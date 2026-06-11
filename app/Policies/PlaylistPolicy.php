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

        return $user->id === $playlist->user_id && !$isLikesType;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deletePlaylist(User $user, Playlist $playlist) {
        $isLikesType = $playlist->type === 'likes';

        return $user->id === $playlist->user_id && !$isLikesType;
    }
}
