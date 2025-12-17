<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ 
{
    RegisterController,
    LoginController,
    RefreshController,
    ProfileController,
    LogoutController    
};

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('refresh', [RefreshController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    Route::get('profile', [ProfileController::class, 'profile']);
    Route::post('logout', [LogoutController::class, 'logout']);
});