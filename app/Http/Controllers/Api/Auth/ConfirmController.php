<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConfirmController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $user = auth('api')->user();
        $code = $request->get('code');
        $verificationToken = $user->verification_token;

        if ($code !== $verificationToken) {
            return response()->json([
                'message' => 'Email was not confirmed'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'message' => 'Email verified'
        ], Response::HTTP_ACCEPTED);
    }
}
