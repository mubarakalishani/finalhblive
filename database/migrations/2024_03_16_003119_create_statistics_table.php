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
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->decimal('tasks_total_earned', 15, 5)->default(0);
            $table->decimal('tasks_today_earned', 15, 5)->default(0);
            $table->decimal('tasks_this_month', 15, 5)->default(0);
            $table->decimal('tasks_last_month', 15, 5)->default(0);
            $table->decimal('offers_total_earned', 15, 5)->default(0);
            $table->decimal('offers_today_earned', 15, 5)->default(0);
            $table->decimal('offers_this_month', 15, 5)->default(0);
            $table->decimal('offers_last_month', 15, 5)->default(0);
            $table->decimal('shortlinks_total_earned', 15, 5)->default(0);
            $table->decimal('shortlinks_today_earned', 15, 5)->default(0);
            $table->decimal('shortlinks_this_month', 15, 5)->default(0);
            $table->decimal('shortlinks_last_month', 15, 5)->default(0);
            $table->decimal('ptc_total_earned', 15, 5)->default(0);
            $table->decimal('ptc_today_earned', 15, 5)->default(0);
            $table->decimal('ptc_this_month', 15, 5)->default(0);
            $table->decimal('ptc_last_month', 15, 5)->default(0);
            $table->decimal('faucet_total_earned', 15, 5)->default(0);
            $table->decimal('faucet_today_earned', 15, 5)->default(0);
            $table->decimal('faucet_this_month', 15, 5)->default(0);
            $table->decimal('faucet_last_month', 15, 5)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
