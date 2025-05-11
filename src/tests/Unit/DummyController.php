<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class DummyController
{
    use ApiResponse;

    public function successDemo(): JsonResponse
    {
        return $this->success(['Majid' => 'Shahzeb'], 'OK', 201);
    }

    public function errorDemo(): JsonResponse
    {
        return $this->error('Fail', 400);
    }
}
