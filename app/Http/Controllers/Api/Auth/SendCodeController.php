<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;

#[Group('Auth')]
class SendCodeController extends Controller
{
    /**
     * Send verification code
     */
    public function __invoke()
    {
        $verificationToken = (string) random_int(100000, 999999);

        $user = auth('api')->user();
        $userId = $user->id;
        $email = $user->email;

        // User::whereId($userId)->update([ <-- use this if bug found
        User::find($userId)->update([
            'verification_token' => $verificationToken
        ]);

        Log::info($user->remember_token);
        
        Mail::raw($verificationToken, function ($message) use ($email) {
            $message
                ->to($email)
                ->subject('Laravel');
        });

        return response()->json([
            'message' => 'Verification code was sent'
        ], Response::HTTP_OK);
    }
}
