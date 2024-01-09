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
        Schema::create('category_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_category_id');
            $table->unsignedBigInteger('country_id');
            $table->string('country_name', 100);
            $table->decimal('min_reward_amount', 10, 4)->default(0.00);
            $table->timestamps();

            $table->foreign('task_category_id')->references('id')->on('task_categories')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('available_countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_rewards');
    }
};
