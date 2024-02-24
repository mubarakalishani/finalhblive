<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PtcAd;
use App\Models\PtcLog;
use App\Models\User;
use App\Models\CheatLog;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PtcAdController extends Controller
{
    public function show($uniqueId)
    {
        $ptcAd = PtcAd::where('unique_id', $uniqueId)->get();
        $ptcAdData = json_decode($ptcAd, true);

        // Check if the decoding was successful
        if ($ptcAdData !== null) {
            // Access the desired values
            $seconds = $ptcAdData[0]['seconds'];
        }


        
        return view('worker.ptc.iframe_ptc', ['uniqueId' => $uniqueId, 'seconds' => $seconds]);
    }

    public function showIframe(){
        $availableIframePtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', Auth::user()->country)
        ->where('status', 1)
        ->where('type', 0)
        ->where('ad_balance', '>', 0)
        ->orderBy('reward_per_view', 'desc')
        ->get();

        foreach ($availableIframePtcAds as $ad) {
            $lastClaim = PtcLog::where('worker_id', Auth::user()->id)->where('ad_id', $ad->id)->latest()->first();
            if ($lastClaim) {
                $createdAt = Carbon::parse( $lastClaim->created_at);
                // Calculate the time difference in hours and minutes
                $timeDifference = now()->diff($createdAt);
                // Calculate the total time difference in minutes
                $totalMinutesDifference = $timeDifference->days * 24 * 60 + $timeDifference->h * 60 + $timeDifference->i;
                $totalSecondsDifference = $timeDifference->days * 24 * 60 * 60 + $timeDifference->h * 60 * 60 + $timeDifference->i * 60 + $timeDifference->s;
                $remainingHours = $ad->revision_interval - $timeDifference->h;
                $remainingMinutes = 60 - $timeDifference->i;
                $remainingSeconds = 60 - $timeDifference->s;
                // Store the remaining time in a variable
                $remainingTime = $remainingHours . ' hours ' . $remainingMinutes . ' minutes';

                // You can now use $remainingTime as needed, for example, store it in the database
                $ad->remaining_hours = $remainingHours;
                $ad->remaining_time = $remainingTime;
                $ad->totalMinutesDifference = $totalMinutesDifference;
                $ad->totalSecondsDifference = $totalSecondsDifference;
                $ad->remainingSeconds = $remainingSeconds;

            }
        }


        $availableWindowPtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', Auth::user()->country)
        ->where('status', 1)
        ->where('type', 1)
        ->where('ad_balance', '>', 0)
        
        ->orderBy('reward_per_view', 'desc')
        ->get();

        foreach ($availableWindowPtcAds as $ad) {
            $lastClaim = PtcLog::where('worker_id', auth()->user()->id)->where('ad_id', $ad->id)->latest()->first();
            if ($lastClaim) {
                $createdAt = Carbon::parse( $lastClaim->created_at);
                // Calculate the time difference in hours and minutes
                $timeDifference = now()->diff($createdAt);
                // Calculate the total time difference in minutes
                $totalSecondsDifference = $timeDifference->days * 24 * 60 * 60 + $timeDifference->h * 60 * 60 + $timeDifference->i * 60 + $timeDifference->s;
                $totalMinutesDifference = $timeDifference->days * 24 * 60 + $timeDifference->h * 60 + $timeDifference->i;
                $remainingHours = $ad->revision_interval - $timeDifference->h;
                $remainingMinutes = 60 - $timeDifference->i;
                $remainingSeconds = 60 - $timeDifference->s;
                // Store the remaining time in a variable
                $remainingTime = $remainingHours . ' hours ' . $remainingMinutes . ' minutes';

                // You can now use $remainingTime as needed, for example, store it in the database
                $ad->remaining_hours = $remainingHours;
                $ad->remaining_time = $remainingTime;
                $ad->totalMinutesDifference = $totalMinutesDifference;
                $ad->totalSecondsDifference = $totalSecondsDifference;
                $ad->remainingSeconds = $remainingSeconds;

            }
        }

        return view('worker.ptc.all-iframe', ['availableIframePtcAds' => $availableIframePtcAds, 'availableWindowPtcAds' => $availableWindowPtcAds]);
    }

    public function showWindow(){
        $availableWindowPtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', Auth::user()->country)
        ->where('status', 1)
        ->where('type', 1)
        ->where('ad_balance', '>', 0)
        ->orderBy('reward_per_view', 'desc')
        ->get();

        foreach ($availableWindowPtcAds as $ad) {
            $lastClaim = PtcLog::where('worker_id', auth()->user()->id)->where('ad_id', $ad->id)->latest()->first();
            if ($lastClaim) {
                $createdAt = Carbon::parse( $lastClaim->created_at);
                // Calculate the time difference in hours and minutes
                $timeDifference = now()->diff($createdAt);
                // Calculate the total time difference in minutes
                $totalSecondsDifference = $timeDifference->days * 24 * 60 * 60 + $timeDifference->h * 60 * 60 + $timeDifference->i * 60 + $timeDifference->s;
                $totalMinutesDifference = $timeDifference->days * 24 * 60 + $timeDifference->h * 60 + $timeDifference->i;
                $remainingHours = $ad->revision_interval - $timeDifference->h;
                $remainingMinutes = 60 - $timeDifference->i;
                $remainingSeconds = 60 - $timeDifference->s;
                // Store the remaining time in a variable
                $remainingTime = $remainingHours . ' hours ' . $remainingMinutes . ' minutes';

                // You can now use $remainingTime as needed, for example, store it in the database
                $ad->remaining_hours = $remainingHours;
                $ad->remaining_time = $remainingTime;
                $ad->totalMinutesDifference = $totalMinutesDifference;
                $ad->totalSecondsDifference = $totalSecondsDifference;
                $ad->remainingSeconds = $remainingSeconds;
                if ($totalSecondsDifference < 10) {
                    $ad->remainingSeconds = 10;
                }

            }
        }



        $availableIframePtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', Auth::user()->country)
        ->where('status', 1)
        ->where('type', 0)
        ->where('ad_balance', '>', 0)
        ->orderBy('reward_per_view', 'desc')
        
        ->get();

        foreach ($availableIframePtcAds as $ad) {
            $lastClaim = PtcLog::where('worker_id', Auth::user()->id)->where('ad_id', $ad->id)->latest()->first();
            if ($lastClaim) {
                $createdAt = Carbon::parse( $lastClaim->created_at);
                // Calculate the time difference in hours and minutes
                $timeDifference = now()->diff($createdAt);
                // Calculate the total time difference in minutes
                $totalMinutesDifference = $timeDifference->days * 24 * 60 + $timeDifference->h * 60 + $timeDifference->i;
                $totalSecondsDifference = $timeDifference->days * 24 * 60 * 60 + $timeDifference->h * 60 * 60 + $timeDifference->i * 60 + $timeDifference->s;
                $remainingHours = $ad->revision_interval - $timeDifference->h;
                $remainingMinutes = 60 - $timeDifference->i;
                $remainingSeconds = 60 - $timeDifference->s;
                // Store the remaining time in a variable
                $remainingTime = $remainingHours . ' hours ' . $remainingMinutes . ' minutes';

                // You can now use $remainingTime as needed, for example, store it in the database
                $ad->remaining_hours = $remainingHours;
                $ad->remaining_time = $remainingTime;
                $ad->totalMinutesDifference = $totalMinutesDifference;
                $ad->totalSecondsDifference = $totalSecondsDifference;
                $ad->remainingSeconds = $remainingSeconds;

            }
        }
        return view('worker.ptc.all-window', ['availableWindowPtcAds' => $availableWindowPtcAds, 'availableIframePtcAds' => $availableIframePtcAds]);
    }

    public function showYoutube(){
        
    }



    public function iframeSubmit(Request $request, $uniqueId)
    {
        $unique_id = $uniqueId;
        $adId = PtcAd::where('unique_id', $unique_id)->value('id');
        $ad = PtcAd::find($adId);
        $viewsNeeded = $ad->views_needed;
        $viewsCompleted = $ad->views_completed;
        $employer = User::find($ad->employer_id);
        $worker = User::find(Auth::user()->id);
        // $validate = Validator::make($request->all(), [
        //     'h-captcha-response' => 'required|captcha'
        // ]);
        // $data = array(
        //     'secret' => Setting::where('name', 'hcaptcha_secret')->value('value'),
        //     'response' => $request->input('h-captcha-response'),
        // );
        // $verify = curl_init();
        // curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        // curl_setopt($verify, CURLOPT_POST, true);
        // curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($verify);
        // // var_dump($response);
        // $responseData = json_decode($response);
        // if($responseData->success) {
            //check if the user has already claimed the max allowed times of this link within 24hrs
            $claimsCount = PtcLog::where('worker_id', Auth::user()->id)->where('created_at', '>', now()->subHours($ad->revision_interval))->where('ad_id', $adId)->count();
            if ($claimsCount > 0) {
                CheatLog::create([
                    'user_id' => Auth::user()->id,
                    'description' => 'trying to double view same ptc ad '.$adId.' before required time passes',
                ]);
                return redirect(url('/views/iframe'))->with('error', 'You have already watched this ad within allowed timeframe');
            }

            //check for user ip address quality for vpn or proxies

            //check if the required views are already completed
            if($viewsCompleted >= $viewsNeeded){
                return redirect(url('/views/iframe'))->with('error', 'The Ad is not active');
            }

            PtcLog::create([
                'worker_id' => Auth::user()->id,
                'ad_id' => $adId,
                'reward' => $ad->reward_per_view,
                'ip' => request()->ip(),
            ]);
            $ad->increment('views_completed');
            $ad->decrement('ad_balance', $ad->reward_per_view);
            $worker->addWorkerBalance($ad->reward_per_view);
            $worker->increment('total_earned', $ad->reward_per_view);
            $worker->increment('earned_from_ptc' , $ad->reward_per_view);
            $worker->increment('total_ptc_completed');
            //if there is an upline of the user, credit his respective referral commission
            if ($worker->upline !=0 && Setting::where('name', 'ptc_referral_status')->value('value') == 'enabled') {
                $upline = User::find($worker->upline);
                $uplineCommission = ($ad->reward_per_view/100) * Setting::where('name', 'ptc_referral_commission')->value('value');
                $upline->addWorkerBalance($uplineCommission);
                $upline->increment('earned_from_referrals', $uplineCommission);
                Log::create([
                    'user_id' => $worker->upline,
                    'description' => 'received Referral commission from '.$worker->username.' for completing a task'
                ]);
            }
            return redirect($ad->url);
        // }
        // else{
        //     return redirect(url('/views/iframe'))->with('error', 'Invalid Captcha');
        // }
    }




    public function windowSubmit(Request $request)
    {
        $unique_id = $request->input('id');
        $adId = PtcAd::where('unique_id', $unique_id)->value('id');
        $ad = PtcAd::find($adId);
        $viewsNeeded = $ad->views_needed;
        $viewsCompleted = $ad->views_completed;
        $employer = User::find($ad->employer_id);
        $worker = User::find(Auth::user()->id);
        // $validate = Validator::make($request->all(), [
        //     'h-captcha-response' => 'required|captcha'
        // ]);
        // $data = array(
        //     'secret' => Setting::where('name', 'hcaptcha_secret')->value('value'),
        //     'response' => $request->input('h-captcha-response'),
        // );
        // $verify = curl_init();
        // curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        // curl_setopt($verify, CURLOPT_POST, true);
        // curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($verify);
        // // var_dump($response);
        // $responseData = json_decode($response);
        // if($responseData->success) {
            //check if the user has already claimed the max allowed times of this link within 24hrs
            $claimsCount = PtcLog::where('worker_id', Auth::user()->id)->where('created_at', '>', now()->subHours($ad->revision_interval))->where('ad_id', $adId)->count();
            if ($claimsCount > 0) {
                CheatLog::create([
                    'user_id' => Auth::user()->id,
                    'description' => 'trying to double view same ptc ad '.$adId.' before required time passes',
                ]);
                return back()->with('error', 'You have already watched this ad within allowed timeframe');
            }

            //check for user ip address quality for vpn or proxies

            //check if the required views are already completed
            if($viewsCompleted >= $viewsNeeded){
                return back()->with('error', 'The Ad is not active');
            }

            PtcLog::create([
                'worker_id' => Auth::user()->id,
                'ad_id' => $adId,
                'reward' => $ad->reward_per_view,
                'ip' => request()->ip(),
            ]);
            $ad->increment('views_completed');
            $ad->decrement('ad_balance', $ad->reward_per_view);
            $worker->addWorkerBalance($ad->reward_per_view);
            $worker->increment('total_earned', $ad->reward_per_view);
            $worker->increment('earned_from_ptc' , $ad->reward_per_view);
            $worker->increment('total_ptc_completed');
            //if there is an upline of the user, credit his respective referral commission
            if ($worker->upline !=0 && Setting::where('name', 'ptc_referral_status')->value('value') == 'enabled') {
                $upline = User::find($worker->upline);
                $uplineCommission = ($ad->reward_per_view/100) * Setting::where('name', 'ptc_referral_commission')->value('value');
                $upline->addWorkerBalance($uplineCommission);
                $upline->increment('earned_from_referrals', $uplineCommission);
                Log::create([
                    'user_id' => $worker->upline,
                    'description' => 'received Referral commission from '.$worker->username.' for completing a task'
                ]);
            }
            header( "refresh:2; url=".url('/views/window') ); 
            // return redirect(url('/views/window'))->with('success', $ad->reward_per_view.' added to your balance successfully')->withDelay(2);
        // }else{
        //     return back()->with('error', 'Invalid Captcha');
        // }
    }
}
