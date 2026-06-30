<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\TrackResource;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class LikeTrackController extends Controller
{ 
    /**
     * Display list of playlist tracks
     */
    public function index(PaginatedRequest $request)
    {
        $playlist = auth('api')->user()->likedTracks();
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
            'track_id' => 'required|string|max:32|alpha_num|'
        ]);

        $trackId = $request->get('track_id');
        $user = auth('api')->user();
        $playlist = $user->likesPlaylist();

        Track::findOrFail($trackId);

        Gate::authorize('update-playlist-content', [Playlist::class, $playlist]);

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
            'track_id' => 'required|string|max:32|alpha_num'
        ]);

        $trackId = $request->get('track_id');
        $user = auth('api')->user();
        $playlist = $user->likesPlaylist();

        $playlist->tracks()->findOrFail($trackId);

        Gate::authorize('update-playlist-content', [Playlist::class, $playlist]);
        $playlist->removeTrack($trackId);

        return response()->json([
            'message' => 'Track removed'
        ], Response::HTTP_OK);
    }
}
