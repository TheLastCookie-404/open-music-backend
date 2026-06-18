<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Cache;

#[Group('Auth')]
class ConfirmController extends Controller
{
    public function __construct(
        protected EmailVerificationService $emailVerificationService
    ) {}
    /**
     * Confirm user mail
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        // $user = auth('api')->user();
        $code = $request->get('code');

        $this->emailVerificationService->verify($code);
        
        // $userId = $user->id;
        // $verificationCodeKey = "user_{$userId}_email_verify";
        // $verificationCode = Cache::get($verificationCodeKey);

        // if ($user->hasVerifiedEmail()) {
        //     return response()->json([
        //         'message' => 'Email already verified'
        //     ], Response::HTTP_CONFLICT);
        // }

        // if ($verificationCode === null) {
        //     Cache::forget($verificationCodeKey);
        //     return response()->json([
        //         'message' => 'Verification code expired or was not sent'
        //     ], Response::HTTP_GONE);
        // }

        // if ($code !== $verificationCode) {
        //     Cache::forget($verificationCodeKey);
        //     return response()->json([
        //         'message' => 'Invalid verification code'
        //     ], Response::HTTP_UNPROCESSABLE_ENTITY);
        // }

        // $user->markEmailAsVerified();

        // Cache::forget($verificationCodeKey);

        return response()->json([
            'message' => 'Email verified'
        ], Response::HTTP_ACCEPTED);
    }
}
