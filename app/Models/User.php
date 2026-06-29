<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUlids, Prunable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'role',
        'password',
        'verification_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];

    public function prunable()
    {
        return static::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDay());
    }

    // All uploaded tracks by user
    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    // All user playlists
    public function playlists() 
    {
        return $this->hasMany(Playlist::class);
    }

    public function likedTracks()
    {
        return $this->hasOne(Playlist::class)
            ->where('type', '=', 'likes')
            ->first();
    }

    public function likesPlaylist() 
    {
        return $this->hasOne(Playlist::class)->first();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
