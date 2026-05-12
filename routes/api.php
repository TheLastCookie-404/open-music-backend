<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ 
{
    RegisterController,
    LoginController,
    RefreshController,
    ProfileController,
    LogoutController,
    UpdateRoleController,
};
use App\Http\Controllers\Api\Media\
{
    FileAccessController,
    DeleteController,
    UploadController,
    TrackController
};
use App\Http\Controllers\Api\Playlist\
{
    CreatePlaylistController,
    DeletePlaylistController,
    PlaylistController,
    UpdatePlaylistController
};

Route::prefix('/auth')->group(function () {
    Route::post('register', [RegisterController::class, 'index']); // Registers new User
    Route::post('login', [LoginController::class, 'index']); // Logs User in
    Route::post('refresh', [RefreshController::class, 'index']); // Refreshes User auth token

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [ProfileController::class, 'show']); // Shows profile of current user
        Route::post('logout', [LogoutController::class, 'index']); // Logs User out
    });
});

Route::prefix('/tracks')->group(function () {
    Route::get('/', [TrackController::class, 'index']); // Get all tracks
    Route::get('search', [TrackController::class, 'show']); // Gets list of matched tracks

    Route::middleware('auth:api')->group(function () {
        Route::post('/', [UploadController::class, 'store']); // Uploads one track
        Route::delete('/', [DeleteController::class, 'destroy']); // Deletes one track
    });
});

Route::prefix('/playlists')->group(function () {
    Route::get('/', []);
    Route::get('tracks', []);
});

Route::middleware('auth:api')->group(function () {

    Route::prefix('/users')->group(function () {
        Route::patch('role', [UpdateRoleController::class, 'update']); // Updates User`s role
    });

    Route::prefix('/playlists/me')->group(function () {
        Route::get('/', [PlaylistController::class, 'index']); // Show list of User`s playlists
        Route::post('/', [CreatePlaylistController::class, 'store']); // Creates playlist
        Route::delete('/', [DeletePlaylistController::class, 'destroy']); // Deletes playlist

        Route::prefix('/tracks')->group(function () {
            Route::get('file/{id}', [FileAccessController::class, 'index']); // Gets one track by id

            Route::get('/', [PlaylistController::class, 'show']); // Show list of User`s tracks in playlist
            Route::post('/', [UpdatePlaylistController::class, 'store']); // Adds new track to playlist
            Route::delete('/', [UpdatePlaylistController::class, 'destroy']); // Removes one track from playlist
        });
    });
});



// Route::middleware('auth:api')->group(function () {
//     Route::get('profile', [ProfileController::class, 'show']); // Shows profile of current user
//     Route::post('logout', [LogoutController::class, 'index']); // Logs User out
//     Route::post('upload', [UploadController::class, 'store']); // Uploads one track
//     Route::post('role', [UpdateRoleController::class, 'update']); // Updates User`s role
//     Route::post('tracks/delete', [DeleteController::class, 'destroy']); // Deletes one track

//     Route::get('playlists', [PlaylistController::class, 'index']); // Show list of User`s playlists
//     Route::get('playlist/track', [PlaylistController::class, 'show']); // Show list of User`s tracks in playlist
//     Route::post('playlist', [CreatePlaylistController::class, 'store']); // Creates playlist
//     Route::post('playlist/add', [UpdatePlaylistController::class, 'store']); // Adds new track to playlist
//     Route::post('playlist/remove', [UpdatePlaylistController::class, 'destroy']); // Removes one track from playlist
//     Route::post('playlist/delete', [DeletePlaylistController::class, 'destroy']); // Deletes playlist
// });

// Route::get('file/{id}', [FileAccessController::class, 'index']); // Gets one track by id
// Route::get('tracks', [TrackController::class, 'index']); // Get all tracks
// Route::get('tracks/search', [TrackController::class, 'show']); // Gets list of matched tracks
