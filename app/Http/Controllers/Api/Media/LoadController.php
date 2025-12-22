<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoadController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'audio' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac',
        ]);

        $request->file('audio')->store('uploads', 'public');

        return response()->json([
            "message" => "uploaded"
        ], 200);
    }
}