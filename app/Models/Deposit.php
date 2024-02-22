<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'method', 'amount', 'status', 'internal_tx', 'description', 'external_tx'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
