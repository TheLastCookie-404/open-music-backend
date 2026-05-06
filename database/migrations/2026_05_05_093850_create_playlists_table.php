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
        Schema::create('playlists', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique()->nullable();
            $table
                ->foreignUlid('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('playtime')->nullable();
            $table->float('playtime_seconds')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
