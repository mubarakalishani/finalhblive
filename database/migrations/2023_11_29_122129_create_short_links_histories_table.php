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
        Schema::create('short_links_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('link_id')->unsigned();
            $table->decimal('reward', 6, 4)->default(0.00);
            $table->ipAddress('ip_address');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('link_id')->references('id')->on('short_links')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_links_histories');
    }
};
