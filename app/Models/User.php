<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'username', 'unique_user_id', 'email', 'secret_key', 'password', 'level', 'referrals', 'utm_source', 'signup_ip', 'last_ip', 'upline', 'country',
        'balance', 'deposit_balance', 'bonus_balance', 'diamond_level_balance', 'instant_withdrawable_balance', 'total_earned', 'earned_from_referrals',
        'earned_from_offers', 'earned_from_tasks', 'earned_from_surveys', 'earned_from_ptc', 'earned_from_faucet', 'earned_from_shortlinks',
        'total_tasks_completed', 'total_offers_completed', 'total_ptc_completed', 'total_surveys_completed', 'total_faucet_completed', 
        'total_shortlinks_completed', 'status', 'kyc_status', 
    ];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function advertiserStat()
    {
        return $this->hasMany(AdvertiserStat::class);
    }


    public function offerwallLogs()
    {
        return $this->hasMany(OffersAndSurveysLog::class, 'user_id');
    }


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addWorkerBalance($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    public function deductWorkerBalance($amount)
    {
        $this->balance -= $amount;
        $this->save();
    }

    public function addAdvertiserBalance($amount)
    {
        $this->deposit_balance += $amount;
        $this->save();
    }

    public function deductAdvertiserBalance($amount)
    {
        $this->deposit_balance -= $amount;
        $this->save();
    }

    public function scopeSearch($query, $value)
    {
         $query->where('username', 'like', "%{$value}%")
         ->orWhere('country', 'like', "%{$value}%")
         ->orWhere('name', 'like', "%{$value}%");
    }



}
