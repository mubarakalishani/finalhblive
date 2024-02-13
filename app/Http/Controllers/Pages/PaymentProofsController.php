<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\PayoutGateway;
use App\Models\WithdrawalHistory;
use Illuminate\Http\Request;

class PaymentProofsController extends Controller
{
    public function index()
    {
        $withdrawals = WithdrawalHistory::where('status', 1)->orderBy('updated_at', 'DESC')->take(100)->get();
        foreach ($withdrawals as $withdraw) {
            $withdraw->image = "/uploads/".PayoutGateway::where('name', $withdraw->method)->value('image_path');
        }
        return view('pages.payment-proofs', [
            'withdrawals' => $withdrawals
        ]);
    }
}
