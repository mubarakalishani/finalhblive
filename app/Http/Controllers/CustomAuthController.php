<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

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
        $usernameExists = User::where('username', $username)->first();
        //if username exists, add a 4 char random string next to it
        if($usernameExists){
            $username = $username.rand(1000,9999);
        }
    
        if ($user) {
            Auth::login($user);
        } else {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->email_verified_at = now()->format('Y-m-d H:i:s');
            $user->unique_user_id =  bin2hex(random_bytes(7));
            $user->username = $username;
            $user->password = Hash::make(bin2hex(random_bytes(6)));

            $user->save();
    
            Auth::login($user);
        }
    
        return redirect('/dashboard'); // Redirect to your dashboard or another route
    }
    
}
