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
        Schema::create('offers_and_surveys_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('provider_name', 50);
            $table->decimal('payout', 8, 4)->nullable()->default(0.00);
            $table->decimal('reward', 8, 4)->nullable()->default(0.00);
            $table->decimal('added_expert_level', 8, 4)->nullable()->default(0.00);
            $table->decimal('upline_commision', 8, 4)->nullable()->default(0.00);
            $table->string('transaction_id', 100)->nullable();
            $table->string('offer_id', 100)->nullable();
            $table->string('offer_name', 255)->nullable();
            $table->integer('hold_time')->unsigned()->default(0); //hold before credit in seconds
            $table->tinyInteger('instant_credit')->default(0);//0 no, 1 yes
            $table->ipAddress('ip_address')->nullable();
            $table->tinyInteger('status')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers_and_surveys_logs');
    }
};
