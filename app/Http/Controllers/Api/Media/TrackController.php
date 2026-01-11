<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;

class TrackController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Media $media)
    {
        $request->validate([
            'name' => 'nullable|string|max:218|min:1'
        ]);

        $trackName = $request->get('name');

        return response()->json(
            $this->getTrack($media, $trackName)
        );
    }

    private function getTrack(Media $media, ?string $trackName)
    {
        $trackList = $media::when($trackName, function ($query) use ($trackName) {
            return $query->where('title', '=', $trackName);
        })->latest('id')->get();

        // Format json
        $trackList = $trackList->toResourceCollection();

        $tracksCount = count($trackList);

        return [
            'found' => $tracksCount,
            'tracks' => $trackList
        ];
    }
}
