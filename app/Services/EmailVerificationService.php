<?php

namespace App\Services;

use App\Notifications\MailVerification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    public function isVerified()
    {
        $user = auth('api')->user();

        return $user->hasVerifiedEmail();
    }

    public function sendCode(?string $verificationUrl = null)
    {
        $user = auth('api')->user();
        $userId = $user->id;

        if ($user->hasVerifiedEmail()) {
            throw new ConflictHttpException('Email already verified');
        }

        $verificationCode = $this->generateCode($userId);

        $user->notify(new MailVerification($verificationCode, $verificationUrl));
    }

    public function verify(string $code)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        $verificationCodeKey = "user_{$userId}_email_verify";
        $verificationCode = Cache::get($verificationCodeKey);

        if ($this->isVerified()) {
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

        // $isCodeCached = Cache::put("user_{$userId}_email_verify", $verificationCode, $emailVerifyTtl);

        // if ($isCodeCached !== true) throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR);

        return $verificationCode;
    }

}
