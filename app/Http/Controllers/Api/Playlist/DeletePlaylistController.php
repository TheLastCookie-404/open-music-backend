<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeletePlaylistController extends Controller
{
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
