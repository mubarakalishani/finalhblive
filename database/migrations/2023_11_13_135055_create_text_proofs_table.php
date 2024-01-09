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
        Schema::create('text_proofs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submitted_proof_id')->unsigned();
            $table->unsignedBigInteger('task_id')->unsigned();
            $table->integer('proof_no')->unsigned();
            $table->string('proof_text', 1000)->nullable();
            $table->foreign('submitted_proof_id')->references('id')->on('submitted_task_proofs')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_proofs');
    }
};
