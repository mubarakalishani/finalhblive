<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\DepositMethodSetting;
use App\Models\Deposit;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;

class DepositController extends Controller
{
    public function faucetpaySuccessCallback(Request $request){

        $token = $request->input('token');
        $faucetpayUsername = DepositMethodSetting::where('name', 'faucetpay_merchant_username')->value('value');

        // Validate the IPN callback using FaucetPay's API
        $validationResponse = Http::get("https://faucetpay.io/merchant/get-payment/$token");
        $validationData = $validationResponse->json();
        if ($validationData['valid'] == true) {
            $transactionIdFaucetPay = $validationData['transaction_id'];
            $merchantUsername = $validationData['merchant_username'];
            $amount1 = $validationData['amount1'];
            $currency1 = $validationData['currency1'];
            $amount2 = $validationData['amount2'];
            $currency2 = $validationData['currency2'];
            $custom = $validationData['custom'];

            //check if the external unique id exists in the database with completed status, if it does, never add balance again
            $externalTxAlreadyExists = Deposit::where('external_tx', $transactionIdFaucetPay)->where('status', 'completed')->exists();
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
                    'external_tx' => $transactionIdFaucetPay ,
                ]);
                $user->addAdvertiserBalance($amount1);
            }
            
            return response('OK', 200); // Respond with HTTP 200 OK to FaucetPay
        }

        // Invalid IPN callback
        return response('Invalid IPN', 400);

    }


    // public function handleCoinbaseWebhook(Request $request)
    // {
    //     $payload = $request->getContent();
    //     $secret = DepositMethodSetting::where('name', 'coinbase_webhook_secret')->value('value');

    //     // Validate the webhook signature
    //     $signature = $request->header('X-CC-WEBHOOK-SIGNATURE');
    //     if (hash_equals(hash_hmac('sha256', $payload, $secret), $signature)) {
    //         // Signature is valid, process the webhook payload
    //         $event = json_decode($payload, true);
    //         \App\Models\Log::create([
    //             'user_id' => 1,
    //             'description' => 'Coinbase Webhook Received '.$event->data->code,
    //         ]); 
    //         \Illuminate\Support\Facades\Log::info('Coinbase Webhook Received', ['event' => $event]);

    //         switch ($event->type) {
    //             case 'charge:created':
    //                 $status = 'created';
    //                 break;
    //             case 'charge:pending':
    //                 $status = 'pending';
    //                 break;
    //             case 'charge:confirmed':
    //                 $status = 'completed';
    //                 break;
    //             case 'charge:failed':
    //                 $status = 'failed';
    //                 break;            
                
    //             default:
    //                 $status = 'created';
    //                 break;
    //         }
    //         $externalTxAlreadyExists = Deposit::where('external_tx', $event->data->id)->exists();
    //         $uniqueUserId = $event->data->metadata->user_id;
    //         $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
    //         $user = User::find($userId);
    //         if (!$externalTxAlreadyExists) {
    //             Deposit::create([
    //                 'user_id' => $userId,
    //                 'method' => 'coinbasecommerce',
    //                 'amount' => $event->data->pricing->local->amount,
    //                 'status' => $status,
    //                 'internal_tx' => Str::random(12),
    //                 'description' => 'transaction Coinbase commerce id'.$event->data->id.' status '.$status,
    //                 'external_tx' => $event->data->id,
    //             ]);
    //         }
    //         else {
    //             $transaction = Deposit::where('external_tx', $event->data->id)
    //             ->where('status', '!=', 'completed');
    //             $transaction->update([
    //                 'status' => $status,
    //             ]);
    //             switch ($status) {
    //                 case 'completed':
    //                     $user->addAdvertiserBalance($event->data->pricing->settlement->amount);
    //                     break;
                    
    //                 default:
    //                     $transaction->update(['status' => $status]);
    //                     break;
    //             }
    //         }
            
    //     } else {
    //         // Invalid signature, ignore or log the request
        
    //         return response('Invalid signature', 401);
    //     }

    //     return response('OK', 200);
    // }

    public function handleCoinbaseWebhook(Request $request)
{
    $payload = $request->getContent();
    $secret = DepositMethodSetting::where('name', 'coinbase_webhook_secret')->value('value');

    // Validate the webhook signature
    $signature = $request->header('X-CC-WEBHOOK-SIGNATURE');
    if (hash_equals(hash_hmac('sha256', $payload, $secret), $signature)) {
        // Signature is valid, process the webhook payload
        $receivedEvent = $request->all();

        \Illuminate\Support\Facades\Log::info('Coinbase Webhook Received', ['event' => $receivedEvent]);
        $event = $receivedEvent['event'];
        switch ($event['type']) {
            case 'charge:created':
                $status = 'created';
                break;
            case 'charge:pending':
                $status = 'pending';
                break;
            case 'charge:confirmed':
                $status = 'completed';
                break;
            case 'charge:failed':
                $status = 'failed';
                break;
            default:
                $status = 'created';
                break;
        }

        $externalTxAlreadyExists = Deposit::where('external_tx', $event['data']['id'])->exists();
        $uniqueUserId = $event['data']['metadata']['user_id'];
        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);

        if (!$externalTxAlreadyExists) {
            Deposit::create([
                'user_id' => $userId,
                'method' => 'coinbasecommerce',
                'amount' => $event['data']['pricing']['local']['amount'],
                'status' => $status,
                'internal_tx' => Str::random(12),
                'description' => 'transaction Coinbase commerce id' . $event['data']['id'] . ' status ' . $status,
                'external_tx' => $event['data']['id'],
            ]);
        } else {
            $transaction = Deposit::where('external_tx', $event['data']['id'])
                ->where('status', '!=', 'completed')
                ->first();

            if ($transaction) {
                switch ($status) {
                    case 'completed':
                        $user->addAdvertiserBalance($event['data']['pricing']['settlement']['amount']);
                        break;
                    default:
                        $transaction->update(['status' => $status]);
                        break;
                }
            }
        }
    } else {
        // Invalid signature, ignore or log the request
        return response('Invalid signature', 401);
    }

    return response('OK', 200);
}


