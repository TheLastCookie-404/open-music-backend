<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table
                ->foreignUlid('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->timestamps();
            $table->string('file_hash')->unique();
            $table->enum('status', [
                'available', 
                'restricted', 
                'forbidden'
            ])->default('available');
            $table->string('title');
            $table->string('artist')->nullable();
            $table->json('genres')->nullable();
            $table->json('tags')->nullable();
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
        Schema::dropIfExists('tracks');

        Storage::disk('tracks')->deleteDirectory('./');
        Storage::disk('public-tracks')->deleteDirectory('./');
    }
};