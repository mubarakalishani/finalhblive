<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\OffersAndSurveysLog;
use App\Models\OfferwallsSetting;
use App\Models\DepositMethodSetting;
use App\Models\PayoutGateway;
use App\Models\SubmittedTaskProof;
use App\Models\Task;
use App\Models\User;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EveryMinuteController extends Controller
{
    public function index(){
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
            $completedOfferExist = OffersAndSurveysLog::where('status', 0)
            ->where('transaction_id', $offer->transaction_id)
            ->exists();
            $offerUser = User::find($offer->worker->id);
            if (!$completedOfferExist) {
                $offerUser->addWorkerBalance($offer->reward);
                $offerUser->increment('diamond_level_balance', $offer->added_expert_level);
                $offerUser->increment('earned_from_offers', $offer->reward);
                $offerUser->increment('total_earned', $offer->reward);
                $offerUser->increment('total_offers_completed');
                $uplineId = $offerUser->upline;
                if($uplineId > 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $offer->upline_commision );
                    $uplineToReward->increment('earned_from_referrals', $offer->upline_commision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $offer->upline_commision . ' from user '. $offerUser->username,
                    ]);
                }
            }
            Log::create([
                'user_id' => $offer->worker->id,
                'description' => 'reward '.$offer->reward.' added from '.$offer->provider_name
            ]);
            $offer->update(['status' => 0]);
        }
        $this->checkAllWithdrawals();
    }


    protected function checkAllWithdrawals(){
        //get all the pending withdrawals
        $pendingWithdrawals = WithdrawalHistory::where('status', 0)->get();
        foreach ($pendingWithdrawals as $pendingWithdrawal) {
            $userId = $pendingWithdrawal->user_id;
            $user = User::find($userId);
            $pendingWithdrawal = WithdrawalHistory::where('user_id', $userId)->whereIn('status', [0,1])->sum('sum');
            if($pendingWithdrawal == null){
                $sumOfWithdrawals = 0;
            }
            if ( $pendingWithdrawal > $user->total_earned) {
                $pendingWithdrawal->update([
                    'status' => 4,
                    'description' => 'sum of withdrawals is greater than the earned balance'
                ]);
            }

            //here check if a user has offers within short time
            $offerRecords = OffersAndSurveysLog::where('payout', '>=', 0.05)->where('user_id', $userId)->orderBy('id')->get();
            $countWithin5Minutes = 0;
            if ($offerRecords->count() > 1) {
                for ($i = 0; $i < count($offerRecords) - 1; $i++) {
                    $currentRecord = $offerRecords[$i];
                    $nextRecord = $offerRecords[$i + 1];
                
                    $timeDifference = Carbon::parse($currentRecord->created_at)
                        ->diffInMinutes(Carbon::parse($nextRecord->created_at));
                
                    if ($timeDifference < 5) {
                        $countWithin5Minutes++;
                    }
                }    
            }
            if ($countWithin5Minutes > 1) {
                $pendingWithdrawal->update([
                    'status' => 4,
                    'description' => 'multiple offers and surveys within very short span of time'
                ]);
            }
        }
    }
}
