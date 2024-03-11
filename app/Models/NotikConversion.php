<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotikConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'click_id', 'campaign_id', 'campaign_name', 'traffic_source', 'user_country_code', 'remarks', 'user_ip'
    ];
}
