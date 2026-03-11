<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AuthController
{
    // Register new user
    public function index(Request $request)
    {
        $request->validate([
            'name' => 'required|string|between:1,255',
            'email' => 'required|string|email|between:5,255|unique:users',
            'password' => 'required|string|alpha_dash|between:6,12|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nickname' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully', 
            'user' => $user
        ], Response::HTTP_CREATED);
    }
}