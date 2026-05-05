<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistTrack extends Pivot
{
    use HasFactory, HasUlids;
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
        return $this->belongsTo(Media::class);    
    }
}
