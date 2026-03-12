<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory, HasUlids;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // 'uid',
        // 'uploaded_by',
        'user_id',
        'file_hash',
        'status',
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
        // 'uid',
        // 'uploaded_by',
        'user_id',
        'file_hash',
        'status',
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

    // public function uploader() 
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}