<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use App\Models\PtcAd;
use App\Models\PtcLog;
use App\Models\Task;
use App\Models\User;
use App\View\Composers\ProfileComposer;
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Facades\View::composer('layouts.afterlogin', function (View $view) {
            $availableIframePtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', auth()->user()->country)
            ->where('status', 1)
            ->where('type', 0)
            ->where('ad_balance', '>', 0)
            ->orderBy('reward_per_view', 'desc')
            ->get();

            foreach ($availableIframePtcAds as $ad) {
                $lastClaim = PtcLog::where('worker_id', auth()->user()->id)->where('ad_id', $ad->id)->latest()->first();
                if ($lastClaim) {
                    $createdAt = Carbon::parse( $lastClaim->created_at);
                    // Calculate the time difference in hours and minutes
                    $timeDifference = now()->diff($createdAt);
                    // Calculate the total time difference in seconds
                    $totalSecondsDifference = $timeDifference->days * 24 * 60 * 60 + $timeDifference->h * 60 * 60 + $timeDifference->i * 60 + $timeDifference->s;
                    $ad->totalSecondsDifference = $totalSecondsDifference;

                }
            }

            $countIframe = 0;
            foreach ($availableIframePtcAds as $IframeAd) {
                if (!$IframeAd->totalSecondsDifference || $IframeAd->totalSecondsDifference > ($IframeAd->revision_interval * 60 * 60)) {
                    $countIframe++;
                }
            }

            $availableWindowPtcAds = PtcAd::whereJsonDoesntContain('excluded_countries', auth()->user()->country)
            ->where('status', 1)
            ->where('type', 1)
            ->where('ad_balance', '>', 0)
            ->orderBy('reward_per_view', 'desc') 
            ->get();

            foreach ($availableWindowPtcAds as $ad) {
                $lastClaim = PtcLog::where('worker_id', auth()->user()->id)->where('ad_id', $ad->id)->latest()->first();
                if ($lastClaim) {
                    $createdAt = Carbon::parse( $lastClaim->created_at);
                    // Calculate the time difference in hours and minutes
                    $timeDifference = now()->diff($createdAt);
                    // Calculate the total time difference in seconds
                    $totalSecondsDifference = $timeDifference->days * 24 * 60 * 60 + $timeDifference->h * 60 * 60 + $timeDifference->i * 60 + $timeDifference->s;
                    $ad->totalSecondsDifference = $totalSecondsDifference;

                }
            }

            $countWindows = 0;
            foreach ($availableWindowPtcAds as $windowAd) {
                if (!$windowAd->totalSecondsDifference || $windowAd->totalSecondsDifference > ($windowAd->revision_interval * 60 * 60)) {
                    $countWindows++;
                }
            }

            $userCountry = auth()->user()->country;
            $availableTasks = Task::with(['targetedCountries' => function ($query) use ($userCountry) {
                            $query->where('country', $userCountry);
                        }])
                        ->whereDoesntHave('submittedProofs', function ($query) {
                            $query->where('worker_id', auth()->user()->id);
                        })
                        ->where('status', 1)
                        ->whereHas('employer', function ($query) {
                            $query->where('deposit_balance', '>', 0);
                        })
                        // Filter the tasks by the user's country and the amount_per_task column
                        ->whereHas('targetedCountries', function ($query) use ($userCountry) {
                            $query->where('country', $userCountry);
                        })->get();
            $countTasks = 0;
            foreach ($availableTasks as $availableTask) {
                $employer = User::find($availableTask->employer_id);
                if ($availableTask->targetedCountries->first()->amount_per_task < $employer->deposit_balance) {
                    $countTasks++;
                }
            }            

            $sidebarData = [
                'availablePtcAds' => $countIframe + $countWindows,
                'countTasks' => $countTasks,
            ];

            $view->with('sidebarData', $sidebarData);
        });
    }
}
