<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class ReferralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the referral code is present in the query string (?ref=abc) or URL path (/ref/abc)
        $referralCode = $request->query('ref') ?? last($request->segments());

        // Store the referral code in the session
        if ($referralCode) {
            $uplineExists = User::where('username', $referralCode)->exists();
            if ($uplineExists) {
                Session::put('upline_id', User::where('username', $referralCode)->value('id'));
            }
        }

        return $next($request);
    }
}
