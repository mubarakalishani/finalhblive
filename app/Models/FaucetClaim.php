<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaucetClaim extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'claimed_amount'
    ];

    public function worker(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
