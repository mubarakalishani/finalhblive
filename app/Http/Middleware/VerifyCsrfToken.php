<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/faucetpay/callback',
        '/webhook/coinbase',
        '/webhook/perfectmoney',
        '/postback/offers4crypto',
        '/postback/bitcotasks',
        '/postback/revlum'
    ];
}
