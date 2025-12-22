<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LoadController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'audio' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac',
        ]);

        $fileName = $request->file('audio')->getClientOriginalName();
        $file = $request->file('audio');

        Storage::disk('public')->putFileAs("audiofile", $file, $fileName);

        Log::info(exif_read_data("storage/audiofile/$fileName"));

        return response()->json([
            "message" => "uploaded"
        ], 200);
    }
} 