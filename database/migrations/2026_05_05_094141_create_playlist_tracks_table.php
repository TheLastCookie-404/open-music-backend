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
            $table
                ->foreignUlid('media_id')
                ->references('id')
                ->on('media')
                ->onDelete('cascade');
            $table
                ->foreignUlid('playlist_id')
                ->references('id')
                ->on('playlists')
                ->onDelete('cascade');
            $table->unique(['playlist_id', 'media_id']);
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
