<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;

class RefreshController extends AuthController
{
    // Refresh JWT token
    public function index()
    {
        /** @disregard P1013 Undefined method (for refresh()) */
        if (!$token = auth('api')->refresh()) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }

        return $this->respondWithToken($token)
            ->cookie(
                'token', $token, 60 * 24, // Expires in 1 day
                '/', null, true, true, false, 'Strict' // path, domain, secure, httpOnly, raw, sameSite
            );
    }
}
