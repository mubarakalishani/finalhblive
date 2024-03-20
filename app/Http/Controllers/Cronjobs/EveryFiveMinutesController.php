<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use App\Models\DepositMethodSetting;
use App\Models\Log;
use App\Models\OffersAndSurveysLog;
use App\Models\PayoutGateway;
use App\Models\SubmittedTaskProof;
use App\Models\Task;
use App\Models\User;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EveryFiveMinutesController extends Controller
{




    public function index(){
        $this->processFaucetPayPayments();
        $this->resolveResubmitExhaustTasks();
        $this->resolveRejectedProofs();
        $this->resolveAppealTimeExhaustedProofs();
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
                    $user = User::find($withdrawal->user_id);
                    $user->increment('total_withdrawn', $withdrawal->amount_no_fee);
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
            $employer = User::find($task->employer_id);
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


    protected function resolveRejectedProofs(){
        //get all the proofs for whose the resubmission allowed time is passed and mark them as resubmit time exhausted
        $rejectedDisputeTimeExhaustedProofs = SubmittedTaskProof::where(function ($query) {
            $query->whereRaw('NOW() >= DATE_ADD(updated_at, INTERVAL 2 DAY)');
        })->where('status', 2)->get();
        foreach ($rejectedDisputeTimeExhaustedProofs as $rejectedDisputeTimeExhaustedproof) {
            $task= Task::find($rejectedDisputeTimeExhaustedproof->task_id);
            $employer = User::find($task->employer_id);
            $rejectedDisputeTimeExhaustedproof->update([
                'status' => 8,
            ]);
            $employer->increment('deposit_balance', $rejectedDisputeTimeExhaustedproof->amount);
            Log::create([
                'user_id' => $task->employer_id,
                'description' => 'rejected permanently and added blc back as dispute time passed p#'.$rejectedDisputeTimeExhaustedproof->id.' for task # '. $rejectedDisputeTimeExhaustedproof->task_id,
            ]);
        }
    }


    protected function resolveAppealTimeExhaustedProofs(){
        //get all the proofs for whose the resubmission allowed time is passed and mark them as resubmit time exhausted
        $appealTimeExhaustedProofs = SubmittedTaskProof::where('updated_at', '<=', now()->subHours(30))
        ->where('status', 6)->get();
        foreach ($appealTimeExhaustedProofs as $appealTimeExhaustedProof) {
            $task= Task::find($appealTimeExhaustedProof->task_id);
            $employer = User::find($task->employer_id);
            $appealTimeExhaustedProof->update([
                'status' => 9,
            ]);
            $employer->increment('deposit_balance', $appealTimeExhaustedProof->amount);
            Log::create([
                'user_id' => $task->employer_id,
                'description' => 'reject permanently and add blc back as appeal allowed time passed p#'.$appealTimeExhaustedProof->id.' for task # '. $appealTimeExhaustedProof->task_id,
            ]);
        }
    }
}
