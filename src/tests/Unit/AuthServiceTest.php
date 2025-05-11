<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthService;
use App\Repositories\EloquentUserRepository;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Mockery;

class AuthServiceTest extends TestCase
{
    public function test_login_with_valid_credentials_returns_token(): void
    {
        $hashed = Hash::make('Majid123');
        $user = Mockery::mock(User::class)->makePartial();
        $user->password = $hashed;

        $tokenObj = (object) ['plainTextToken' => 'fakeToken123'];
        $user->shouldReceive('createToken')
             ->once()
             ->with(Mockery::any())
             ->andReturn($tokenObj);

        $repo = Mockery::mock(EloquentUserRepository::class);
        $repo->shouldReceive('findByEmail')
             ->once()
             ->with('majid@gmail.com')
             ->andReturn($user);

        $service = new AuthService($repo);
        $token   = $service->login('majid@gmail.com', 'Majid123');

        $this->assertEquals('fakeToken123', $token);
    }

    public function test_login_with_invalid_credentials_throws_exception(): void
    {
        $this->expectException(AuthenticationException::class);

        $repo = Mockery::mock(EloquentUserRepository::class);
        $repo->shouldReceive('findByEmail')
             ->once()
             ->with('test@invalid.com')
             ->andReturnNull();

        $service = new AuthService($repo);
        $service->login('test@invalid.com', 'wrong');
    }
}
