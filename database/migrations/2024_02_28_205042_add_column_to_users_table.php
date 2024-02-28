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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('total_withdrawn', 8, 3)->default(0.00)->after('total_earned');
            $table->decimal('total_earned', 10, 5)->default(0.00)->change();
            $table->decimal('balance', 10, 5)->default(0.00)->change();
            $table->decimal('earned_from_referrals', 10, 5)->default(0.00)->change();
            $table->decimal('earned_from_offers', 10, 5)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
