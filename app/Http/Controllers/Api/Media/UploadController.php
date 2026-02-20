<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'audio' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac',
        ]);

        $fileName = $request->file('audio')->getClientOriginalName();
        $fileNameEncoded = rawurlencode($fileName);
        $file = $request->file('audio');
        $fileHash = hash_file('sha256', $file);
        $uId =  str()->random(4) . time();
        $metadata = GetId3::fromUploadedFile($file);
        $artwork = $metadata->getArtwork(true);
        $artworkFileName = 'artwork.jpg';

        try {
            $this->storeInDB($uId, $metadata, $fileHash, [
                'artwork_filename' => $artwork !== null ? $artworkFileName : null,
                'audio_filename' => $fileNameEncoded
            ]);

            $this->upload($uId, $file, $fileName, $artwork, $artworkFileName);
        }
        catch (Exception) {
            return response()->json([
                'message' => 'Track already exist',
            ], Response::HTTP_CONFLICT);
        }

        return response()->json([
            'message' => 'Uploaded',
        ]);
    }


    private function upload(string $uId, UploadedFile $file, string $fileName, mixed $artwork, string $artworkFileName)
    {
        Storage::disk('media')->putFileAs($uId, $file, $fileName);

        if($artwork !== null) {
            Storage::disk('media')->putFileAs($uId, $artwork, $artworkFileName);
        }
    }

    private function storeInDB(string $uId, GetId3 $metadata, string $fileHash, array $fileUrls)
    {
        Media::create([
            'uid' => $uId,
            'file_hash' => $fileHash,
            'title' => $metadata->getTitle(),
            'artist' => $metadata->getArtist(),
            'genres' => $metadata->getGenres(),
            'album' => $metadata->getAlbum(),
            'playtime' => $metadata->getPlaytime(),
            'playtime_seconds' => $metadata->getPlaytimeSeconds(),
            'artwork_filename' => $fileUrls['artwork_filename'],
            'audio_filename' => $fileUrls['audio_filename'],
        ]);
    }
} 