<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStep extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'step_no', 'step_details'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
