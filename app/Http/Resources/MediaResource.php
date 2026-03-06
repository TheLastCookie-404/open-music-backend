<?php

namespace App\Http\Resources;

use App\Models\Media;
use App\Models\User;
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
    public function toArray(Request $request)
    {
        $request->validate([
            // 'extended' => 'nullable|boolean'
            'extended' => 'string|in:yes,no'
        ]);

        $request->mergeIfMissing([
            'extended' => 'no'
        ]);

        $isExtended = $request->get('extended');
        $rootUrl = url("storage/media/$this->id");
        $audioUrl = "$rootUrl/$this->audio_filename";
        $fileNameDecoded = rawurldecode($this->audio_filename);
        $uploadedBy = User::where('id', '=', $this->uploaded_by)->value('name');
        $userInfo = User::where('id', '=', $this->uploaded_by);
        $artworkUrl = null;
        $fullDataEncoded = null;


        if ($this->artwork_filename !== null) {
            $artworkUrl = "$rootUrl/$this->artwork_filename";
        }

        try {
            $metadata = GetId3::fromDiskAndPath('media', "$this->id/$fileNameDecoded");
            $fullData = $metadata->extractInfo();
            $fullDataEncoded = mb_convert_encoding($fullData, 'UTF-8');
        } catch (Exception $e) {
            Log::error($e);
        }


        return [
            'id' => $this->id,
            'file_hash' => $this->file_hash,
            // 'uploaded_by' => $uploadedBy,
            // 'uploaded_by' => [
            //     'name' => $userInfo->value('name'),
            //     'nickname' => $userInfo->value('nickname'),
            //     'email' => $userInfo->value('email'),
            //     'role' => $userInfo->value('role'),
            // ],
            // 'uploaded_by' => $this->user()->get([
            //     'name', 
            //     'nickname', 
            //     'email', 
            //     'role'
            // ]),
            'uploaded_by' => [
                'name' => $this->user()->value('name'),
                'nickname' => $this->user()->value('nickname'),
                'email' => $this->user()->value('email'),
                'role' => $this->user()->value('role'),
            ],
            'created_at' => $this->created_at,
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
            'file_metadata' => $isExtended === 'yes' ? $fullDataEncoded : null,
        ];
    }
}
