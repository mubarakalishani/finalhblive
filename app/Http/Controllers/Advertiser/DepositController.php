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
        $event = json_decode($payload, true);

        \App\Models\Log::create([
            'user_id' => 1,
            'description' => 'Coinbase Webhook Received ' . $event['data']['code'],
        ]);

        \Illuminate\Support\Facades\Log::info('Coinbase Webhook Received', ['event' => $event]);

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

}
