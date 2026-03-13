<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends AuthController
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

        try {
            $user->update([
                'role' => $role
            ]);

            return response()->json([
                'message' => "$userName`s role changed",
            ], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            Log::error($e);

        }
    }
}
