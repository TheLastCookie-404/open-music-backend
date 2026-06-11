<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class PlaylistPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function updatePlaylist(User $user, Playlist $playlist) 
    {
        $isLikesType = $playlist->type === 'likes';

        if ($isLikesType) return Response::deny('Likes playlist info cannot be updated via this endpoint');

        return $user->id === $playlist->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deletePlaylist(User $user, Playlist $playlist) {
        $isLikesType = $playlist->type === 'likes';

        if ($isLikesType) return Response::deny('Likes playlist cannot be deleted');

        return $user->id === $playlist->user_id;
    }
}
