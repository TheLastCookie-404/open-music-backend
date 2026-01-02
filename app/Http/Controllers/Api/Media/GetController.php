<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;

class GetController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Media $media) {
        return response()->json([
            "message" => "test"
        ]);
    }
}
