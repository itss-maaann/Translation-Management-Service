<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Translation;
use App\Observers\TranslationObserver;
use Illuminate\Support\Facades\Cache;

class TranslationObserverTest extends TestCase
{
    public function test_saved_event_invalidates_cache(): void
    {
        Cache::shouldReceive('store->forget')
            ->once()
            ->with(config('translations.export_cache_key'));

        $observer = new TranslationObserver();
        $observer->saved(new Translation());
    }

    public function test_deleted_event_invalidates_cache(): void
    {
        Cache::shouldReceive('store->forget')
            ->once()
            ->with(config('translations.export_cache_key'));

        $observer = new TranslationObserver();
        $observer->deleted(new Translation());
    }
}
