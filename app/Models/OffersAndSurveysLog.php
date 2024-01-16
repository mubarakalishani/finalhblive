<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffersAndSurveysLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'provider_name', 'payout', 'reward', 'added_expert_level', 'upline_commision', 'transaction_id', 
        'offer_id', 'offer_name', 'hold_time', 'instant_credit', 'ip_address', 'status'
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeSearch($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->where('provider_name', 'like', "%{$value}%");
            
        });
    }
}
