<?php

namespace App\Http\Controllers\Api\Media;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Resources\MediaCollection;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Owenoj\LaravelGetId3\GetId3;

class TrackController extends Controller
{
    private const PAGINATION_RULES = 'nullable|string|in:cursor,none';
    public const UNIQUE_VIOLATION = '23505';
    public const INTEGRITY_CONSTRAINT_VIOLATION = '23000';


    public function index(Request $request, Media $media) 
    {
        $request->validate([
            'pagination' => self::PAGINATION_RULES,
        ]);

        $pagination = $request->get('pagination');

        $trackList = $this->choosePaginationMethod($media, $pagination, 10);

        return new MediaCollection($trackList);
    }

    public function show(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'required_without:artist|nullable|string|max:218|min:1',
            'artist' => 'required_without|nullable|string|max:218|min:1',
            'pagination' => self::PAGINATION_RULES,
        ]);

        $title = $request->get('title');
        $artist = $request->get('artist');
        $pagination = $request->get('pagination');

        $trackList = $media
            ->when($title, function($query, $title) {
               return $query->where('title', 'LIKE', "%$title%");
            })
            ->when($artist, function($query, $artist) {
               return $query->orWhere('artist', 'LIKE', "%$artist%");
            });

        $trackList = $this->choosePaginationMethod($trackList, $pagination, 10);

        return new MediaCollection($trackList);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'audio' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac',
        ]);

        $fileName = $request->file('audio')->getClientOriginalName();
        $fileNameEncoded = rawurlencode($fileName);
        $file = $request->file('audio');
        $fileHash = hash_file('sha256', $file);
        $metadata = GetId3::fromUploadedFile($file);
        $artwork = $metadata->getArtwork(true);
        $artworkFileName = 'artwork.jpg';

        Gate::authorize('upload-track');

        try {

            $instance = $this->storeInDB($metadata, $fileHash, [
                'artwork_filename' => $artwork !== null ? $artworkFileName : null,
                'audio_filename' => $fileName // $fileNameEncoded
            ]);
            
            $this->upload($instance['id'], $file, $fileName, $artwork, $artworkFileName);
        }
        catch (Exception $e) {
            Log::error($e);

            if ($e->getCode() === self::UNIQUE_VIOLATION || $e->getCode() === self::INTEGRITY_CONSTRAINT_VIOLATION) {
                return response()->json([
                    'message' => 'Track already exists',
                ], Response::HTTP_CONFLICT);
            }

            return response()->json([
                'message' => 'Track uploading failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Uploaded',
        ], Response::HTTP_CREATED);
    }

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



    private function choosePaginationMethod(mixed $media, ?string $method, int $perPage) 
    {
        $latest = $media->latest();

        return match ($method) {
            'none' => $latest->get(),
            'cursor' => $latest->cursorPaginate($perPage)->withQueryString(),
            default => $latest->paginate($perPage)->withQueryString(),
        };
    }

    private function upload(string $id, UploadedFile $file, string $fileName, mixed $artwork, string $artworkFileName)
    {
        Storage::disk('media')->putFileAs($id, $file, $fileName);

        if($artwork !== null) {
            Storage::disk('public-media')->putFileAs($id, $artwork, $artworkFileName);
        }
    }

    private function storeInDB(GetId3 $metadata, string $fileHash, array $fileUrls)
    {
        return Media::create([
            'file_hash' => $fileHash,
            'user_id' => auth('api')->user()->id,
            'title' => $metadata->getTitle(),
            'artist' => $metadata->getArtist(),
            'genres' => $metadata->getGenres(),
            'album' => $metadata->getAlbum(),
            'playtime' => $metadata->getPlaytime(),
            'playtime_seconds' => $metadata->getPlaytimeSeconds(),
            'artwork_filename' => $fileUrls['artwork_filename'],
            'audio_filename' => $fileUrls['audio_filename'],
        ]);
    }
}
