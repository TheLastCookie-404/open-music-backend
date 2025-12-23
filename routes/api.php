<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Media\UploadController;
use App\Http\Controllers\Api\Auth\ 
{
    RegisterController,
    LoginController,
    RefreshController,
    ProfileController,
    LogoutController
};

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('logout', [LogoutController::class, 'index']);
    Route::post('upload', [UploadController::class, 'store']);
});

Route::post('register', [RegisterController::class, 'index']);
Route::post('login', [LoginController::class, 'index']);
Route::post('refresh', [RefreshController::class, 'index']);