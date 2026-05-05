<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Media\
{
    FileAccessController,
    DeleteController,
    UploadController,
    TrackController
};
use App\Http\Controllers\Api\Auth\ 
{
    RegisterController,
    LoginController,
    RefreshController,
    ProfileController,
    LogoutController,
    UpdateRoleController,
};

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('logout', [LogoutController::class, 'index']);
    Route::post('upload', [UploadController::class, 'store']);
    Route::post('role', [UpdateRoleController::class, 'update']);
    Route::post('tracks/delete', [DeleteController::class, 'destroy']);
});

Route::get('file/{id}', [FileAccessController::class, 'index']);
Route::get('tracks', [TrackController::class, 'index']);
Route::get('tracks/search', [TrackController::class, 'show']);
Route::post('login', [LoginController::class, 'index']);
Route::post('refresh', [RefreshController::class, 'index']);
Route::post('register', [RegisterController::class, 'index']);