<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\OffersAndSurveysLog;
use App\Models\Offerwall;
use App\Models\PayoutGateway;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect(url('/dashboard'));
        }
        $faqs = Faq::where('s_no', '>', 0)->orderBy('s_no', 'ASC')->get();
        $withdrawals = WithdrawalHistory::where('status', 1)->orderBy('updated_at', 'DESC')->take(5)->get();
        $offerwalls = Offerwall::orderBy('order', 'ASC')
        ->where('status', 1)
        ->get();
        $geteways = PayoutGateway::where('status', 1)->get();
        $offersLogs = OffersAndSurveysLog::where('reward', '>', 0.05)
        ->orderBy('id', 'DESC')
        ->take(5)->get();


        foreach ($offersLogs as $log) {
            $log->provider_image = Offerwall::where('name', $log->provider_name)->value('image_url');
        }

        foreach ($withdrawals as $withdraw) {
            $withdraw->image = "/uploads/".PayoutGateway::where('name', $withdraw->method)->value('image_path');
        }
        return view('home', [
            'faqs' => $faqs,
            'withdrawals' => $withdrawals,
            'offerwalls' => $offerwalls,
            'gateways' => $geteways,
            'offersLogs' => $offersLogs
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
