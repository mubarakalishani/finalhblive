<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProofDispute extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'employer_id', 'task_id', 'proof_id', 'description'
    ];
}
