<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'method', 'wallet', 'amount_no_fee', 'amount_after_fee', 'fee', 'status', 'description'
    ];

    public function user(){
        $this->belongsTo(User::class, 'user_id', 'id');
    }
}