public function handlePerfectMoneyWebhook(Request $request){
    $faucetPayStatus = Deposit::where('method', 'perfectmoney')->value('status');
    if ($faucetPayStatus != 1) {
        return 0;
    }
    $transactionId = $request->input('PAYMENT_ID');
    $amount = $request->input('PAYMENT_AMOUNT');
    $externalTx = $request->input('PAYMENT_BATCH_NUM');
    $transactionExist = Deposit::where('internal_tx', $transactionId)
    ->where('method', 'perfectmoney')
    ->exists();
    


    $secretKey = DepositMethodSetting::where('name', 'perfectmoney_secret')->value('value');
    $hashedSecretKey = strtoupper(md5($secretKey));
    $string =
    (string)$request->input('PAYMENT_ID') . ":" .
    (string)$request->input('PAYEE_ACCOUNT') . ":" .
    (string)$request->input('PAYMENT_AMOUNT') . ":" .
    (string)$request->input('PAYMENT_UNITS') . ":" .
    (string)$request->input('PAYMENT_BATCH_NUM') . ":" .
    (string)$request->input('PAYER_ACCOUNT') . ":" .
    (string)$hashedSecretKey . ":" .
    (string)$request->input('TIMESTAMPGMT');

    $hash=strtoupper(md5($string));
    \App\Models\Log::create([
        'user_id' => 1,
        'description' => 'perfectmoney calculated hash = '.$hash,
    ]);
    if($hash != $request->input('V2_HASH')){
        return "invalid hash";
    }

    

    

    if(!$transactionExist){
        $userId = User::where('unique_user_id', $request->input('USER_ID'))->value('id');
        $user = User::find($userId);
        Deposit::create([
            'user_id' => $userId,
            'method' => 'perfectmoney',
            'amount' => $amount,
            'status' => 'completed',
            'internal_tx' => Str::random(12),
            'description' => 'transaction perfectmoney completed',
            'external_tx' => $externalTx ,
        ]);
        $user->addAdvertiserBalance($amount);
    }


}



public function createCoinbasePayLink(Request $request){
        $curl = curl_init();
        $postFilds=array(
            'name' => 'Deposit to '.env('APP_NAME'),
            'redirect_url' => url('/advertiser/deposit'),
            'cancel_url' => url('/advertiser/deposit'),
            'success_url' => url('/advertiser/deposit'),
            'local_price' => [
                'amount' => $request->input('amount'),
                'currency' => 'USD',
            ],
            'pricing_type'=>'fixed_price',
            'metadata'=>array(
                'user_id'=> auth()->user()->unique_user_id,
                'username' => auth()->user()->username,
                )
        );
        $postFilds=urldecode(http_build_query($postFilds));
        curl_setopt_array($curl, 
            array(
                CURLOPT_URL => "https://api.commerce.coinbase.com/charges",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $postFilds,
                CURLOPT_HTTPHEADER => array(
                    "X-CC-Api-Key: ".DepositMethodSetting::where('name', 'coinbase_api')->value('value'),
                    "X-CC-Version: 2018-03-22",
                    "content-type: multipart/form-data"
                ),
            )
        );
        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($result);
        return new RedirectResponse($response->data->hosted_url);
}


public function createPerfectMoneyPayLink(Request $request){
    $amount = $request->input('amount');
    $transactionId = Str::random(12);
    // Deposit::create([
    //     'user_id' => auth()->user()->id,
    //     'method' => 'perfectmoney',
    //     'amount' => $amount,
    //     'status' => 'Waiting For Payment',
    //     'internal_tx' => $transactionId,
    //     'description' => 'transaction Perfect Money id' . $transactionId . ' status not paid yet',
    //     'external_tx' => 'no external tx yet',
    // ]);

    return view('advertiser.deposit.perfectmoney', [
        'amount' => $amount,
        'transactionId' => $transactionId
    ]);
}

// public function createPayeerPayLink(Request $request){
//     $amount = $request->input('amount');
//     $transactionId = Str::random(12);
//     Deposit::create([
//         'user_id' => auth()->user()->id,
//         'method' => 'payeer',
//         'amount' => $amount,
//         'status' => 'Waiting For Payment',
//         'internal_tx' => $transactionId,
//         'description' => 'transaction Perfect Money id' . $transactionId . ' status not paid yet',
//         'external_tx' => 'no external tx yet',
//     ]);

//     return view('advertiser.deposit.payeer', [
//         'amount' => $amount,
//         'transactionId' => $transactionId
//     ]);
// }

}
