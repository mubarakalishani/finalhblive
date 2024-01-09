<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;

class CoinbaseCommerceTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ApiClient::init('5a917e55-0ca0-42ae-a543-67fa9dbd8c28');
       
        $charge = Charge::create([
            'name' => 'Sample Charge',
            'description' => 'Sample description',
            'local_price' => [
                'amount' => 100,
                'currency' => 'USD',
            ],
            'pricing_type' => 'fixed_price',
            'metadata' => [
                'customer_id' => auth()->user()->id,
            ],
        ]);

        return redirect()->to($charge['hosted_url']);
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
