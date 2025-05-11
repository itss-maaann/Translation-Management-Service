<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_validation_errors(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept'        => 'application/json',
        ])->postJson('/api/translations', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['locale_id','key','value']);
    }

    public function test_update_validation_allows_partial(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $locale = Locale::factory()->create(['code' => 'en', 'name' => 'English']);
        Tag::factory()->create(['name' => 'web']);

        $translation = Translation::factory()
            ->create(['locale_id' => $locale->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Accept'        => 'application/json',
        ])->putJson("/api/translations/{$translation->id}", []);

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $translation->id);
    }
}
