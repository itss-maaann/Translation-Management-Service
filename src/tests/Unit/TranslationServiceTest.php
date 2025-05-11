<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TranslationService;
use App\Repositories\TranslationRepository;
use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Mockery;

class TranslationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_list_calls_repository_paginate(): void
    {
        $repoMock = Mockery::mock(TranslationRepository::class);

        $items    = ['dummy'];
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            count($items),
            20,
            1
        );

        $repoMock->shouldReceive('paginate')
            ->once()
            ->with(20)
            ->andReturn($paginator);

        $service = new TranslationService($repoMock);
        $result  = $service->list(20);

        $this->assertSame(1, $result->total());
    }

    public function test_export_builds_and_caches_json(): void
    {
        Cache::store('redis')->forget('translations:export');

        $repoMock = Mockery::mock(TranslationRepository::class);
        $repoMock->shouldReceive('chunkForExport')
            ->once()
            ->andReturnUsing(function ($chunkSize, $callback) {
                $dummy = (object)[
                    'key'    => 'Majid',
                    'value'  => 'Test',
                    'locale' => (object)['code' => 'en'],
                    'tags'   => collect([(object)['name' => 'web']]),
                ];
                $callback(collect([$dummy]));
            });

        $service = new TranslationService($repoMock);

        /** @var \Symfony\Component\HttpFoundation\StreamedResponse $response */
        $response = $service->export();

        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('"Total Count":1', $output);
        $this->assertStringContainsString('"Majid"', $output);

        $cached = Cache::store('redis')->get('translations:export');
        $this->assertNotEmpty($cached);
    }
}
