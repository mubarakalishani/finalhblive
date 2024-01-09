<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectApprovalReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'submitted_proof_id', 'selected_reason', 'employer_comment'
    ];

    public function proof(){
        return $this->belongsTo(SubmittedTaskProof::class, 'submitted_proof_id', 'id');
    }
}
