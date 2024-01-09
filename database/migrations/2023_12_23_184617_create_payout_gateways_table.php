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
        Schema::create('payout_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->decimal('min_payout', 5, 3)->default(0.00);
            $table->decimal('fixed_fee', 5, 3)->default(0.00);
            $table->tinyInteger('fee_percentage')->unsigned()->default(0);
            $table->tinyInteger('instant')->unsigned()->default(0);
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_gateways');
    }
};
