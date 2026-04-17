<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaCollection;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;

class TrackController extends Controller
{
    private const PAGINATION_RULES = 'nullable|string|in:cursor,none';

    public function index(Request $request, Media $media) 
    {
        $request->validate([
            'pagination' => self::PAGINATION_RULES,
        ]);

        $pagination = $request->get('pagination');

        $trackList = $this->choosePaginationMethod($media, $pagination, 5);

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

        $trackList = $this->choosePaginationMethod($trackList, $pagination, 5);

        return new MediaCollection($trackList);
    }

    private function choosePaginationMethod(mixed $media, ?string $method, int $perPage) 
    {
        return match ($method) {
            'none' => $media->get(),
            'cursor' => $media->cursorPaginate($perPage)->withQueryString(),
            default => $media->paginate($perPage)->withQueryString(),
        };
    }
}
