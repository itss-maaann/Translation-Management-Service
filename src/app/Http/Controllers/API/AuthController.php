<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Auth",
 *   description="Authentication endpoints"
 * )
 */
class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService) {}

    /**
     * User login
     *
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email"),
     *       @OA\Property(property="password", type="string", format="password")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Login successful",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="token", type="string")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=401, description="Invalid credentials")
     * )
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
     * User logout
     *
     * @OA\Post(
     *   path="/api/logout",
     *   tags={"Auth"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Logged out successfully")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(
            message: 'Logged out successfully.'
        );
    }
}
