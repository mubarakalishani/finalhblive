<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\AvailableCountry;
use App\Models\AdvertiserStat;
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
            $response = $client->get('https://ipinfo.io/' . request()->ip() . '/country');
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
