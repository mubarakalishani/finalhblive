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
        Schema::create('ptc_ad_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->decimal('reward_per_view', 8, 5)->default(0.00);
            $table->smallInteger('seconds')->nullable()->default(5);
            $table->integer('minimum_views')->unsigned()->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptc_ad_packages');
    }
};
