<?php

namespace App\Http\Controllers\Api\Playlist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\TrackResource;
use Illuminate\Http\Request;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
