<?php

namespace App\Services;

use App\Notifications\MailVerification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EmailVerificationService
{
    private const MAX_RAND_NUM = 999999;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sendCode(?string $verificationUrl = null)
    {
        $user = $this->getUser();
        $userId = $user->id;

        if ($user->hasVerifiedEmail()) {
            throw new ConflictHttpException('Email already verified');
        }

        $verificationCode = $this->generateCode($userId);

        $user->notify(new MailVerification($verificationCode, $verificationUrl));
    }

    public function verify(string $code)
    {
        $user = $this->getUser();
        $userId = $user->id;
        $verificationCodeKey = "user_{$userId}_email_verify";
        $verificationCode = Cache::get($verificationCodeKey);

        if ($user->hasVerifiedEmail()) {
            throw new ConflictHttpException('Email already verified');
        }

        if ($verificationCode === null) {
            throw new GoneHttpException('Verification code expired or was not sent');
        }

        if ($code !== $verificationCode) {
            throw new UnprocessableEntityHttpException('Invalid verification code');
        }

        $user->markEmailAsVerified();

        Cache::forget($verificationCodeKey);
    }

    private function generateCode(string $userId)
    {
        $emailVerifyTtl = config('auth.email_verify_ttl');
        $randomNumber = mt_rand(0, self::MAX_RAND_NUM);
        $verificationCode = str_pad($randomNumber, 6, '0', STR_PAD_LEFT);

        Cache::put("user_{$userId}_email_verify", $verificationCode, $emailVerifyTtl);

        return $verificationCode;
    }

    private function getUser() {
        $user = auth('api')->user();

        if ($user === null) {
            throw new UnauthorizedHttpException('Unauthenticated.');
        }

        return $user;
    }
}
