<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\TrackResource;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PlaylistTrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(PaginatedRequest $request)
    {
        $request->validate([
            'playlist_id' => 'required|string|max:32|alpha_num'
        ]);

        $playlistId = $request->get('playlist_id');

        $playlist = auth('api')->user()->playlists()->findOrFail($playlistId);
        $trackList = $request->paginate($playlist->tracks());

        return TrackResource::collection($trackList);
        // return response()->json([
        //     'playlist_id' =>  $playlist->value('id'),
        //     'playlist_name' => $playlist->value('name'),
        //     'playlist_tracks' => $playlist->tracks()->get()
        // ]);
    }
    
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
            'message' => 'track removed'
        ]);
    }
}
