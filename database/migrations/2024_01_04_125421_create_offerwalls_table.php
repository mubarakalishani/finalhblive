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
        Schema::create('offerwalls', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('order')->unsigned();
            $table->string('name', 20);
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->string('secret_key', 200)->nullable();
            $table->string('api_key', 200)->nullable();
            $table->text('whitelisted_ips')->nullable();
            $table->integer('starter_cp')->unsigned()->default(30);
            $table->integer('advance_cp')->unsigned()->default(50);
            $table->integer('expert_cp')->unsigned()->default(80);
            $table->integer('ref_commission')->unsigned()->default(7);
            $table->double('tier1_hold_amount')->default(0.5);
            $table->integer('tier1_hold_time')->unsigned()->default(30);
            $table->double('tier2_hold_amount')->default(1);
            $table->integer('tier2_hold_time')->unsigned()->default(30);
            $table->double('tier3_hold_amount')->default(5);
            $table->integer('tier3_hold_time')->unsigned()->default(30);
            $table->tinyInteger('hold')->unsigned()->default(1);
            $table->string('iframe_url', 1000);
            $table->text('iframe_styles')->nullable();
            $table->text('iframe_extra_elements')->nullable();
            $table->tinyInteger('is_target_blank')->default(0);
            $table->string('image_url', 1000);
            $table->text('image_styles')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offerwalls');
    }
};
