<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestFaucetPayWithdraw extends Controller
{
    public function test(){
        $apiEndpoint = 'https://faucetpay.io/api/v1/send';
        $apiKey = '';

        $walletAddress = 'TVLGvwQsrhVEZcad9BjCtBhJQCkqYvbAbr';

        // Prepare the data to be sent in the request
        $data = [
            'api_key' => $apiKey,
            'currency' => 'USDT',
            'amount' => 80000000,
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
            return $result;
        } else {
            return $result;
        }
    }
}
