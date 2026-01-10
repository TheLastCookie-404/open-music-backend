<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'audio' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac',
        ]);

        $fileName = $request->file('audio')->getClientOriginalName();
        $file = $request->file('audio');
        $id =  str()->random(4) . time();
        $metadata = GetId3::fromUploadedFile($file);

        $fileUrls = $this->upload($id, $file, $fileName, $metadata->getArtwork(true));

        $this->storeInDB($id, $metadata, $fileUrls);

        return response()->json([
            "message" => "uploaded"
        ], 200);
    }


    private function upload(string $id, UploadedFile $file, string $fileName, mixed $artwork)
    {
        $rootUrl = url("storage/media/$id");
        $fileNameEncoded = rawurlencode($fileName);
        $audioUrl = "$rootUrl/$fileNameEncoded";
        $artworkUrl = null;

        Log::info($audioUrl);

        Storage::disk('media')->putFileAs($id, $file, $fileName);

        if($artwork !== null) {
            Storage::disk('media')->putFileAs($id, $artwork, "artwork.jpg");
            $artworkUrl = "$rootUrl/artwork.jpg";
        }

        return [
            'artwork_url' => $artworkUrl,
            'audio_url' => $audioUrl
        ];
    }

    private function storeInDB(string $uId, GetId3 $metadata, array $fileUrls)
    {
        Media::create([
            'uid' => $uId,
            'title' => $metadata->getTitle(),
            'artist' => $metadata->getArtist(),
            'playtime' => $metadata->getPlaytime(),
            'playtime_seconds' => $metadata->getPlaytimeSeconds(),
            'artwork_url' => $fileUrls['artwork_url'],
            'audio_url' => $fileUrls['audio_url'],
            'audio_download_url' => null,
        ]);
    }
} 