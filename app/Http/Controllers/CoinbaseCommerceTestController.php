<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// use CoinbaseCommerce\ApiClient;

class CoinbaseCommerceTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function charge()
    {
        // ApiClient::init('5a917e55-0ca0-42ae-a543-67fa9dbd8c28');
       
        // $charge = Charge::create([
        //     'name' => 'Sample Charge',
        //     'description' => 'Sample description',
        //     'local_price' => [
        //         'amount' => 100,
        //         'currency' => 'USD',
        //     ],
        //     'pricing_type' => 'fixed_price',
        //     'metadata' => [
        //         'customer_id' => auth()->user()->id,
        //     ],
        // ]);
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://api.commerce.coinbase.com/charges',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => '{
        //         "cancel_url": "https://example.com/cancel",
        //         "checkout_id": "ABC123",
        //         "local_price": {
        //             "amount": "10",
        //             "currency": "USD"
        //         },
        //         "redirect_url": "https://handbucks.com",
        //         "pricing_type": "fixed_price"
        //     }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
        //         'Accept: application/json',
        //         'X-CC-Api-Key: 5a917e55-0ca0-42ae-a543-67fa9dbd8c28',
        //         'X-CC-Version: 2018-03-22',
        //     ),
        // ));
        
        // $result = curl_exec($curl);
        
        // curl_close($curl);

        // $response = json_decode($result);
        // return $response->data->hosted_url;




        $curl = curl_init();
$postFilds=array(
    'pricing_type'=>'no_price',
    'metadata'=>array('customer_id'=>10)
);
$postFilds=urldecode(http_build_query($postFilds));
curl_setopt_array($curl, 
    array(
        CURLOPT_URL => "https://api.commerce.coinbase.com/charges",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postFilds,
        CURLOPT_HTTPHEADER => array(
            "X-CC-Api-Key: 5a917e55-0ca0-42ae-a543-67fa9dbd8c28",
            "X-CC-Version: 2018-03-22",
            "content-type: multipart/form-data"
        ),
    )
);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

echo $response;

        
        
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
