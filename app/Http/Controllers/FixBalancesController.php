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
        $users = $users = User::where('id', '>=', 7000)->get();
        foreach ($users as $user) {
            $earnedFromPtc = 0;
            $ptcCount = 0;
            $earnedFromFaucet = 0;
            $faucetCount = 0;
            $earnedFromOffers = 0;
            $offersCount = 0;
            $earnedFromShortlinks = 0;
            $shotlinkCount = 0;
            $earnedFromTasks = 0;
            $tasksCount = 0;




            $earnedFromPtc = PtcLog::where('worker_id', $user->id)->sum('reward');
            $ptcCount = PtcLog::where('worker_id', $user->id)->count();
            $earnedFromFaucet = FaucetClaim::where('user_id', $user->id)->sum('claimed_amount');
            $faucetCount = FaucetClaim::where('user_id', $user->id)->count();
            $earnedFromOffers = OffersAndSurveysLog::where('user_id', $user->id)->where('status', 0)->sum('reward');
            $offersCount = OffersAndSurveysLog::where('user_id', $user->id)->where('status', 0)->count();
            $earnedFromShortlinks = ShortLinksHistory::where('user_id', $user->id)->sum('reward');
            $shotlinkCount = ShortLinksHistory::where('user_id', $user->id)->count();
            $earnedFromTasks = SubmittedTaskProof::where('worker_id', $user->id)->where('status', 1)->sum('amount');
            $tasksCount = SubmittedTaskProof::where('worker_id', $user->id)->where('status', 1)->count();
            $totalEarned = $earnedFromPtc + $earnedFromFaucet + $earnedFromOffers + $earnedFromShortlinks + $earnedFromTasks;
            
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
                'total_shortlinks_completed' => $shotlinkCount,
                'total_earned' => $totalEarned,
            ]);

            if($user->id > 9000){
                echo "completed";
                return 0;
            }
        }

        echo "fixed successfully";
        
    }
}
