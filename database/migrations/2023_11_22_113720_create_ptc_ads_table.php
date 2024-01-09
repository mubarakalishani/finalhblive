<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ptc_ads', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 30)->unique();
            $table->bigInteger('employer_id')->unsigned();
            $table->decimal('ad_balance', 10, 4)->default(0.00);
            $table->decimal('temp_locked_balance', 10, 4)->default(0.00);
            $table->decimal('reward_per_view', 5, 4)->default(0.00);
            $table->integer('views_needed')->unsigned()->default(0);
            $table->integer('views_completed')->unsigned()->default(0);
            $table->string('title', 15);
            $table->string('description', 100);
            $table->string('url', 1000);
            $table->tinyInteger('seconds');
            $table->tinyInteger('revision_interval')->default(24);
            $table->text('targeted_countries')->nullable();
            $table->text('excluded_countries')->nullable();
            $table->tinyInteger('type')->default(0); //iframe or window, 0 for iframe and 1 for windows
            $table->tinyInteger('status')->default(0);
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptc_ads');
    }
};
