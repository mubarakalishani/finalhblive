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
        Schema::create('withdrawal_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unassigned()->nullable();
            $table->string('method', 50);
            $table->text('wallet');
            $table->decimal('amount_no_fee', 5, 2);
            $table->decimal('amount_after_fee', 5, 2);
            $table->decimal('fee', 7, 4);
            $table->integer('status')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_histories');
    }
};
