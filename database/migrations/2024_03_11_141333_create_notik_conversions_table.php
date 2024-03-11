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
        Schema::create('notik_conversions', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100);
            $table->string('click_id', 500);
            $table->string('campaign_id', 500);
            $table->string('campaign_name', 500);
            $table->string('traffic_source', 500);
            $table->string('user_country_code', 500);
            $table->string('remarks', 500);
            $table->ipAddress('user_ip');
            $table->integer('days')->unsigned()->default(0);
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notik_conversions');
    }
};
