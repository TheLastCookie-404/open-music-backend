<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    public function __invoke(Request $request, User $user) 
    {
        $request->validate([
            'password' => 'required|string|alpha_dash|between:6,12',
            'new_password' => 'required|string|alpha_dash|between:6,12|confirmed',
        ]);

        $password = $request->get('password');
        $newPassword = Hash::make($request->get('new_password'));

        $user = auth('api')->user(); 

        if (Hash::check($password, $newPassword)) {
            return response()->json([
                'message' => 'Password already used',
            ], Response::HTTP_CONFLICT);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Incorrect password',
            ], Response::HTTP_CONFLICT);
        }

        $user->query()->update([
            'password' => $newPassword
        ]);

        $user->save();
        
        return response()->json([
            'message' => 'User profile',
            'data' => auth('api')->user()
        ], Response::HTTP_OK);
    }
}
