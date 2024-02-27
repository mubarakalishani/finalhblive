<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'worker_id', 'proof_id', 'description', 'employer_id', 'status'
    ];
    //status 0=employer unresponded yet , 1=responded

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function proof()
    {
        return $this->belongsTo(SubmittedTaskProof::class, 'proof_id', 'id');
    }
}
