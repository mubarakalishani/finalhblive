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
        Schema::create('employer_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employer_id');
            $table->integer('tasks_created')->unsigned()->nullable()->default(0);
            $table->decimal('total_paid', 12, 4)->nullable()->default(0.00);
            $table->integer('tasks_rated')->unsigned()->nullable()->default(0);
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employer_stats');
    }
};
