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
        Schema::create('short_links', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('url', 500);
            $table->string('unique_id', 32)->unique();
            $table->decimal('reward', 5, 4)->default(0.00);
            $table->tinyInteger('views_per_day')->default(1);
            $table->smallInteger('min_seconds')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
