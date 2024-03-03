<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use App\Models\DepositMethodSetting;
use App\Models\OffersAndSurveysLog;
use App\Models\PayoutGateway;
use App\Models\User;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EveryFiveMinutesController extends Controller
{




    public function index(){
        $this->processFaucetPayPayments();
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
}
