<?php

namespace App\Policies;

use App\Models\Track;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TrackPolicy
{
    private const ROLE_RESTRICTIONS = [
        'guest' => ['available'], 
        'user' => ['available'],
        'admin' => ['available', 'restricted'],
        'superadmin' => ['available', 'restricted', 'forbidden']
    ];

    /**
     * Determine whether the user can get a track (for file access control).
     */
    public function getTrack(?User $user, string $trackStatus) 
    {
        return \in_array($trackStatus, self::ROLE_RESTRICTIONS[$user->role ?? 'guest']);
    }

    /**
     * Determine whether the user can upload track.
     */
    public function uploadTrack(User $user)
    {
        return \in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can delete the track.
     */
    public function deleteTrack(User $user, Track $track) 
    {
        $isStatusMatchPermissins = \in_array($user->role, ['admin', 'superadmin']);
        
        return $user->id === $track->user_id && $isStatusMatchPermissins || $user->role === 'superadmin';
    }
}
