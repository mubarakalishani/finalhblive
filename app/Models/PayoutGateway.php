<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutGateway extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'min_payout', 'fixed_fee', 'fee_percentage', 'instant', 'status', 'image_path'
    ];
}
