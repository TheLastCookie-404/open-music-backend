<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\MailVerification;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

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
        $verificationToken = str_pad($randomNumber, 6, '0', STR_PAD_LEFT);

        $user = auth('api')->user();
        $userId = $user->id;
        $email = $user->email;

        // User::whereId($userId)->update([ <-- use this if bug found
        $user->update([
            'verification_token' => $verificationToken
        ]);

        $user->notify(new MailVerification($verificationToken, $verificationUrl));
        
        // Mail::raw($verificationToken, function ($message) use ($email) {
        //     $message
        //         ->to($email)
        //         ->subject('Laravel');
        // });

        return response()->json([
            'message' => 'Verification code was sent'
        ], Response::HTTP_OK);
    }
}
