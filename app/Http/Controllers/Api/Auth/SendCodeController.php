<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\MailVerification;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        $user = auth('api')->user();
        $userId = $user->id;

        Cache::put("user_{$userId}_email_verify", $verificationCode, 60);

        $user->notify(new MailVerification($verificationCode, $verificationUrl));

        return response()->json([
            'message' => 'Verification code was sent'
        ], Response::HTTP_OK);
    }
}
