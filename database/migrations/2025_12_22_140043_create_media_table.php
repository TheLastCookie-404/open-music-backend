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
            $table->string('uid')->unique();
            $table->string('file_hash')->unique();
            $table->string('title');
            $table->string('artist')->nullable();
            $table->json('genres')->nullable();
            $table->string('album')->nullable();
            $table->string('playtime')->nullable();
            $table->float('playtime_seconds')->nullable();
            $table->string('artwork_filename')->nullable();
            $table->string('audio_filename');
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
