<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaskStep;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = ['employer_id', 'worker_level', 'task_balance', 'category',
     'sub_category', 'approval_fee', 'rating_time', 'hold_time',
     'max_budget', 'daily_budget', 'weekly_budget', 'hourly_budget','maximum_submissions', 'submission_per_hour', 'submission_per_day', 'submission_per_week', 'status', 'remarks'
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id', 'id');
    }

    public function taskCategory()
    {
        return $this->belongsTo(TaskCategory::class, 'category', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(TaskCategory::class, 'sub_category', 'id');
    }

    public function declineReason()
    {
        return $this->hasOne(AdminTaskDeclineReason::class);
    }

    public function stepDetails()
    {
        return $this->hasMany(TaskStep::class);
    }

    public function targetedCountries()
    {
        return $this->hasMany(TaskTargetedCountry::class);
    }

    public function excludedCountries()
    {
        return $this->hasMany(TaskExcludedCountry::class);
    }

    public function requiredProofs()
    {
        return $this->hasMany(TaskRequiredProof::class);
    }

    public function submittedProofs()
    {
        return $this->hasMany(SubmittedTaskProof::class);
    }
}
