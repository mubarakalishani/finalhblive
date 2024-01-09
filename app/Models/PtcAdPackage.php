<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtcAdPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'reward_per_view', 'minimum_views', 'seconds'
    ];
}
