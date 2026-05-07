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
    PlaylistController,
    UpdatePlaylistController
};

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('logout', [LogoutController::class, 'index']);
    Route::post('upload', [UploadController::class, 'store']);
    Route::post('role', [UpdateRoleController::class, 'update']);
    Route::post('tracks/delete', [DeleteController::class, 'destroy']);

    Route::get('playlists', [PlaylistController::class, 'index']);
    Route::get('playlist/track', [PlaylistController::class, 'show']);
    Route::post('playlist', [CreatePlaylistController::class, 'store']);
    Route::post('playlist/add', [UpdatePlaylistController::class, 'store']);
});

Route::get('file/{id}', [FileAccessController::class, 'index']);
Route::get('tracks', [TrackController::class, 'index']);
Route::get('tracks/search', [TrackController::class, 'show']);
Route::post('login', [LoginController::class, 'index']);
Route::post('refresh', [RefreshController::class, 'index']);
Route::post('register', [RegisterController::class, 'index']);