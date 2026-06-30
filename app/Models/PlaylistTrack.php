<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistTrack extends Pivot
{
    use HasFactory;

    protected $table = 'playlist_tracks';

    protected $fillable = [
        'order_position'
    ];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);    
    }
}
