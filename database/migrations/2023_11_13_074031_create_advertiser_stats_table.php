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
        Schema::create('advertiser_stats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->unique();
            $table->integer('tasks_created')->unsigned()->nullable()->default(0);
            $table->integer('ptc_ads_created')->unsigned()->nullable()->default(0);
            $table->integer('no_of_tasks_paid')->unsigned()->nullable()->default(0);
            $table->integer('no_of_ptc_paid')->unsigned()->nullable()->default(0);
            $table->float('tasks_reward_paid')->nullable()->default(0.00);
            $table->float('ptc_reward_paid')->nullable()->default(0.00);
            $table->float('total_spend')->nullable()->default(0.00);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertiser_stats');
    }
};
