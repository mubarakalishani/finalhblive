<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRequiredProof extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'proof_no', 'proof_type', 'proof_text'];
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
