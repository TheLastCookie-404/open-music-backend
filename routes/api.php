<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Track\FileAccessController;
use App\Http\Controllers\Api\Track\TrackController;
use App\Http\Controllers\Api\Playlist\PlaylistController;
use App\Http\Controllers\Api\Playlist\PlaylistTrackController;
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
    Route::post('login', LoginController::class); // Logs User in
    Route::post('send-code', SendCodeController::class);
    Route::post('confirm-email', ConfirmController::class); 
    Route::put('refresh', RefreshController::class); // Refreshes User auth token

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', ProfileController::class); // Shows profile of current user
        Route::delete('logout', LogoutController::class); // Logs User out
    });
});

Route::prefix('/tracks')->group(function () {
    Route::get('/', [TrackController::class, 'index']); // Get all tracks
    Route::get('search', [TrackController::class, 'show']); // Gets list of matched tracks

    Route::middleware('auth:api')->group(function () {
        Route::post('/', [TrackController::class, 'store']); // Uploads one track
        Route::delete('/', [TrackController::class, 'destroy']); // Deletes one track
    });
});

Route::get('file/{id}', [FileAccessController::class, 'show']); // Gets one track by id

Route::prefix('/playlists')->group(function () {
    Route::get('/', []);
    Route::get('tracks', []);
});

Route::middleware('auth:api')->group(function () {

    Route::prefix('/users')->group(function () {
        Route::patch('role', RoleController::class); // Updates User`s role
    });

    Route::prefix('/me/playlists')->group(function () {
        Route::get('/', [PlaylistController::class, 'index']); // Show list of User`s playlists
        Route::post('/', [PlaylistController::class, 'store']); // Creates playlist
        Route::delete('/', [PlaylistController::class, 'destroy']); // Deletes playlist

        Route::prefix('/tracks')->group(function () {
            Route::get('/', [PlaylistTrackController::class, 'show']); // Show list of User`s tracks in playlist
            Route::post('/', [PlaylistTrackController::class, 'store']); // Adds new track to playlist
            Route::delete('/', [PlaylistTrackController::class, 'destroy']); // Removes one track from playlist
        });
    });
});