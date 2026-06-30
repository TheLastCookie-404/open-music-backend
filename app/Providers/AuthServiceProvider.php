<?php

namespace App\Providers;

use App\Models\Track;
use App\Models\Playlist;
use App\Models\User;
use App\Policies\TrackPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    // private const ROLE_RESTRICTIONS = [
    //     'guest' => ['available'], 
    //     'user' => ['available'],
    //     'admin' => ['available', 'restricted'],
    //     'superadmin' => ['available', 'restricted', 'forbidden']
    // ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('update-role', function (User $user) {
            return $user->role === 'superadmin';
        });
    }
}
