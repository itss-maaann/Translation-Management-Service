<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\EloquentUserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        private EloquentUserRepository $users
    ) {}

    /**
     * @throws AuthenticationException
     */
    public function login(string $email, string $password): string
    {
        $user = $this->users->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        return $user->createToken(Str::random(10))->plainTextToken;
    }
}
