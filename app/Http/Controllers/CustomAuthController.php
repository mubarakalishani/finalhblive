<?php

namespace App\Http\Controllers;

use App\Models\AvailableCountry;
use App\Models\NotikConversion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CustomAuthController extends Controller
{
    public function handleGoogleCallback()
    {
        $data = Socialite::driver('google')->user();
        
    
        // Check if the user with this email already exists in your database
        // If not, create a new user record
    
        $user = User::where('email', $data->email)->first();
        $nameParts = explode(' ', $data->name);
        $username = implode('', $nameParts);
        $username = strtolower($username);
        $usernameExists = User::where('username', $username)->first();
        //if username exists, add a 4 char random string next to it
        while ($usernameExists) {
            $username = $username.rand(100,999);
            $usernameExists = User::where('username', $username)->exists();
        }


        if (Session::has('click_id') && Session::has('oid')) {
            // Retrieve the values from the session
            $click_id = Session::get('click_id');
            $country_code = Session::get('country_code');
            $oid = Session::get('oid');
            $oname = Session::get('oname');
            $source = Session::get('source');
            $click_ip = Session::get('click_ip');
    
            $ipNotikExists = User::where('signup_ip', $click_ip)->orWhere('last_ip', $click_ip)->exists();
    
            if ($ipNotikExists) {
                $remarks = 'ip address already found in either last_ip or signup_ip of any existing member';
            } else {
                $remarks = 'normal';
            }

            NotikConversion::create([
                "username" => $username,
                "click_id" => $click_id,
                "campaign_id" => $oid,
                "campaign_name" => $oname,
                "traffic_source" => $source,
                "user_ip" => $click_ip,
                "remarks" => $remarks,
                "user_country_code" => $country_code,
                "days" => 0,
                "status" => 0
            ]);
    
            // Unset the session variables
            Session::forget(['click_id', 'oid', 'oname', 'source', 'click_ip', 'country_code']);
        }



    
        if ($user) {
            Auth::login($user);
        } else {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->email_verified_at = now()->format('Y-m-d H:i:s');
            $uniqueId = bin2hex(random_bytes(6));
            $uniqueIdExists = User::where('unique_user_id', $uniqueId)->exists();
            while ($uniqueIdExists) {
                $uniqueId = bin2hex(random_bytes(6));
                $uniqueId = User::where('unique_user_id', $uniqueId)->exists();
            }
            $user->unique_user_id =  $uniqueId;
            $user->secret_key = bin2hex(random_bytes(32));
            $user->signup_ip = request()->ip();
            $user->last_ip = request()->ip();
            $user->country = $this->getCountryCode();
            $user->upline = $this->getUplineId();
            $user->username = $username;
            $user->password = Hash::make(bin2hex(random_bytes(6)));
            $user->utm_source = $this->getTrafficSource();

            $user->save();
    
            Auth::login($user);
        }
    
        return redirect('/dashboard'); // Redirect to your dashboard or another route
    }


    protected function getCountryCode()
    {
        $client = new Client();
    
        try {
            $response = $client->get('https://ipinfo.io/' . request()->ip() . '/country?token=31864a8810b4cb');
            $countryCode = trim(strtoupper($response->getBody()->getContents()));
    
            // Fetch country name from the database
            $country = AvailableCountry::where('country_code', $countryCode)->value('country_name');
    
            return $country;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Log or handle the exception
            dump('Error fetching country code:', $e->getMessage());
            return null; // Return null or handle the error appropriately
        }
    }

    protected function getUplineId(){
        // Retrieve referral code from the session
        $referralCode = Session::get('upline_id');
        $uplineExist = User::where('id', $referralCode)->exists();
        if($uplineExist){
            $upline = User::find($referralCode);
            $upline->increment('referrals');
        }

        // Clear the referral code from the session (optional)
        Session::forget('upline_id');
        if ($uplineExist) {
            return $referralCode;
        }
        else{
            return 0;
        }

    }


    protected function getTrafficSource(){
        // Retrieve referral code from the session
        $trafficSource = Session::get('traffic_source');
        return $trafficSource;

    }


    
}
