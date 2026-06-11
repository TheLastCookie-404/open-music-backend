<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Dedoc\Scramble\Scramble;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

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
        User::observe(UserObserver::class);
    }
}
