<?php

namespace App\Http\Controllers\Campaings;

use App\Http\Controllers\Controller;
use App\Models\NotikConversion;
use App\Models\User;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function notik(Request $request){
        $clickId = $request->click_id;
        $campaignId = $request->oid;
        $campaignName = $request->oname;
        $trafficSource = $request->source;
        $clickIp = $request->click_ip;
        $countryCode = $request->country_code;

        if ($clickId !== null && $campaignId !== null) {
            // Store the parameters in the session
            $request->session()->put('click_id', $clickId);
            $request->session()->put('oid', $campaignId);
            $request->session()->put('oname', $campaignName);
            $request->session()->put('source', $trafficSource);
            $request->session()->put('click_ip', $clickIp);
            $request->session()->put('country_code', $countryCode);
        }

        
        return redirect('https://handbucks.com');
    }

    public function notiks2S() {
        $conversions = NotikConversion::where('status', 0)->get();
        
        foreach ($conversions as $conversion) {
            $user = User::where('username', $conversion->username)->first();
            
            if ($user && $user->total_earned >= 2) {
                $callbackUrl = 'https://postback.notik.me/adv-pb/KACTqADeqM322rxI?oid='.$conversion->campaign_id.'&click_id='.$conversion->click_id.'&conversion_ip='.$conversion->user_ip.'&pbSec=yAQcMJ0Q';
                
                // Initialize cURL session
                $ch = curl_init();
    
                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $callbackUrl); // Corrected variable name
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
                // Execute the GET request
                $response = curl_exec($ch);
    
                // Check for cURL errors
                if (curl_errno($ch)) {
                    echo 'Error: ' . curl_error($ch);
                } else {
                    // Output the response from the server
                    echo 'Response: ' . $response;
                }
    
                // Close cURL session
                curl_close($ch);

                $conversion->update([
                    'status' => 1
                ]);
            }
        }
    }    
}
