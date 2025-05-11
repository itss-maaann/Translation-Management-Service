<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tag;
use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_performance(): void
    {
        Locale::factory()->create(['code' => 'en', 'name' => 'English']);
        Tag::factory()->create(['name' => 'web']);

        $count = 1000;
        Translation::factory()->count($count)->create();

        $user  = User::factory()->create();
        $token = $user->createToken('perf')->plainTextToken;

        $headers = [
            'Authorization' => "Bearer {$token}",
            'Accept'        => 'application/json',
        ];

        $this
            ->withHeaders($headers)
            ->get('/api/translations/export')
            ->assertOk();

        $start = hrtime(true);
        $response = $this
            ->withHeaders($headers)
            ->get('/api/translations/export');
        $end = hrtime(true);

        $response->assertOk();

        $timeMs = ($end - $start) / 1e6;
        $this->assertLessThan(
            500,
            $timeMs,
            "Cached export took {$timeMs} ms, expected <500 ms"
        );
    }
}
