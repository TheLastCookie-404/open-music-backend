<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

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

        $isEntryExists = $media->where('uid', '=', $uid)->exists();
        $isDirecoryExists = Storage::disk('media')->exists("$uid");

        if ($isEntryExists && $isDirecoryExists) {
            $media->where('uid', '=', $uid)->delete();
            Storage::disk('media')->deleteDirectory("$uid");
        } else {
            return response()->json([
                "message" => "Track does not exist"
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            "message" => "Deleted"
        ]);
    }
}
