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
        $source = $request->header('utm_source');

        Session::put('utm_source', $source);

        // Store the referral code in the session
        if ($referralCode) {
            $uplineExists = User::where('username', $referralCode)->exists();
            if ($uplineExists) {
                Session::put('upline_id', User::where('username', $referralCode)->value('id'));
            }
        }



        $trafficSource = 'direct';

        // Check if there is a Referer header
        if ($request->headers->has('referer')) {
            $trafficSource = $request->headers->get('referer');
        } else {
            // Check if UTM parameters are present in the URL
            $utmSource = $request->input('utm_source');
        
            // Check if at least one UTM parameter is present
            if ($utmSource) {
                $trafficSource = $utmSource;
            }
        }
        
        // If neither Referer header nor UTM parameters are present, default to "Direct Traffic"
        if (!$trafficSource) {
            $trafficSource = "Direct Traffic";
        }

        session(['traffic_source' => $trafficSource]);

        return $next($request);
    }
}
