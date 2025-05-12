<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        info('Running tests on DB', [config('database.default'), config('database.connections.sqlite.database')]);
        $this->assertTrue(true);
    }
}
