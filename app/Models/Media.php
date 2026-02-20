<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uid',
        'file_hash',
        'title',
        'artist',
        'playtime',
        'playtime_seconds',
        'artwork_filename',
        'audio_filename',
        'genres',
        'album',
        // 'artwork_url',
        // 'audio_url',
        // 'audio_download_url',
    ];

    protected $casts = [
        'uid',
        'file_hash',
        'title',
        'artist',
        'playtime',
        'playtime_seconds',
        'artwork_filename',
        'audio_filename',
        'genres' => 'array',
        'album',
        // 'audio_url',
        // 'audio_download_url',
    ];
}