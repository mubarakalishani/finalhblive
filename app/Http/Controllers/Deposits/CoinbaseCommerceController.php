<?php

namespace App\Http\Controllers\Deposits;

use App\Http\Controllers\Controller;
use App\Models\DepositMethodSetting;
use Illuminate\Http\Request;

class CoinbaseCommerceController extends Controller
{
    public function handleCoinbaseWebhook(Request $request)
    {
        $payload = $request->getContent();
        $secret = DepositMethodSetting::where('name', 'coinbase_webhook_secret')->value('value'); // Replace with your actual webhook secret

        // Validate the webhook signature
        $signature = $request->header('X-CC-WEBHOOK-SIGNATURE');
        if (hash_equals(hash_hmac('sha256', $payload, $secret), $signature)) {
            // Signature is valid, process the webhook payload
            $event = json_decode($payload, true);
            // Handle the event as needed
            // Example: Log the event
            \Log::info('Coinbase Webhook Received', ['event' => $event]);
        } else {
            // Invalid signature, ignore or log the request
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return response()->json(['status' => 'success']);
    }
}
