<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

#[Group('Auth')]
class RegisterController extends AuthController
{
    public function __construct(
        protected EmailVerificationService $emailVerificationService
    ) {}
    
    /**
     * Register new user
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'required|string|between:1,255',
            'email' => 'required|email:rfc,dns,strict|between:5,255',
            'password' => 'required|string|alpha_dash|between:6,12|confirmed',
            'verification_link_url' => 'nullable|string'
        ]);

        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');
        $verificationUrl = $request->get('verification_link_url');

        $user = User::query()->where('email', $email)->first();
        $isMailVerified = $user !== null ? $user->hasVerifiedEmail() : false;

        if ($isMailVerified === true) {
            throw new ConflictHttpException('This Email can`t be used');
        }

        $user = User::updateOrCreate(
            [
                'email' => strtolower($email),
            ],
            [
                'name' => $name,
                'nickname' => $name,
                'email' => strtolower($email),
                'password' => bcrypt($password)
            ]
        );

        $credentials = $request->only('email', 'password');

        /** @disregard P1013 Undefined method (for attempt()) */
        if (!$token = auth('api')->attempt($credentials)) {
            Log::info('Register auto authorization failed');
        }

        $this->emailVerificationService->sendCode($verificationUrl);

        return $this->respondWithToken($token, [
            'message' => 'User registered successfully', 
            'data' => $user
        ], Response::HTTP_CREATED)->cookie(
            'token', $token, config('jwt.refresh_ttl'), // Expires in 1 day
            '/', null, true, true, false // path, domain, secure, httpOnly, raw, sameSite
        );
    }
}