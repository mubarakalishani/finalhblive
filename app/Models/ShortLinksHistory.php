<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLinksHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'link_id', 'reward', 'ip_address'
    ];
}
