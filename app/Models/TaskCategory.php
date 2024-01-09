<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'min_reward'];

    public function children()
    {
        return $this->hasMany(TaskCategory::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(TaskCategory::class, 'parent_id', 'id');
    }

    public function rewards()
    {
        return $this->hasMany(CategoryReward::class, 'task_category_id');
    }
}
