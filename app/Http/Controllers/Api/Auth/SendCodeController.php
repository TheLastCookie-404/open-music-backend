<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\MailVerification;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

#[Group('Auth')]
class SendCodeController extends Controller
{
    private const MAX_RAND_NUM = 999999;
    /**
     * Send verification code
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'verification_link_url' => 'nullable|string'
        ]);

        $verificationUrl = $request->get('verification_link_url');

        $randomNumber = mt_rand(0, self::MAX_RAND_NUM);
        $verificationCode = str_pad($randomNumber, 6, '0', STR_PAD_LEFT);
        $emailVerifyTtl = config('auth.email_verify_ttl');

        $user = auth('api')->user();
        $userId = $user->id;

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], Response::HTTP_CONFLICT);
        }

        Cache::put("user_{$userId}_email_verify", $verificationCode, $emailVerifyTtl);

        $user->notify(new MailVerification($verificationCode, $verificationUrl));

        return response()->json([
            'message' => 'Verification code was sent',
            'meta' => [
                'expires_in' => $emailVerifyTtl
            ]
        ], Response::HTTP_OK);
    }
}
