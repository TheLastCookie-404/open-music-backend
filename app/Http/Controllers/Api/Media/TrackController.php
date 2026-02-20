<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
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
            'title' => 'required_without:artist|nullable|string|max:218|min:1',
            'artist' => 'required_without|nullable|string|max:218|min:1',
        ]);

        $title = $request->get('title');
        $artist = $request->get('artist');

        // $trackList = $media
        //     ->where('title', 'LIKE', '%' . $title . '%')->get();

        $trackList = $media
            ->when($title, function($query, $title) {
               return $query->where('title', 'LIKE', "%$title%");
            })
            ->when($artist, function($query, $artist) {
               return $query->orWhere('artist', 'LIKE', "%$artist%");
            })
            ->get();

        return $this->formatResponse($trackList);
    }

    private function formatResponse(mixed $trackList)
    {
        $trackList = $trackList->toResourceCollection();
        $tracksCount = count($trackList);

        return response()->json(
            [
                'found' => $tracksCount,
                'tracks' => $trackList
            ],          
        );  
    }
}
