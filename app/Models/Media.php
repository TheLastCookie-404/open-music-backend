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
    ];

    protected $casts = [
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}