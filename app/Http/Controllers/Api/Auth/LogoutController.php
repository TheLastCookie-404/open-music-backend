<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Auth')]
class LogoutController extends AuthController
{
    /** 
     * Logout user (invalidate token)
     */
    public function __invoke()
    {
        /** @disregard P1013 Undefined method (for logout()) */
        auth('api')->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ], Response::HTTP_OK);
    }
}
