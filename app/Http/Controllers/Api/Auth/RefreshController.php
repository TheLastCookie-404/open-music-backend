<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Auth')]
class RefreshController extends AuthController
{
    /** 
     * Refresh JWT token
     */ 
    public function __invoke()
    {
        /** @disregard P1013 Undefined method (for refresh()) */
        $token = auth('api')->refresh();

        return $this->respondWithToken($token, [
            'message' => 'Token refreshed'
        ], Response::HTTP_OK)->cookie(
            'token', $token, 60 * 24, // Expires in 1 day
            '/', null, true, true, false // path, domain, secure, httpOnly, raw, sameSite
        );
    }
}
