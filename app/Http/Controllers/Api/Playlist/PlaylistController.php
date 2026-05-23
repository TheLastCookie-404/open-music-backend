<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\PlaylistResource;
use App\Models\User;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PlaylistController extends Controller
{
    public function index(PaginatedRequest $request) {
        $playlists = $request->paginate(auth('api')->user()->playlists());
        return PlaylistResource::collection($playlists);
    }

    /**
     * Store a newly created resource in storage.
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
            'message' => 'created',
            'playlist' => $playlist
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Playlist $playlist)
    {
        $request->validate([
            'playlist_id' => 'required|string|max:32|alpha_num'
        ]);

        $playlistId = $request->get('playlist_id');

        Log::info($playlistId);

        Gate::authorize('delete-playlist', [$playlist, $playlistId]);
        
        $playlist->destroy($playlistId);

        return response()->json([
            'message' => 'playlist deleted'
        ]);
    }
}
