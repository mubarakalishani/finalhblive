<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offerwall extends Model
{
    use HasFactory;
    protected $fillable = [
        'order', 'name', 'status', 'iframe_url', 'iframe_styles', 'iframe_extra_elements', 'is_target_blank', 'image_url', 'image_styles'
    ];
}
