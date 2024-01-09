<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtcAd extends Model
{
    use HasFactory;
    protected $fillable = [
        'employer_id', 'unique_id', 'ad_balance', 'temp_locked_balance', 'reward_per_view', 'views_needed', 'views_completed', 
        'title', 'description', 'url', 'targeted_countries', 'excluded_countries', 'status', 'type'
    ];

    public function employer(){
        return $this->belongsTo(User::class, 'employer_id', 'id');
    }
    public function logs(){
        return $this->hasMany(PtcLog::class, 'ad_id');
    }

    public function scopeSearch($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->where('title', 'like', "%{$value}%")
            ->orWhere('description', 'like', "%{$value}%")
            ->orWhere('url', 'like', "%{$value}%");
        });
    }
}
