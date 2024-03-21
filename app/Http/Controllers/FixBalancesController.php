<?php

namespace App\Http\Controllers;

use App\Models\FaucetClaim;
use App\Models\OffersAndSurveysLog;
use App\Models\PtcLog;
use App\Models\ShortLinksHistory;
use App\Models\Statistic;
use App\Models\SubmittedTaskProof;
use App\Models\User;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;

class FixBalancesController extends Controller
{
    public function index(){
        $this->fixStatistics();
        // $users = $users = User::where('id', '>=', 15000)->get();
        // foreach ($users as $user) {
        //     $earnedFromPtc = 0;
        //     $ptcCount = 0;
        //     $earnedFromFaucet = 0;
        //     $faucetCount = 0;
        //     $earnedFromOffers = 0;
        //     $offersCount = 0;
        //     $earnedFromShortlinks = 0;
        //     $shotlinkCount = 0;
        //     $earnedFromTasks = 0;
        //     $tasksCount = 0;




        //     $earnedFromPtc = PtcLog::where('worker_id', $user->id)->sum('reward');
        //     $ptcCount = PtcLog::where('worker_id', $user->id)->count();
        //     $earnedFromFaucet = FaucetClaim::where('user_id', $user->id)->sum('claimed_amount');
        //     $faucetCount = FaucetClaim::where('user_id', $user->id)->count();
        //     $earnedFromOffers = OffersAndSurveysLog::where('user_id', $user->id)->where('status', 0)->sum('reward');
        //     $offersCount = OffersAndSurveysLog::where('user_id', $user->id)->where('status', 0)->count();
        //     $earnedFromShortlinks = ShortLinksHistory::where('user_id', $user->id)->sum('reward');
        //     $shotlinkCount = ShortLinksHistory::where('user_id', $user->id)->count();
        //     $earnedFromTasks = SubmittedTaskProof::where('worker_id', $user->id)->where('status', 1)->sum('amount');
        //     $tasksCount = SubmittedTaskProof::where('worker_id', $user->id)->where('status', 1)->count();
        //     $totalEarned = $earnedFromPtc + $earnedFromFaucet + $earnedFromOffers + $earnedFromShortlinks + $earnedFromTasks;
            
        //     $user->update([
        //         'earned_from_ptc' => $earnedFromPtc,
        //         'earned_from_offers' => $earnedFromOffers,
        //         'earned_from_tasks' => $earnedFromTasks,
        //         'earned_from_faucet' => $earnedFromFaucet,
        //         'earned_from_shortlinks' => $earnedFromShortlinks,
        //         'total_tasks_completed' => $tasksCount,
        //         'total_offers_completed' => $offersCount,
        //         'total_ptc_completed' => $ptcCount,
        //         'total_faucet_completed' => $faucetCount,
        //         'total_shortlinks_completed' => $shotlinkCount,
        //         'total_earned' => $totalEarned,
        //     ]);

        //     if($user->id > 16000){
        //         echo "completed";
        //         return 0;
        //     }
        // }

        // echo "fixed successfully";



    //     $users = User::with(['payouts' => function ($query) {
    //         $query->where('status', 1);
    // }])->get();
    //     foreach ($users as $user) {
    //         $totalWithdrawn = WithdrawalHistory::where('user_id', $user->id)->where('status', 1)->sum('amount_after_fee');
    //         $user->update([
    //             'total_withdrawn' => $totalWithdrawn
    //         ]);
    //     }
        
    }

    protected function fixStatistics(){
        $statistic = Statistic::latest()->firstOrCreate([]);
        // Get the first and last day of the current month
        $firstDayOfMonth = now()->startOfMonth();
        $lastDayOfMonth = now()->endOfMonth();

        // Get the first and last day of the last month
        $firstDayOfLastMonth = now()->subMonth()->startOfMonth();
        $lastDayOfLastMonth = now()->subMonth()->endOfMonth();
        $statistic->tasks_total_earned = SubmittedTaskProof::where('status', 1)->sum('amount');
        $statistic->tasks_this_month = SubmittedTaskProof::where('status', 1)
        ->where('created_at', '=', $firstDayOfMonth->year)
        ->whereMonth('created_at', '=', $firstDayOfMonth->month)
        ->sum('amount');
        $statistic->tasks_last_month = SubmittedTaskProof::where('status', 1)->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])->sum('amount');

        $statistic->offers_total_earned = OffersAndSurveysLog::whereIn('status', [0,1])->sum('payout');
        $statistic->offers_this_month = OffersAndSurveysLog::whereIn('status', [1,0])
        ->where('created_at', '=', $firstDayOfMonth->year)
        ->whereMonth('created_at', '=', $firstDayOfMonth->month)
        ->sum('payout');
        $statistic->offers_last_month = OffersAndSurveysLog::whereIn('status', [0,1])->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])->sum('payout');

        $statistic->shortlinks_total_earned = ShortLinksHistory::sum('reward');
        $statistic->shortlinks_this_month = ShortLinksHistory::where('created_at', '=', $firstDayOfMonth->year)
        ->whereMonth('created_at', '=', $firstDayOfMonth->month)
        ->sum('reward');
        $statistic->shortlinks_last_month = ShortLinksHistory::whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])->sum('reward');

        $statistic->ptc_total_earned = PtcLog::sum('reward');
        $statistic->ptc_this_month = PtcLog::where('created_at', '=', $firstDayOfMonth->year)
        ->whereMonth('created_at', '=', $firstDayOfMonth->month)
        ->sum('reward');
        $statistic->ptc_last_month = PtcLog::whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])->sum('reward');

        $statistic->faucet_total_earned = FaucetClaim::sum('claimed_amount');
        $statistic->faucet_this_month = FaucetClaim::where('created_at', '=', $firstDayOfMonth->year)
        ->whereMonth('created_at', '=', $firstDayOfMonth->month)
        ->sum('claimed_amount');
        $statistic->faucet_last_month = FaucetClaim::whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])->sum('claimed_amount');
        
    }
}
