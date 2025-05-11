<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Response;
use App\Http\Middleware\TrustHosts;

class MiddlewareTest extends TestCase
{
    public function test_redirect_if_authenticated_allows_guest(): void
    {
        $middleware = new RedirectIfAuthenticated();
        $request = Request::create('/');
        $responseOrRequest = $middleware->handle($request, function ($req) {
            return new \Illuminate\Http\Response();
        });

        $this->assertInstanceOf(\Illuminate\Http\Response::class, $responseOrRequest);
    }

    public function test_trust_hosts_matches_wildcard(): void
    {
        $middleware = new TrustHosts($this->app);
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_HOST' => 'example.test']);
        $response = $middleware->handle($request, function () {
            return new \Illuminate\Http\Response();
        });
        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
    }
}
