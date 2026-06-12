<?php

namespace App\Http\Controllers\Api\Track;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Group('Track')]
class FileAccessController extends Controller
{
    use AuthorizesRequests;

    /**
     * File access
     */
    public function show(Request $request, Track $track, $id)
    {
        $request->merge(['id' => $id]);

        $request->validate([
            'id' => 'required|string|max:32|alpha_num',
        ]);

        $id = $request->get('id');

        $track = $track->findOrFail($id);
        $trackStatus = $track->value('status');
        $fileName = $track->value('audio_filename');
        $fileName = rawurldecode($fileName);

        // Auth with policy (policiy doesnt works without Track::class)
        Gate::authorize('get-track', [Track::class, $trackStatus]);
        
        $file = Storage::disk('track')->path("$id/$fileName");

        if (auth('api')->check()) {
            Log::info(auth('api')->user()->name . ' now listens: ' . $track->value('title') . ' - ' . $track->value('artist'));
        }

        $response = new BinaryFileResponse($file);
        BinaryFileResponse::trustXSendfileTypeHeader();

        return $response;
    }
}
