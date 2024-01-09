<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryReward extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_category_id', 'country_id', 'country_name', 'min_reward_amount'
    ];
    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }
}
