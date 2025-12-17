<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;

class ProfileController extends AuthController
{
    // Get user profile
    public function show()
    {
        return response()->json(auth('api')->user());
    }
}
