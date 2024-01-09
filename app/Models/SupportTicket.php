<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id', 'department_id', 'name', 'email', 'subject', 'message'
    ];

    public function department(){
        $this->belongsTo(SupportDepartment::class, 'department_id', 'id');
    }
}
