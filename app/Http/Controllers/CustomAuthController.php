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
        $user = Socialite::driver('google')->user();
        
    
        // Check if the user with this email already exists in your database
        // If not, create a new user record
    
        $existingUser = User::where('email', $user->email)->first();
        $nameParts = explode(' ', $user->name);
        $username = implode('', $nameParts);
    
        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            $newUser = new User;
            $newUser->email = $user->email;
            $newUser->email_verified_at = now()->format('Y-m-d H:i:s');
            $newUser->unique_user_id =  bin2hex(random_bytes(7));
            $newUser->username = $username;
            $newUser->password = Hash::make(bin2hex(random_bytes(6)));

            $newUser->save;
    
            Auth::login($newUser);
        }
    
        return redirect()->route('dashboard'); // Redirect to your dashboard or another route
    }
    
}
