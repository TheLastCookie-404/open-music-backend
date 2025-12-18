<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoadController extends Controller
{
    public function store(Request $request) 
    {
        $request->validate([
            'audio' => 'required',
        ]);

        Log::info(json_encode($request->get('audio')));

        return response()->json([
            $request->get('audio')
        ], 200);
    }
}
