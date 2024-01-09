<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedTaskProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'worker_id', 'amount', 'status'
    ];

    public function imageProofs()
    {
        return $this->hasMany(ImageProof::class, 'submitted_proof_id');
    }

    public function textProofs()
    {
        return $this->hasMany(TextProof::class , 'submitted_proof_id');
    }

    public function revisionApprovalReason()
    {
        return $this->hasOne(RejectApprovalReason::class , 'submitted_proof_id');
    }

    public function dispute()
    {
        return $this->hasOne(TaskDispute::class , 'proof_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function scopeSearch($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->WhereHas('task', function ($query) use ($value) {
                      $query->where('title', 'like', "%{$value}%");
                  });
        });
    }

    public function scopeByEmployer($query, $employerId)
    {
        return $query->whereHas('task', function ($query) use ($employerId) {
            $query->where('employer_id', $employerId);
        });
    }
}
