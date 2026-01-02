<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class RefreshController extends AuthController
{
    // Refresh JWT token
    public function index()
    {
        /** @disregard P1013 Undefined method (for refresh()) */
        try {
            $token = auth('api')->refresh();
        }
        catch (JWTException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 401);
        }

        return $this->respondWithToken($token)
            ->cookie(
                'token', $token, 60 * 24, // Expires in 1 day
                '/', null, true, true, false, 'Strict' // path, domain, secure, httpOnly, raw, sameSite
            );
    }
}
