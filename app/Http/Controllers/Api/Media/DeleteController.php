<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DeleteController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Media $media)
    {
        $request->validate([
            'uid' => 'required|string|max:32|alpha_num'
        ]);

        $uid = $request->get('uid');

        $media->where('uid', '=', $uid)->delete();
        Storage::disk('public')->deleteDirectory("audiofile/$uid");

        return response()->json($request->get('uid'));
    }
}
