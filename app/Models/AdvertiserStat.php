<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertiserStat extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'tasks_created', 'ptc_ads_created', 'no_of_tasks_paid', 'no_of_ptc_paid', 'tasks_reward_paid', 'ptc_reward_paid', 'total_spend',
    ];

    public function advertiserStats()
    {
        return $this->hasOne(AdvertiserStat::class);
    }
}
