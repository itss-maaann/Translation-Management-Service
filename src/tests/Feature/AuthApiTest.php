<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_endpoint_success(): void
    {
        $user = User::factory()->create(['password' => bcrypt('MajidSecret123')]);

        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'MajidSecret123',
        ]);

        $response->assertOk()
                 ->assertJsonStructure(['status','message','data' => ['token']]);
    }

    public function test_login_endpoint_failure(): void
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'noone@nowhere.com',
            'password' => 'Secret',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Invalid credentials',
                 ]);
    }

    public function test_logout_requires_authentication(): void
    {
        $response = $this->postJson('/api/logout');
        $response->assertUnauthorized();
    }

    public function test_logout_endpoint_success(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/logout');

        $response->assertOk()
                 ->assertJson(['status' => 'success']);
    }
}
