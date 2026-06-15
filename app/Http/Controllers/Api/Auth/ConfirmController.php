<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Cache;

#[Group('Auth')]
class ConfirmController extends Controller
{
    /**
     * Confirm user mail
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $user = auth('api')->user();
        $code = $request->get('code');
        $userId = $user->id;
        $verificationCode = Cache::get("user_{$userId}_email_verify");

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], Response::HTTP_CONFLICT);
        }

        if ($verificationCode === null) {
            return response()->json([
                'message' => 'Verification code expired or was not sent'
            ], Response::HTTP_GONE);
        }

        if ($code !== $verificationCode) {
            return response()->json([
                'message' => 'Invalid verification code'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'message' => 'Email verified'
        ], Response::HTTP_ACCEPTED);
    }
}
