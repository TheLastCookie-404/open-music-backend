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
        Schema::create('playlist_tracks', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table
                ->foreignUlid('track_id')
                ->references('id')
                ->on('media')
                ->nullOnDelete();
            $table
                ->foreignUlid('playlist_id')
                ->references('id')
                ->on('playlists')
                ->nullOnDelete();
            $table->integer('order_position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_tracks');
    }
};
