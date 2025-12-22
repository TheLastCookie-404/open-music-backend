<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;
use Illuminate\Support\Str;

class LoadController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'audio' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac',
        ]);

        $fileName = $request->file('audio')->getClientOriginalName();
        $file = $request->file('audio');

        $id =  mt_rand(0, 9999) . time();

        Storage::disk('public')->putFileAs("audiofile/$id", $file, $fileName);

        $track = GetId3::fromUploadedFile($file);

        if($artwork = $track->getArtwork(true)) {
            Storage::disk('public')->putFileAs("audiofile/$id", $artwork, "artwork.jpg");
        }

        return response()->json([
            "message" => "uploaded"
        ], 200);
    }
} 