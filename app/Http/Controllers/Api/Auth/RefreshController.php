<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class RefreshController extends AuthController
{
    // Refresh JWT token
    public function index()
    {
        try {
            /** @disregard P1013 Undefined method (for refresh()) */
            $token = auth('api')->refresh();
        }
        catch (JWTException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token)
            ->cookie(
                'token', $token, 60 * 24, // Expires in 1 day
                '/', null, true, true, false // path, domain, secure, httpOnly, raw, sameSite
            );
    }
}
