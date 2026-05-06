<?php

namespace App\Providers;

use App\Models\Media;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const ROLE_RESTRICTIONS = [
        'guest' => ['available'], 
        'user' => ['available'],
        'admin' => ['available', 'restricted'],
        'superadmin' => ['available', 'restricted', 'forbidden']
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('delete-track', function (User $user, Media $media, string $id) {
            $uploadedById = $media->where('id', '=', $id)->value('user_id');
            
            return $user->id === $uploadedById || $user->role === 'superadmin';
        });

        Gate::define('upload-track', function (User $user) {
            // return $user->role === 'admin' || $user->role === 'superadmin';
            return \in_array($user->role, ['admin', 'superadmin']);
        });

        Gate::define('update-role', function (User $user) {
            return $user->role === 'superadmin';
        });

        Gate::define('get-track', function (?User $user, string $mediaStatus) {
            return \in_array($mediaStatus, self::ROLE_RESTRICTIONS[$user->role ?? 'guest']);
        });


        Gate::define('update-playlist', function (User $user, Playlist $playlist, string $id) {
            $createdBy = $playlist->where('id', '=', $id)->value('user_id');

            return $user->id === $createdBy;
        });
    }
}
