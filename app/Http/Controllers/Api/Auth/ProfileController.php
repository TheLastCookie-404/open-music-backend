<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AuthController
{
    /** 
     * Get user profile
     */
    public function __invoke()
    {
        return response()->json([
            'message' => 'User profile',
            'data' => auth('api')->user()
        ], Response::HTTP_OK);
    }
}
