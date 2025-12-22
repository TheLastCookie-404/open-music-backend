<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class LogoutController extends AuthController
{
    // Logout user (invalidate token)
    public function index()
    {
        /** @disregard P1013 Undefined method (for logout()) */
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
