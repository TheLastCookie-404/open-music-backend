<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AuthController
{
    /** 
     * Login user and return JWT token
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        
        $credentials = $request->only('email', 'password');

        if (auth('api')->user()) {
            return response()->json([
                'message' => 'user already logged in'
            ]);
        }

        /** @disregard P1013 Undefined method (for attempt()) */
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // return $this->respondWithToken($token);
        return $this->respondWithToken($token)
            ->cookie(
                'token', $token, config('jwt.refresh_ttl'), // Expires in 1 day
                '/', null, true, true, false // path, domain, secure, httpOnly, raw, sameSite
            );
    }
}
