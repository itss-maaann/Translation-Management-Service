<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Export Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Key and TTL for caching the full JSON export of translations.
    |
    */

    'export_cache_key' => env('TRANSLATIONS_EXPORT_CACHE_KEY', 'translations:export'),

    // Time to live in seconds
    'export_ttl'       => env('TRANSLATIONS_EXPORT_TTL', 3600),

];
