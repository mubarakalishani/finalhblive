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

        $this->processFaucetPayPayments();
        $this->resolveResubmitExhaustTasks();
        $this->updateTasks();
    }



    protected function processFaucetPayPayments(){
        $apiEndpoint = 'https://faucetpay.io/api/v1/send';
        $apiKey = DepositMethodSetting::where('name', 'faucetpay_merchant_api')->value('value');

        $pendingFaucetPayWithdrawals = WithdrawalHistory::where('method', 'Faucet Pay')
        ->where('status', 0)
        ->get();
        if (PayoutGateway::where('name', 'Faucet Pay')->value('instant') == 1) {
            foreach ($pendingFaucetPayWithdrawals as $withdrawal) {
                $walletAddress = $withdrawal->wallet;
                // Prepare the data to be sent in the request
                $data = [
                    'api_key' => $apiKey,
                    'currency' => 'USDT',
                    'amount' => $withdrawal->amount_after_fee * 100000000,
                    'to' => $walletAddress
                ];

                // Initialize cURL session
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Execute cURL session
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    echo 'Curl error: ' . curl_error($ch);
                }

                // Close cURL session
                curl_close($ch);

                // Decode the JSON response
                $result = json_decode($response, true);
                if ($result['status'] == 200 && $result['message'] == 'OK') {
                    $withdrawal->update(['status' => 1]);
                }
            }
        }  
    }


    protected function resolveResubmitExhaustTasks(){
        //get all the proofs for whose the resubmission allowed time is passed and mark them as resubmit time exhausted
        $resubmitExhaustedProofs = SubmittedTaskProof::where(function ($query) {
            $query->whereRaw('NOW() > DATE_ADD(updated_at, INTERVAL 3 DAY)');
        })->where('status', 3)->get();
        foreach ($resubmitExhaustedProofs as $resubmitExhaustedProof) {
            $task= Task::find($resubmitExhaustedProof->task_id);
            $employer = User::where($task->employer_id);
            $resubmitExhaustedProof->update([
                'status' => 7,
            ]);
            $employer->increment('deposit_balance', $resubmitExhaustedProof->amount);
            Log::create([
                'user_id' => $task->employer_id,
                'description' => 'resubmit time passed amount for proof id '.$resubmitExhaustedProof->id.' for task # '. $resubmitExhaustedProof->task_id,
            ]);
        }
    }

    protected function markTasksBudgetExceeded(){
        $tasks = Task::where('status', 1)->get();
        foreach ($tasks as $task) {
            $employer = User::where($task->employer_id);
            
        }
    }

    protected function updateTasks(){

        //now get all the pending proofs that are passed the employer allowed review time
        $employerReviewPassedProofs = SubmittedTaskProof::whereHas('task', function ($query) {
            $query->whereRaw('DATEDIFF(CURRENT_DATE, submitted_task_proofs.updated_at) > tasks.rating_time');
        })
        ->where('status', 0)
        ->get();

        foreach ($employerReviewPassedProofs as $employerReviewPassedProof) {
            $worker = User::findOrFail($employerReviewPassedProof->user_id);
            $worker->increment('balance', $employerReviewPassedProof->amount);
            $worker->increment('earned_from_tasks', $employerReviewPassedProof->amount);
            $worker->increment('total_tasks_completed');

            $employerReviewPassedProof->update([
                'status' => 1
            ]);
        }
    }
}
