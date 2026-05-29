<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Auth')]
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
        $user = auth('api')->user();

        if ($user->email ?? false === $credentials['email']) {
            return response()->json([
                'message' => 'User already logged in'
            ], Response::HTTP_CONFLICT);
        }

        /** @disregard P1013 Undefined method (for attempt()) */
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // return $this->respondWithToken($token);
        return $this->respondWithToken($token, [
            'message' => 'Logged in',
        ], Response::HTTP_ACCEPTED)->cookie(
            'token', $token, config('jwt.refresh_ttl'), // Expires in 1 day
            '/', null, true, true, false // path, domain, secure, httpOnly, raw, sameSite
        );
    }
}
