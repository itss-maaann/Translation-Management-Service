<?php

declare(strict_types=1);

namespace Tests\Unit\Console;

use Tests\TestCase;
use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeedTranslationsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_seeds_specified_number_of_records(): void
    {
        Locale::factory()->create(['code'=>'en','name'=>'English']);
        Tag::factory()->create(['name'=>'web']);

        $this->artisan('tms:seed-translations', ['count' => 50])
             ->assertExitCode(0);

        $this->assertDatabaseCount('translations', 50);
    }
}
