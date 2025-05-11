<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class TranslationAuthorizationTest extends TestCase
{
    public function test_protected_routes_require_authentication(): void
    {
        $endpoints = [
            'GET'  => '/api/translations',
            'POST' => '/api/translations',
            'GET'  => '/api/translations/search?q=test',
            'GET'  => '/api/translations/export',
        ];

        foreach ($endpoints as $method => $uri) {
            $jsonMethod = strtolower($method) . 'Json';
            $response = $this->$jsonMethod($uri);
            $response->assertStatus(401);
        }
    }
}
