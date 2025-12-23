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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('uid');
            $table->string('title')->nullable();
            $table->string('artist')->nullable();
            $table->string('playtime')->nullable();
            $table->float('playtime_seconds')->nullable();
            $table->string('artwork_url')->nullable();
            $table->string('audio_url');
            $table->string('audio_download_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
