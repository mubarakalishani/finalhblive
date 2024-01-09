<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('unique_user_id')->unique();
            $table->string('email')->unique();
            $table->string('secret_key')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('level')->unsigned()->default(0);  //0 = starter, 1 = advanced, 2 = expert
            $table->integer('referrals')->default(0);
            $table->string('utm_source')->default('direct');
            $table->ipAddress('signup_ip');
            $table->ipAddress('last_ip');
            $table->tinyInteger('upline')->default(0);
            $table->string('country');
            $table->decimal('balance', 10, 4)->default(0.00);
            $table->decimal('deposit_balance', 10, 4)->default(0.00);
            $table->decimal('bonus_balance', 10, 4)->default(0.00);
            $table->decimal('diamond_level_balance', 10, 4)->default(0.00);
            $table->decimal('instant_withdrawable_balance', 10, 4)->default(0.00);
            $table->decimal('total_earned', 10, 4)->default(0.00);
            $table->decimal('earned_from_referrals', 10, 4)->default(0.00);
            $table->decimal('earned_from_offers', 10, 4)->default(0.00);
            $table->decimal('earned_from_tasks', 10, 4)->default(0.00);
            $table->decimal('earned_from_surveys', 10, 4)->default(0.00);
            $table->decimal('earned_from_ptc', 10, 4)->default(0.00);
            $table->decimal('earned_from_faucet', 10, 4)->default(0.00);
            $table->decimal('earned_from_shortlinks', 10, 4)->default(0.00);
            $table->integer('total_tasks_completed')->default(0);
            $table->integer('total_offers_completed')->default(0);
            $table->integer('total_ptc_completed')->default(0);
            $table->integer('total_surveys_completed')->default(0);
            $table->integer('total_faucet_completed')->default(0);
            $table->integer('total_shortlinks_completed')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('kyc_status')->default(0);
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
