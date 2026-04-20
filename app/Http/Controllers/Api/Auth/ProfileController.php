<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ProfileController extends AuthController
{
    // Get user profile
    public function show()
    {
        return response()->json(auth('api')->user());
    }
}
