<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Playlist;
use ErrorException;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Playlist::create([
        //     'user_id' => $user->id,
        //     'name' => 'playlist.likes',
        //     'description' => 'playlist.likes.description',
        //     'type' => 'likes'
        // ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->wasChanged('email_verified_at') && !is_null($user->email_verified_at)) {
            Playlist::create([
                'user_id' => $user->id,
                'name' => 'playlist.likes',
                'description' => 'playlist.likes.description',
                'type' => 'likes'
            ]);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
