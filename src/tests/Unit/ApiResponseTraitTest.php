<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\JsonResponse;

class ApiResponseTraitTest extends TestCase
{
    public function test_success_response_structure(): void
    {
        $ctrl = new DummyController();
        $res  = $ctrl->successDemo();

        $this->assertInstanceOf(JsonResponse::class, $res);
        $this->assertEquals(201, $res->getStatusCode());
        $data = $res->getData(true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('OK', $data['message']);
        $this->assertEquals(['Majid' => 'Shahzeb'], $data['data']);
    }

    public function test_error_response_structure(): void
    {
        $ctrl = new DummyController();
        $res  = $ctrl->errorDemo();

        $this->assertInstanceOf(JsonResponse::class, $res);
        $this->assertEquals(400, $res->getStatusCode());
        $data = $res->getData(true);
        $this->assertEquals('error', $data['status']);
        $this->assertEquals('Fail', $data['message']);
    }
}
