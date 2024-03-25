<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\AvailableCountry;
use App\Models\AdvertiserStat;
use App\Models\NotikConversion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'alpha_dash:ascii', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();


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
                $remarks = 'ip found in either last_ip or signup_ip';
            } else {
                $remarks = 'normal';
            }

            NotikConversion::create([
                "username" => $input['username'],
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

            
        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'username' => $input['username'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'unique_user_id' =>  bin2hex(random_bytes(6)),
                'secret_key' => bin2hex(random_bytes(32)),
                'signup_ip' => request()->ip(),
                'last_ip' => request()->ip(),
                'country' => $this->getCountryCode(),
                'upline' => $this->getUplineId(),
                'utm_source' => $this->getTrafficSource(),
            ]), function (User $user) {
                $this->createTeam($user);
            });
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
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
