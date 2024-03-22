<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\ShortLink; 
use App\Models\ShortLinksVerification;
use App\Models\User;
use App\Models\ShortLinksHistory;
use App\Models\CheatLog;
use App\Models\Statistic;

class WorkerShortLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shortLinks = ShortLink::where('status', 1)->orderBy('order', 'ASC')->get();
        foreach ($shortLinks as $shortLink) {
            $viewsCount = ShortLinksHistory::where('user_id', Auth::user()->id)
            ->where('link_id', $shortLink->id)
            ->where('created_at', '>=', Carbon::now()->subDay()) // Filter records within the last 24 hours
            ->count();

            $lastClaim = ShortLinksHistory::where('user_id', Auth::user()->id)->where('link_id', $shortLink->id)->latest()->first();
            if ($lastClaim) {
                $createdAt = Carbon::parse( $lastClaim->created_at);
                // Calculate the time difference in hours and minutes
                $timeDifference = now()->diff($createdAt);
                $totalMinutesDifference = $timeDifference->days * 24 * 60 + $timeDifference->h * 60 + $timeDifference->i;
                $remainingHours = 24 - $timeDifference->h;
                $remainingMinutes = 60 - $timeDifference->i;
                // Store the remaining time in a variable
                $remainingTime = $remainingHours . ' hours ' . $remainingMinutes . ' minutes';

                // You can now use $remainingTime as needed, for example, store it in the database
                $shortLink->remaining_time = $remainingTime; 
                $shortLink->totalMinutesDifference = $totalMinutesDifference;
            }
            $shortLink->remaining_views = $shortLink->views_per_day - $viewsCount;
        }
        return view('worker.all-shortlinks', ['shortLinks' => $shortLinks]);
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show( $uniqueId )
    {
        $url = ShortLink::where('unique_id', $uniqueId)->value('url');
        $secret_key = bin2hex(random_bytes(32));
        // $url = urlencode(site_url('/links/back/' . bin2hex(random_bytes(15)),));
		$apiUrl = str_replace('[url]', url('/shortlink/back/'.$secret_key), $url);
		$response = Http::get($apiUrl);

        if ($response->successful()) {
            $result = $response->json();
            $status = $result['status'];
            $shortenedUrl = $result['shortenedUrl'];
    
            // Handle the status and shortened
            if ($result['status'] == 'success') {
                ShortLinksVerification::create([
                  'user_id' => Auth::user()->id,
                  'link_id' => ShortLink::where('unique_id', $uniqueId)->value('id'),
                  'url' => $shortenedUrl,
                  'ip_address' => request()->ip(),
                  'secret_keys' => $secret_key,
                ]); 
                return redirect($shortenedUrl);
            }
    
            return $shortenedUrl;
        } else {
            // Handle the error response
            $errorMessage = $response->json('message');
    
            // ...
    
            // return redirect(url('/shortlinks'))->with('error', 'something went wrong try again');
            return $errorMessage;
        }
        
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
    public function verifyAndUpdate($secretKey)
    {
        //check if the secret key length is 64 or not
        if ( strlen($secretKey) != 64) {
            return redirect(url('/shortlinks'))->with('error', 'Invalid Key');
        }

        //check if the secret key exists in the verification table
        $secretKeyExists = ShortLinksVerification::where('secret_keys', $secretKey)->where('user_id', Auth::user()->id)->exists();

        if (!$secretKeyExists) {
            return redirect(url('/shortlinks'))->with('error', 'Invalid Key');
        }

        //check if the ip address is the same or not
        if ( ShortLinksVerification::where('user_id', Auth::user()->id)->where('secret_keys', $secretKey)->value('ip_address') !=  request()->ip() ) {
            return redirect(url('/shortlinks'))->with('error', 'Invalid Key');
        }

        $linkId = ShortLinksVerification::where('secret_keys', $secretKey)->value('link_id');
        $shortLink = ShortLink::find($linkId);
        $minSeconds = $shortLink->min_seconds;
        $createdAt =  ShortLinksVerification::where('secret_keys', $secretKey)->value('created_at');       
        $currentDateTime = Carbon::now();
        $secondsDifference = $currentDateTime->diffInSeconds($createdAt);

        //check if the user has already claimed the max allowed times of this link within 24hrs
        $claimsCount = ShortLinksHistory::where('user_id', Auth::user()->id)->where('created_at', '>', now()->subHours(24))->where('link_id', $linkId)->count();
        if ($claimsCount >= $shortLink->views_per_day) {
            CheatLog::create([
                'user_id' => Auth::user()->id,
                'description' => 'shortlink trying to claim more than 24h max allowed',
            ]);
            return redirect(url('/shortlinks'))->with('error', 'You have already claimed max allowed claims of this link today, try again after 24hours');
        }

        //check if the time difference is too low and add cheat log
        if ( $secondsDifference < $minSeconds ) {
            CheatLog::create([
                'user_id' => Auth::user()->id,
                'description' => 'shortlink trying to claim so early',
            ]);
            return redirect(url('/shortlinks'))->with('error', 'Something went Wrong');
        }

        //all well, add balance
        $user = User::find(Auth::user()->id);
        $amountToAdd = $shortLink->value('reward');
        $user->addWorkerBalance($amountToAdd);
        $user->increment('total_earned', $amountToAdd);
        $user->increment('earned_from_shortlinks', $amountToAdd);
        $user->increment('total_shortlinks_completed');

        //update statistics
        $statistics = Statistic::latest()->firstOrCreate([]);
        $statistics->increment('shortlinks_total_earned', $amountToAdd);
        $statistics->increment('shortlinks_today_earned', $amountToAdd);
        $statistics->increment('shortlinks_this_month', $amountToAdd);

        //create shorlink history
        ShortLinksHistory::create([
            'user_id' => Auth::user()->id,
            'link_id' => $linkId,
            'reward' => $amountToAdd,
            'ip_address' => request()->ip(),
        ]);
        ShortLinksVerification::where('secret_keys', $secretKey)->where('user_id', Auth::user()->id)->delete();
        return redirect(url('/shortlinks'))->with('success', 'Completed Shortlink Successfully, $'.$shortLink->reward. ' added to your balance');

        


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
