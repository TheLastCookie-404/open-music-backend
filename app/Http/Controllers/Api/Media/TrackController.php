<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;
use Illuminate\Support\Facades\Log;

class TrackController extends Controller
{
    /**
     * Display the specified resource.
     */

    public function index(Media $media) 
    {
        $trackList = $media->all();

        return $this->formatResponse($trackList);
    }


    public function show(Request $request, Media $media)
    {
        $request->validate([
            'name' => 'string|max:218|min:1'
        ]);

        $trackName = $request->get('name');

        Log::info($trackName);

        $trackList = $media
            ->where('title', '=', $trackName)
            ->get();

        return $this->formatResponse($trackList);
    }


    private function formatResponse($trackList)
    {
        $trackList = $trackList->toResourceCollection();
        $tracksCount = count($trackList);

        return response()->json(
            [
                'found' => $tracksCount,
                'tracks' => $trackList
            ]              
        );  
    }
}
