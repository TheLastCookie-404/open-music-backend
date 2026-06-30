<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\MailVerification;
use App\Services\EmailVerificationService;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

#[Group('Auth')]
class SendCodeController extends Controller
{
    // private const MAX_RAND_NUM = 999999;

    public function __construct(
        protected EmailVerificationService $emailVerificationService
    ) {}

    /**
     * Send verification code
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'verification_link_url' => 'nullable|string'
        ]);

        $verificationUrl = $request->get('verification_link_url');

        $emailVerifyTtl = config('auth.email_verify_ttl');

        $this->emailVerificationService->sendCode($verificationUrl);

        return response()->json([
            'message' => 'Verification code was sent',
            'meta' => [
                'expires_in' => $emailVerifyTtl
            ]
        ], Response::HTTP_OK);
    }
}
