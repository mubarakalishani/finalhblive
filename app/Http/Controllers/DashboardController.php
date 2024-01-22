<?php

namespace App\Http\Controllers;

use App\Models\Offerwall;
use App\Models\PtcAd;
use App\Models\PtcLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offerwalls = Offerwall::where('status', 1)->get();
        foreach ($offerwalls as $offerwall) {
           $iframeUrl = $offerwall->iframe_url;
           $url = str_replace('[userid]', auth()->user()->unique_user_id, $iframeUrl);
           $offerwall->url = $url;
        }



        $availableIframePtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', auth()->user()->country)
        ->where('status', 1)
        ->where('type', 0)
        ->orderBy('reward_per_view', 'desc')
        ->get();

        foreach ($availableIframePtcAds as $ad) {
            $lastClaim = PtcLog::where('worker_id', auth()->user()->id)->where('ad_id', $ad->id)->latest()->first();
            if ($lastClaim) {
                $createdAt = Carbon::parse( $lastClaim->created_at);
                // Calculate the time difference in hours and minutes
                $timeDifference = now()->diff($createdAt);
                // Calculate the total time difference in minutes
                $totalMinutesDifference = $timeDifference->days * 24 * 60 + $timeDifference->h * 60 + $timeDifference->i;
                $remainingHours = $ad->revision_interval - $timeDifference->h;
                $remainingMinutes = 60 - $timeDifference->i;
                // Store the remaining time in a variable
                $remainingTime = $remainingHours . ' hours ' . $remainingMinutes . ' minutes';

                // You can now use $remainingTime as needed, for example, store it in the database
                $ad->remaining_hours = $remainingHours;
                $ad->remaining_time = $remainingTime;
                $ad->totalMinutesDifference = $totalMinutesDifference;

            }
        }

        return view('dashboard', [
            'offerwalls' => $offerwalls,
            'availableIframePtcAds' => $availableIframePtcAds
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
        //
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
