<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\DepositMethodSetting;
use App\Models\Deposit;
use App\Models\User;

class DepositController extends Controller
{
    public function faucetpaySuccessCallback(Request $request){

        $token = $request->input('token');
        $faucetpayUsername = DepositMethodSetting::where('name', 'faucetpay_merchant_username')->value('value');
        // $payment_info = file_get_contents("https://faucetpay.io/merchant/get-payment/" . $token);
        // $payment_info = json_decode($payment_info, true);
        // $token_status = $payment_info['valid'];

        

        
        // Validate the IPN callback using FaucetPay's API
        $validationResponse = Http::get("https://faucetpay.io/merchant/get-payment/$token");
        $validationData = $validationResponse->json();

        if ($validationData['valid']) {
            $transactionIdFaucetPay = $validationData['transaction_id'];
            $merchantUsername = $validationData['merchant_username'];
            $amount1 = $validationData['amount1'];
            $currency1 = $validationData['currency1'];
            $amount2 = $validationData['amount2'];
            $currency2 = $validationData['currency2'];
            $custom = $validationData['custom'];

            //check if the external unique id exists in the database with completed status, if it does, never add balance again
            $externalTxAlreadyExists = Deposit::where('external_tx', $token)->where('status', 'completed')->exists();
            if ($merchantUsername == $faucetpayUsername && $currency1 == 'USDT' && !$externalTxAlreadyExists) {
                $userId = User::where('unique_user_id', $custom)->value('id');
                $user = User::find($userId);
                Deposit::create([
                    'user_id' => $userId,
                    'method' => 'faucetpay',
                    'amount' => $amount1,
                    'status' => 'completed',
                    'internal_tx' => Str::random(12),
                    'description' => 'transaction faucetpay '.$transactionIdFaucetPay,
                    'external_tx' => $token,
                ]);
                $user->addAdvertiserBalance($amount1);
            }
            
            return response('OK', 200); // Respond with HTTP 200 OK to FaucetPay
        }

        // Invalid IPN callback
        return response('Invalid IPN', 400);

    }
}
