<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offerwall extends Model
{
    use HasFactory;
    protected $fillable = [
        'order',
        'name',
        'status',
        'secret_key',
        'api_key',
        'whitelisted_ips',
        'starter_cp',
        'advance_cp',
        'expert_cp',
        'ref_commission',
        'tier1_hold_amount',
        'tier1_hold_time',
        'tier2_hold_amount',
        'tier2_hold_time',
        'tier3_hold_amount',
        'tier3_hold_time',
        'hold',
        'iframe_url',
        'iframe_styles',
        'iframe_extra_elements',
        'is_target_blank',
        'image_url',
        'image_styles',
    ];
}
