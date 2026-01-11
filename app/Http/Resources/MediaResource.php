<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "created_at," => $this->created_at,
            "updated_at" => $this->updated_at,
            "uid" => $this->uid,
            "title" => $this->title,
            "artist" => $this->artist,
            "playtime" => $this->playtime,
            "playtime_seconds" => $this->playtime_seconds,
            "artwork_url" => $this->artwork_url,
            "audio_url" => $this->audio_url,
            "audio_download_url" => $this->audio_download_url,
        ];
    }
}
