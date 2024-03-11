<?php

namespace App\Http\Controllers\Campaings;

use App\Http\Controllers\Controller;
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
}
