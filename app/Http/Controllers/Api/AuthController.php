<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Return token response structure
    protected function respondWithToken(string $token, array $data = [])
    {
        /** @disregard P1013 Undefined method (for factory()) */
        return response()->json([
            'message' => 'logged in',
            ...$data,
            'token_type' => 'bearer',
            'token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}