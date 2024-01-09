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
        Schema::create('reject_approval_reasons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('submitted_proof_id')->unsigned(); //foreign key to submitted proof
            $table->string('selected_reason', 100)->nullable(); //the reason user will select from the drop down
            $table->string('employer_comment', 255); // the note that employer must have to write
            $table->foreign('submitted_proof_id')->references('id')->on('submitted_task_proofs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reject_approval_reasons');
    }
};
