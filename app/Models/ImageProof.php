<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageProof extends Model
{
    use HasFactory;
    protected $fillable = [
        'submitted_proof_id', 'proof_no', 'url', 'comment'
    ];
}
