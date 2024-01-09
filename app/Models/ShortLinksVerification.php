<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLinksVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'link_id', 'url', 'ip_address', 'secret_keys'
    ];
}
