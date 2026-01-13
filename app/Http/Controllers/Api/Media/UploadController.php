<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'audio' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac',
        ]);

        $fileName = $request->file('audio')->getClientOriginalName();
        $file = $request->file('audio');
        $uId =  str()->random(4) . time();
        $metadata = GetId3::fromUploadedFile($file);

        $fileUrls = $this->upload($uId, $file, $fileName, $metadata->getArtwork(true));

        $this->storeInDB($uId, $metadata, $fileUrls);

        return response()->json([
            "message" => "uploaded"
        ], 200);
    }


    private function upload(string $uId, UploadedFile $file, string $fileName, mixed $artwork)
    {
        $fileNameEncoded = rawurlencode($fileName);
        $artworkFileName = null;

        Storage::disk('media')->putFileAs($uId, $file, $fileName);

        if($artwork !== null) {
            Storage::disk('media')->putFileAs($uId, $artwork, "artwork.jpg");
            $artworkFileName = 'artwork.jpg';
        }

        return [
            'artwork_filename' => $artworkFileName,
            'audio_filename' => $fileNameEncoded
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
            'artwork_filename' => $fileUrls['artwork_filename'],
            'audio_filename' => $fileUrls['audio_filename'],
        ]);
    }
} 