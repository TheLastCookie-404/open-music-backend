<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'tags',
        'playtime',
        'playtime_seconds',
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // All tracks in current playlist
    public function tracks()
    {
        return $this->belongsToMany(Media::class, 'playlist_tracks')
                    ->using(PlaylistTrack::class);
    }

    public function addTrack(string $trackId)
    {
        $this->belongsToMany(Media::class, 'playlist_tracks')->attach($trackId);
    }

    public function removeTrack(string $trackId)
    {
        $this->belongsToMany(Media::class, 'playlist_tracks')->detach($trackId);
    }
}
