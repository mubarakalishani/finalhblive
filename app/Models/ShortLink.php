<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'url', 'unique_id', 'reward', 'views_per_day', 'min_seconds'
    ]; 

    // protected static function booted()
    // {
    //     static::creating(function ($user) {
    //         $user->unique_id = bin2hex(random_bytes(16));
    //     });
    // }
}
