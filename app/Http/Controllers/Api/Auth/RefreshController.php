<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;

class RefreshController extends AuthController
{
    // Refresh JWT token
    public function refresh()
    {
        /** @disregard P1013 Undefined method (for refresh()) */
        return $this->respondWithToken(auth('api')->refresh());
    }
}
