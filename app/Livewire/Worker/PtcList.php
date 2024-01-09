<?php

namespace App\Livewire\Worker;
use App\Models\PtcAd;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\PtcLog;
use Livewire\Component;

class PtcList extends Component
{
    public $availableIframePtcAds;
    public $availableWindowPtcAds;
    public $timerRunning = false;
    public $timeLeft = 60;
    public $windowAdInProgress = false;
    public $adUniqueId;
    
    public function mount()
    {
        $this->availableIframePtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', Auth::user()->country)
        ->where('status', 1)
        ->where('type', 0)
        ->get();

        $this->availableWindowPtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', Auth::user()->country)
        ->where('status', 1)
        ->where('type', 1)
        ->get();

        foreach ($this->availableIframePtcAds as $ad) {
            $lastClaim = PtcLog::where('worker_id', Auth::user()->id)->where('ad_id', $ad->id)->latest()->first();
            if ($lastClaim) {
                $createdAt = Carbon::parse( $lastClaim->created_at);
                // Calculate the time difference in hours and minutes
                $timeDifference = now()->diff($createdAt);
                $remainingHours = $ad->revision_interval - $timeDifference->h;
                $remainingMinutes = 60 - $timeDifference->i;
                // Store the remaining time in a variable
                $remainingTime = $remainingHours . ' hours ' . $remainingMinutes . ' minutes';

                // You can now use $remainingTime as needed, for example, store it in the database
                $ad->remaining_hours = $remainingHours;
                $ad->remaining_time = $remainingTime;

            }
        }

        foreach ($this->availableWindowPtcAds as $ad) {
            $lastClaim = PtcLog::where('worker_id', Auth::user()->id)->where('ad_id', $ad->id)->latest()->first();
            if ($lastClaim) {
                $createdAt = Carbon::parse( $lastClaim->created_at);
                // Calculate the time difference in hours and minutes
                $timeDifference = now()->diff($createdAt);
                $remainingHours = $ad->revision_interval - $timeDifference->h;
                $remainingMinutes = 60 - $timeDifference->i;
                // Store the remaining time in a variable
                $remainingTime = $remainingHours . ' hours ' . $remainingMinutes . ' minutes';

                // You can now use $remainingTime as needed, for example, store it in the database
                $ad->remaining_hours = $remainingHours;
                $ad->remaining_time = $remainingTime;

            }
        }


    }


    public function startWindowAd($adId)
    {
        $this->windowAdInProgress = true;
        $this->adUniqueId = $adId;
    }

    public function verifyAndUpdate(){
        dd($this->all());
    }


    public function render()
    {
        return view('livewire.worker.ptc-list');
    }
}
