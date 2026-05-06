<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;

class CreatePlaylistController extends Controller
{
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
}
