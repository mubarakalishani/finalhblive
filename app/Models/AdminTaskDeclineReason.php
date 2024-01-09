<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminTaskDeclineReason extends Model
{
    protected $table = 'admin_task_decline_reasons';
    protected $fillable = [
        'task_id', 'reason'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
