<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TaskRequiredProof;
use App\Models\TaskStep;
use App\Models\TaskTargetedCountry;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employer_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('worker_level', 20)->default('starter')->nullable();
            $table->bigInteger('category')->unsigned();
            $table->bigInteger('sub_category')->unsigned();
            $table->float('task_balance')->default(0.00);
            $table->decimal('approval_fee', 8, 4)->default(0.00);
            $table->integer('rating_time')->default(0);
            $table->integer('hold_time')->default(0);

            $table->float('max_budget', 8, 2)->default(0.00);
            $table->float('daily_budget', 8, 2)->default(0.00);
            $table->float('weekly_budget', 8, 2)->default(0.00);
            $table->float('hourly_budget', 8, 2)->default(0.00);
            $table->integer('submission_per_hour')->unsigned()->default(0);
            $table->integer('submission_per_day')->unsigned()->default(0);
            $table->integer('submission_per_week')->unsigned()->default(0);
            $table->integer('status')->unsigned()->default(0);
            
            $table->timestamps();
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category')->references('id')->on('task_categories')->onDelete('cascade');
            $table->foreign('sub_category')->references('id')->on('task_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
