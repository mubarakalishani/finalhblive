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
