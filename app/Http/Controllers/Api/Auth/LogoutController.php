<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;

class LogoutController extends AuthController
{
    // Logout user (invalidate token)
    public function logout()
    {
        /** @disregard P1013 Undefined method (for logout()) */
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
