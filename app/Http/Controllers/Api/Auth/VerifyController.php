<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('Auth')]
class VerifyController extends Controller
{
    public function __construct(
        protected EmailVerificationService $emailVerificationService
    ) {}

    /**
     * Verify user mail
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $code = $request->get('code');

        $this->emailVerificationService->verify($code);

        return response()->json([
            'message' => 'Email verified'
        ], Response::HTTP_ACCEPTED);
    }
}
