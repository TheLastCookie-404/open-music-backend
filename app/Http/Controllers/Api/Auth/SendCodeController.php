<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendCodeController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns,strict|between:5,255',
        ]);

        $email = $request->get('email');

        Mail::raw('Test!', function ($message) use ($email) {
            $message
                ->to($email)
                ->subject('Laravel');
        });
    }
}
