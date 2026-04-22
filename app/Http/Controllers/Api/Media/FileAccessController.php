<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Media $media, $id)
    {
        $request->merge(['id' => $id]);

        $request->validate([
            'id' => 'required|string|max:32|alpha_num',
        ]);

        $id = $request->get('id');

        $track = $media->whereId($id);
        $trackStatus = $track->value('status');
        $fileName = $track->value('audio_filename');
        $fileName = rawurldecode($fileName);

        Gate::authorize('get-track', [$trackStatus]);
        
        $file = Storage::disk('media')->path("$id/$fileName");

        $response = new BinaryFileResponse($file);
        BinaryFileResponse::trustXSendfileTypeHeader();

        return $response;
        // return response()->file($file);
    }
}
