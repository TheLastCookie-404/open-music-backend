<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\TrackResource;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PlaylistTrackController extends Controller
{
    /**
     * Display list of playlist tracks
     */
    public function show(PaginatedRequest $request)
    {
        $request->validate([
            'playlist_id' => 'required|string|max:32|alpha_num'
        ]);

        $playlistId = $request->get('playlist_id');

        $playlist = auth('api')->user()->playlists()->findOrFail($playlistId);
        $trackList = $request->paginate($playlist->tracks());

        return TrackResource::collection($trackList)->additional([
            'message' => 'Playlist tracks',
        ]);
    }
    
    /**
     * Add new track to playlist
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
            'message' => 'Track added'
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove track from playlist
     */
    public function destroy(Request $request, Playlist $playlist)
    {
        $request->validate([
            'playlist_id' => 'required|string|max:32|alpha_num',
            'track_id' => 'required|string|max:32|alpha_num'
        ]);

        $trackId = $request->get('track_id');
        $playlistId = $request->get('playlist_id');

        Gate::authorize('update-playlist', [$playlist, $playlistId]);

        $playlist = Playlist::findOrFail($playlistId);
        $playlist->removeTrack($trackId);

        return response()->json([
            'message' => 'Track removed'
        ], Response::HTTP_OK);
    }
}
