<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaucetClaim;
use App\Models\Setting;
use App\Models\User;
use App\Models\IpLog;
use App\Models\CheatLog;
use App\Models\FaucetSetting;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkerFaucetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Assuming you have a model named YourModel
        $lastClaim = FaucetClaim::where('user_id', Auth::user()->id)->latest()->first();

        if ($lastClaim) {
            $createdAt = $lastClaim->created_at;
            $timeAgo = $createdAt->diffForHumans();
        }

        $faucet_claim_time = Setting::where('name', 'faucet_claim_time')->value('value');
        $lastClaimTime = FaucetClaim::where('user_id', Auth::user()->id)
        ->max('created_at');

        if ($lastClaimTime) {
            $timeSinceLastClaim = now()->diffInMinutes($lastClaimTime);
        
            // Calculate the remaining time until the next claim
            $remainingTime = max(0, $faucet_claim_time - $timeSinceLastClaim);
        
            // Store the remaining time in seconds for the countdown
            $countdownValue = $remainingTime * 60; // Convert minutes to seconds
        } else {
            // User has not claimed before, set countdown to 0 or any default value
            $countdownValue = 0;
        }

        $topBanner = FaucetSetting::where('name', 'top_banner')->value('value');
        $leftBanner = FaucetSetting::where('name', 'left_banner')->value('value');
        $rightBanner = FaucetSetting::where('name', 'right_banner')->value('value');
        $centerBanner = FaucetSetting::where('name', 'center_banner')->value('value');
        $bottomBanner = FaucetSetting::where('name', 'bottom_banner')->value('value');

        $faucet_claim_time = Setting::where('name', 'faucet_claim_time')->value('value');
        $faucet_claim_amount = Setting::where('name', 'faucet_claim_amount')->value('value');
        return view('worker.all-faucet', [
            'faucet_claim_time' => $faucet_claim_time, 
            'faucet_claim_amount' => $faucet_claim_amount, 
            'timeAgo' => $timeAgo, 'countdownValue' =>$countdownValue,
            'topBanner' => $topBanner, 'leftBanner' =>$leftBanner, 'rightBanner' => $rightBanner,
            'centerBanner' => $centerBanner, 'bottomBanner' => $bottomBanner
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'h-captcha-response' => 'required|captcha'
        ]);
        $data = array(
            'secret' => env('CAPTCHA_SECRET'),
            'response' => $request->input('h-captcha-response'),
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        // var_dump($response);
        $responseData = json_decode($response);
        $faucet_claim_time = Setting::where('name', 'faucet_claim_time')->value('value');
        $faucet_claim_amount = Setting::where('name', 'faucet_claim_amount')->value('value');
        $claimsCount = FaucetClaim::where('user_id', Auth::user()->id)->where('created_at', '>', now()->subMinutes($faucet_claim_time))->count();

        if($responseData->success) {
            if ( $claimsCount > 0 ) 
            {
                return redirect()->back()->with('error',"You already claimed the faucet within last " . $faucet_claim_time ." Minutes!");
            }
            else
            {
                $amount = Setting::where('name', 'faucet_claim_amount')->value('value');
                FaucetClaim::create([
                    'user_id' => Auth::user()->id,
                    'claimed_amount' => $amount,
                ]);
                $worker = User::find(auth()->user()->id);
                $worker->addWorkerBalance($amount);
                $worker->increment('total_earned', $amount);
                $worker->increment('earned_from_faucet' , $amount);
                $worker->increment('total_faucet_completed');
                //if there is an upline of the user, credit his respective referral commission
                if ($worker->upline !=0 && Setting::where('name', 'faucet_referral_status')->value('value') == 'enabled') {
                    $upline = User::find($worker->upline);
                    $uplineCommission = ($amount/100) * Setting::where('name', 'faucet_referral_commission')->value('value');
                    $upline->addWorkerBalance($uplineCommission);
                    $upline->increment('earned_from_referrals', $uplineCommission);
                    Log::create([
                        'user_id' => $worker->upline,
                        'description' => 'received Referral commission from '.$worker->username.' for completing a task'
                    ]);
                }
                return redirect()->back()->with('message',"You claimed $".$faucet_claim_amount." successfully");
            }
            
        } 
        else 
        {
            return redirect()->back()->with('error',"Invalid captcha! Try again");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
