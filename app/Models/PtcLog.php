<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtcLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'worker_id', 'ad_id', 'reward', 'ip'
    ];

    public function worker(){
       return $this->belongsTo(User::class, 'worker_id', 'id');
    }
    public function ptcAd(){
       return $this->belongsTo(PtcAd::class, 'ad_id', 'id');
    }
}
