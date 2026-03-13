<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class DeleteController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Media $media)
    {        
        $request->validate([
            'id' => 'required|string|max:32|alpha_num'
        ]);

        $id = $request->get('id');
        
        $isEntryExists = $media->where('id', '=', $id)->exists();
        $isDirecoryExists = Storage::disk('media')->exists("$id");

        if ($isEntryExists || $isDirecoryExists) {
            Gate::authorize('delete-track', [$media, $id]);
            
            $media->where('id', '=', $id)->delete();
            Storage::disk('media')->deleteDirectory("$id");
            Storage::disk('public-media')->deleteDirectory("$id");
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
