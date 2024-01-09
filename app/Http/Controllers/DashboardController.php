<?php

namespace App\Http\Controllers;

use App\Models\Offerwall;
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

        return view('dashboard', [
            'offerwalls' => $offerwalls
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
