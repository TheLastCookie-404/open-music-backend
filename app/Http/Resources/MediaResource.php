<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Owenoj\LaravelGetId3\GetId3;


class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $request->validate([
            'extended' => 'nullable|boolean'
        ]);

        $isExtended = $request->get('extended');

        $rootUrl = url("storage/media/$this->uid");
        
        $audioUrl = "$rootUrl/$this->audio_filename";
        $fileNameDecoded = rawurldecode($this->audio_filename);
        $artworkUrl = null;
        $extendedData = null;

        if ($this->artwork_filename !== null) {
            $artworkUrl = "$rootUrl/$this->artwork_filename";
        }

        try {
            $metadata = GetId3::fromDiskAndPath('media', "$this->uid/$fileNameDecoded");
            $fullData = $metadata->extractInfo();
            $fullDataEncoded = mb_convert_encoding($fullData, 'UTF-8');
        } catch (Exception $error) {
            Log::error($error);
        }

        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'file_hash' => $this->file_hash,
            'created_at,' => $this->created_at,
            'updated_at' => $this->updated_at,
            'title' => $this->title,
            'artist' => $this->artist,
            'genres' => $this->genres,
            'album' => $this->album,
            'playtime' => $this->playtime,
            'playtime_seconds' => $this->playtime_seconds,
            'artwork_url' => $artworkUrl,
            'audio_url' => $audioUrl,
            'audio_download_url' => null,
            'file_metadata' => $isExtended ? $fullDataEncoded : null
        ];
    }
}
