<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;
    protected $fillable = [
        'tasks_total_earned', 'tasks_today_earned', 'tasks_this_month', 'tasks_last_month',
        'offers_total_earned', 'offers_today_earned', 'offers_this_month', 'offers_last_month',
        'shortlinks_total_earned', 'shortlinks_today_earned', 'shortlinks_this_month', 'shortlinks_last_month',
        'ptc_total_earned', 'ptc_today_earned', 'ptc_this_month', 'ptc_last_month',
        'faucet_total_earned', 'faucet_today_earned', 'faucet_this_month', 'faucet_last_month'
    ];
}
