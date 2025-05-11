<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService) {}

    /**
     * Handle a login request and return an access token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login(
            $request->validated()['email'],
            $request->validated()['password']
        );

        return $this->success(
            data: ['token' => $token],
            message: 'Login successful.'
        );
    }

    /**
     * Revoke the current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(
            message: 'Logged out successfully.'
        );
    }
}
