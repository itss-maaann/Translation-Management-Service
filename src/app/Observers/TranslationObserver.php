<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use App\Services\TranslationService;

class TranslationObserver
{
    /**
     * Handle events after a translation is created or updated.
     */
    public function saved(Translation $translation): void
    {
        Cache::store('redis')->forget(config('translations.export_cache_key'));
    }

    /**
     * Handle events after a translation is deleted.
     */
    public function deleted(Translation $translation): void
    {
        Cache::store('redis')->forget(config('translations.export_cache_key'));
    }
}
