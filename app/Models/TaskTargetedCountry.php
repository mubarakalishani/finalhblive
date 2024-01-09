<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTargetedCountry extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'country', 'amount_per_task'];

    public function targetedCountries()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
