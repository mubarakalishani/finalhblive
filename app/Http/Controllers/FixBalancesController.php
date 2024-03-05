<?php

namespace App\Http\Controllers;

use App\Models\FaucetClaim;
use App\Models\OffersAndSurveysLog;
use App\Models\PtcLog;
use App\Models\ShortLinksHistory;
use App\Models\SubmittedTaskProof;
use App\Models\User;
use Illuminate\Http\Request;

class FixBalancesController extends Controller
{
    public function index(){
        // Fetch all users with their related data
        $users = User::with(['ptcLogs', 'faucetClaims', 'offersAndSurveysLogs', 'shortLinksHistories', 'submittedTaskProofs'])
                      ->get();
    
        foreach ($users as $user) {
            // Calculate earned amounts and counts for each user
            $earnedFromPtc = $user->ptcLogs->sum('reward');
            $ptcCount = $user->ptcLogs->count();
            
            $earnedFromFaucet = $user->faucetClaims->sum('claimed_amount');
            $faucetCount = $user->faucetClaims->count();
            
            $earnedFromOffers = $user->offersAndSurveysLogs->where('status', 0)->sum('reward');
            $offersCount = $user->offersAndSurveysLogs->where('status', 0)->count();
            
            $earnedFromShortlinks = $user->shortLinksHistories->sum('reward');
            $shortlinkCount = $user->shortLinksHistories->count();
            
            $earnedFromTasks = $user->submittedTaskProofs->where('status', 1)->sum('amount');
            $tasksCount = $user->submittedTaskProofs->where('status', 1)->count();
            
            // Calculate total earned amount
            $totalEarned = $earnedFromPtc + $earnedFromFaucet + $earnedFromOffers + $earnedFromShortlinks + $earnedFromTasks;
            
            // Update user record with calculated values
            $user->update([
                'earned_from_ptc' => $earnedFromPtc,
                'earned_from_offers' => $earnedFromOffers,
                'earned_from_tasks' => $earnedFromTasks,
                'earned_from_faucet' => $earnedFromFaucet,
                'earned_from_shortlinks' => $earnedFromShortlinks,
                'total_tasks_completed' => $tasksCount,
                'total_offers_completed' => $offersCount,
                'total_ptc_completed' => $ptcCount,
                'total_faucet_completed' => $faucetCount,
                'total_shortlinks_completed' => $shortlinkCount,
                'total_earned' => $totalEarned,
            ]);
        }
    
        echo "fixed successfully";
    }
}
