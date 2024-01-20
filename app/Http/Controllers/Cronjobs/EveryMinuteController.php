<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\OffersAndSurveysLog;
use App\Models\User;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;

class EveryMinuteController extends Controller
{
    public function index(){
        Log::create([
            'user_id' => 1,
            'description' => 'the cronjob rann at '.now()
        ]);
        $pendingWithdrawals = WithdrawalHistory::where('status' , 0)->get();
        foreach ($pendingWithdrawals as $withdrawal) {
            if ( $withdrawal->user->balance < 0 ) {
                $user = User::find($withdrawal->user->id);
                $user->addWorkerBalance($withdrawal->amount_no_fee);
                Log::create([
                    'user_id' => $withdrawal->user->id,
                    'description' => 'withdrawal # '.$withdrawal->id.' is returned blc<0'
                ]);

                $withdrawal->update(['status' => 2]);
            }
        }

        $offersPendingCleared = OffersAndSurveysLog::where(function ($query) {
            $query->whereRaw('NOW() > DATE_ADD(created_at, INTERVAL hold_time MINUTE)');
        })->where('status', 1)->get();

        foreach ($offersPendingCleared as $offer) {
            $offerUser = User::find($offer->worker->id);
                $offerUser->addWorkerBalance($offer->reward);
                $offerUser->increment('diamond_level_balance', $offer->added_expert_level);
                Log::create([
                    'user_id' => $offer->worker->id,
                    'description' => 'reward '.$offer->reward.' added from '.$offer->provider_name
                ]);
        }
    }
}
