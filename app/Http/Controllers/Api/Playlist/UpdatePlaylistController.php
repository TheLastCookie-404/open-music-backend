<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class UpdatePlaylistController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Playlist $playlist)
    {
        $request->validate([
            'playlist_id' => 'required|string|max:32|alpha_num',
            'track_id' => 'required|string|max:32|alpha_num'
        ]);

        $trackId = $request->get('track_id');
        $playlistId = $request->get('playlist_id');

        Gate::authorize('update-playlist', [$playlist, $playlistId]);

        $playlist = Playlist::findOrFail($playlistId);
        $playlist->addTrack($trackId);

        return response()->json([
            'message' => 'track added'
        ]);
    }
}
