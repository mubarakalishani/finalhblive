<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'min_deposit', 'status', 'auto', 'description',
    ];
}
