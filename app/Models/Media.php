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
        'title',
        'artist',
        'playtime',
        'playtime_seconds',
        'artwork_filename',
        'audio_filename',
        'file_hash',
        // 'artwork_url',
        // 'audio_url',
        // 'audio_download_url',
    ];

    protected $casts = [
        'uid',
        'title',
        'artist',
        'playtime',
        'playtime_seconds',
        'artwork_filename',
        'audio_filename',
        'file_hash',
        // 'audio_url',
        // 'audio_download_url',
    ];
}