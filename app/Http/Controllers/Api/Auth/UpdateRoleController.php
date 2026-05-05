<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateRoleController extends AuthController
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'user_id' => 'required|string|alpha_dash|between:6,64',
            'role' => 'required|in:user,admin,superadmin'
        ]);

        $userId = $request->get('user_id');
        $role = $request->get('role');

        Gate::authorize('update-role');

        $user = $user->whereId($userId);
        $userName = $user->value('name');
        $userRole = $user->value('role');

        try {
            Log::info("Current user`s role: $userRole, Incoming role: $role");

            if ($userRole === $role)
                return response()->json([
                    'message' => "$userName`s role was already applied"
                ]);

            $user->update([
                'role' => $role
            ]);

            return response()->json([
                'message' => "$userName`s role changed",
            ], Response::HTTP_ACCEPTED);

        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => "server error",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
