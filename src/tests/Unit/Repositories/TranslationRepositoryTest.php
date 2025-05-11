<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use App\Repositories\TranslationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TranslationRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        Locale::factory()->create(['code' => 'en', 'name' => 'English']);
        Tag::factory()->create(['name' => 'web']);
        $this->repo = new TranslationRepository(new Translation());
    }

    public function test_paginate_returns_length_aware_paginator(): void
    {
        Translation::factory()->count(3)->create();
        $paginator = $this->repo->paginate(2);

        $this->assertEquals(2, $paginator->perPage());
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $paginator);
    }

    public function test_search_filters_results_by_key(): void
    {
        Translation::factory()->create(['key' => 'welcome.thanks']);
        Translation::factory()->create(['key' => 'abc.xyz']);

        $paginator = $this->repo->search('welcome', 10);
        $this->assertCount(1, $paginator->items());
    }
}
