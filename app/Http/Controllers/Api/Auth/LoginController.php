<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;

class LoginController extends AuthController
{
    // Login user and return JWT token
    public function index(Request $request)
    {
        $credentials = $request->only('email', 'password');

        /** @disregard P1013 Undefined method (for attempt()) */
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
}
