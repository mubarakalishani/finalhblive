<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tier2Counry extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_code', 'country_name'
    ];
}
