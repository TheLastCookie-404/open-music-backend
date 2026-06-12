<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\TrackResource;
use App\Models\Playlist;
use App\Models\Track;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LikeTrackController extends Controller
{
    public const UNIQUE_VIOLATION = '23505';
    public const INTEGRITY_CONSTRAINT_VIOLATION = '23000';
    
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
            'track_id' => 'required|string|max:32|alpha_num'
        ]);

        $trackId = $request->get('track_id');
        $user = auth('api')->user();
        $playlist = $user->likesPlaylist();

        Track::findOrFail($trackId);

        Gate::authorize('update-playlist-content', [Playlist::class, $playlist]);

        try {
            $playlist->addTrack($trackId);
        } catch (Exception $e) {
            Log::error($e);

            if ($e->getCode() === self::UNIQUE_VIOLATION || $e->getCode() === self::INTEGRITY_CONSTRAINT_VIOLATION) {
                return response()->json([
                    'message' => 'Track already exists',
                ], Response::HTTP_CONFLICT);
            }

            return response()->json([
                'message' => 'Track adding failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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

        Track::findOrFail($trackId);

        Gate::authorize('update-playlist-content', [Playlist::class, $playlist]);
        $playlist->removeTrack($trackId);

        return response()->json([
            'message' => 'Track removed'
        ], Response::HTTP_OK);
    }
}
