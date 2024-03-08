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
        Schema::table('withdrawal_histories', function (Blueprint $table) {
            $table->decimal('amount_no_fee', 7, 4)->change();
            $table->decimal('amount_after_fee', 7, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_histories', function (Blueprint $table) {
            //
        });
    }
};
