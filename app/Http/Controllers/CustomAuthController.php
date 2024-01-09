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
        dd($user);
    
        // Check if the user with this email already exists in your database
        // If not, create a new user record
    
        // $existingUser = User::where('email', $user->email)->first();
    
        // if ($existingUser) {
        //     Auth::login($existingUser);
        // } else {
        //     $newUser = new User;
        //     $newUser->email 
        //     $newUser->username 
        //     $newUser->password = Hash::make(bin2hex(random_bytes(6)));
    
        //     Auth::login($newUser);
        // }
    
        return redirect()->route('dashboard'); // Redirect to your dashboard or another route
    }
    
}
