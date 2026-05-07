<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Http\Request;

class PlaylistController extends AuthController
{
    public function index(User $user) {
        return response()->json([
            'playlists' => auth('api')->user()->playlists()->get()
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function show(Request $request, User $user)
    {
        $request->validate([
            'playlist_id' => 'required|string|max:32|alpha_num'
        ]);

        $playlistId = $request->get('playlist_id');

        $playlist = auth('api')->user()->playlists()->findOrFail($playlistId);

        return response()->json([
            'playlist_id' =>  $playlist->value('id'),
            'playlist_name' => $playlist->value('name'),
            'playlist_tracks' => $playlist->tracks()->get()
        ]);
    }
}
