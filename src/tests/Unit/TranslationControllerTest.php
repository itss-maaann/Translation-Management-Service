<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\API\TranslationController;
use App\Services\TranslationService;
use App\Http\Resources\TranslationResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\Translation;
use Mockery;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TranslationControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_uses_service_and_returns_json(): void
    {
        $paginator = new LengthAwarePaginator([], 0, 10);
        $service   = Mockery::mock(TranslationService::class);
        $service->shouldReceive('list')
                ->once()
                ->with(15)
                ->andReturn($paginator);


        $controller = new TranslationController($service);
        $response   = $controller->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('"status":"success"', $response->getContent());
    }

    public function test_show_returns_single_resource(): void
    {
        $translation = new Translation([
            'key'   => 'abc',
            'value' => 'xyz',
        ]);

        $translation->id         = 42;
        $translation->created_at = now();
        $translation->updated_at = now();

        $translation->setRelation('locale', (object)['id' => 1, 'code' => 'en']);
        $translation->setRelation('tags', collect());

        $service = Mockery::mock(TranslationService::class);
        $service->shouldReceive('getById')
                ->once()
                ->with(42)
                ->andReturn($translation);

        $controller = new TranslationController($service);
        $response   = $controller->show(42);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('"id":42', $response->getContent());
    }

    public function test_export_returns_streamed_response(): void
    {
        $service = Mockery::mock(TranslationService::class);
        $service->shouldReceive('export')
                ->once()
                ->andReturn(new StreamedResponse(fn() => null));

        $controller = new TranslationController($service);
        $response   = $controller->export();

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }
}
