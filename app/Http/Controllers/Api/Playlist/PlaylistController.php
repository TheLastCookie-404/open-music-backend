<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\PlaylistResource;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PlaylistController extends Controller
{
    /**
     * Display list of playlists
     */
    public function index(PaginatedRequest $request) 
    {
        $playlists = $request->paginate(auth('api')->user()->playlists());

        return PlaylistResource::collection($playlists)->additional([
            'message' => 'Playlists',
        ]);
    }

    /**
     * Create new playlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $name = $request->get('name');

        $playlist = Playlist::create([
            'user_id' => auth('api')->user()->id,
            'name' => $name
        ]);

        return response()->json([            
            'message' => 'Playlist created',
            'data' => $playlist
        ], Response::HTTP_CREATED);
    }

    /**
     * Delete playlist
     */
    public function destroy(Request $request, Playlist $playlist)
    {
        $request->validate([
            'playlist_id' => 'required|string|max:32|alpha_num'
        ]);

        $playlistId = $request->get('playlist_id');
        $playlist = $playlist->findOrFail($playlistId);

        Gate::authorize('delete-playlist', [Playlist::class, $playlist]);
        
        $playlist->destroy($playlistId);

        return response()->json([
            'message' => 'Playlist deleted'
        ], Response::HTTP_OK);
    }
}
