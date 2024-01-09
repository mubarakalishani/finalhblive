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
        Schema::create('submitted_task_proofs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->unsigned();
            $table->unsignedBigInteger('worker_id')->unsigned();
            $table->decimal('amount', 8, 4)->nullable(false);
            $table->tinyInteger('status');//0 pending, 1 approved, 2 revised asked, 3 revise submitted.
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('worker_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submitted_task_proofs');
    }
};
