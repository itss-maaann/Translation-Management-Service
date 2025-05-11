<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create(['password' => bcrypt('Majid123')]);
        $this->token = $user->createToken('test')->plainTextToken;

        Locale::factory()->create(['code' => 'en', 'name' => 'English']);
        Tag::factory()->create(['name' => 'web']);
    }

    public function test_index_returns_paginated_list(): void
    {
        $this->withHeaders($this->authHeader())
             ->postJson('/api/translations', [
                 'locale_id' => 1,
                 'key'       => 'Majid.Create',
                 'value'     => 'Majid Unit Test Translation',
                 'tags'      => [1],
             ])->assertCreated();

        $response = $this->withHeaders($this->authHeader())
                         ->getJson('/api/translations?per_page=2');

        $response->assertOk()
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'data'  => [['id','locale','key','value','tags']],
                'links',
                'meta'  => ['current_page','last_page','per_page','total'],
            ],
        ]);

        $this->assertGreaterThan(0, $response->json('data.meta.total'));
    }

    public function test_crud_and_search_and_export(): void
    {
        $response = $this->withHeaders($this->authHeader())
            ->postJson('/api/translations', [
                'locale_id' => 1,
                'key'       => 'Majid',
                'value'     => 'Asserted',
                'tags'      => [1],
            ]);
        $response->assertCreated()
                 ->assertJsonPath('data.key', 'Majid');

        $id = $response->json('data.id');

        $this->withHeaders($this->authHeader())
             ->getJson("/api/translations/{$id}")
             ->assertOk()
             ->assertJsonPath('data.value', 'Asserted');

        $this->withHeaders($this->authHeader())
             ->putJson("/api/translations/{$id}", ['value' => 'Updated'])
             ->assertOk()
             ->assertJsonPath('data.value', 'Updated');

        $this->withHeaders($this->authHeader())
             ->getJson('/api/translations/search?q=Updated')
             ->assertOk()
             ->assertJsonCount(1, 'data.data');

        Cache::store('array')->flush();
        $export = $this->withHeaders($this->authHeader())
                       ->get('/api/translations/export');
        $export->assertOk()
               ->assertHeader('Content-Type', 'application/json');

        $response = $export->baseResponse;
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('"Total Count":1', $content);

        $this->withHeaders($this->authHeader())
             ->delete("/api/translations/{$id}")
             ->assertNoContent();
    }

    private function authHeader(): array
    {
        return [
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/json',
        ];
    }
}
