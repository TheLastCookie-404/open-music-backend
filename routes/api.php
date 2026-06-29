<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Track\FileAccessController;
use App\Http\Controllers\Api\Track\TrackController;
use App\Http\Controllers\Api\Playlist\PlaylistController;
use App\Http\Controllers\Api\Playlist\PlaylistTrackController;
use App\Http\Controllers\Api\Playlist\LikeController;
use App\Http\Controllers\Api\Playlist\LikeTrackController;
use App\Http\Controllers\Api\Auth\ 
{
    ConfirmController,
    RegisterController,
    LoginController,
    RefreshController,
    ProfileController,
    LogoutController,
    RoleController,
    SendCodeController,
};

Route::prefix('/auth')->group(function () {
    Route::post('register', RegisterController::class); // Registers new User
    Route::post('login', LoginController::class)->middleware('verified'); // Logs User in
    Route::post('refresh', RefreshController::class); // Refreshes User auth token

    Route::middleware('auth:api')->group(function () {
        Route::post('send-code', SendCodeController::class);
        Route::post('verify-email', ConfirmController::class); 
        Route::get('profile', ProfileController::class)->middleware('verified'); // Shows profile of current user
        Route::delete('logout', LogoutController::class)->middleware('verified'); // Logs User out
    });
});

Route::prefix('/tracks')->group(function () {
    Route::get('/', [TrackController::class, 'index']); // Get all tracks
    Route::get('search', [TrackController::class, 'show']); // Gets list of matched tracks

    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::post('/', [TrackController::class, 'store']); // Uploads one track
        Route::delete('/', [TrackController::class, 'destroy']); // Deletes one track
    });
});

Route::get('file/{id}', [FileAccessController::class, 'show']); // Gets one track by id

// Route::prefix('/playlists')->group(function () {
//     Route::get('/', []);
//     Route::get('tracks', []);
// });

Route::middleware(['auth:api', 'verified'])->group(function () {

    Route::prefix('/users')->group(function () {
        Route::patch('role', RoleController::class); // Updates User`s role
    });

    Route::prefix('/me')->group(function () {
        Route::prefix('/playlists')->group(function () {
            Route::get('/', [PlaylistController::class, 'index']); // Show list of User`s playlists
            Route::post('/', [PlaylistController::class, 'store']); // Creates playlist
            Route::delete('/', [PlaylistController::class, 'destroy']); // Deletes playlist

            Route::prefix('/tracks')->group(function () {
                Route::get('/', [PlaylistTrackController::class, 'show']); // Show list of User`s tracks in playlist
                Route::post('/', [PlaylistTrackController::class, 'store']); // Adds new track to playlist
                Route::delete('/', [PlaylistTrackController::class, 'destroy']); // Removes one track from playlist
            });

            Route::prefix('/likes')->group(function () {
                Route::get('/', [LikeController::class, 'index']); // Show info about likes playlist
                // Route::post('/', [, 'store']);
                // Route::delete('/', [, 'destroy']);

                Route::prefix('/tracks')->group(function () {
                    Route::get('/', [LikeTrackController::class, 'index']); // Show list of User`s tracks in likes playlist
                    Route::post('/', [LikeTrackController::class, 'store']); // Adds new track to likes playlist
                    Route::delete('/', [LikeTrackController::class, 'destroy']); // Removes one track from likes playlist
                });
            });
        });
    });
});