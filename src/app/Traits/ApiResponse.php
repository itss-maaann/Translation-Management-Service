<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed   $data       Array, JsonResource, or other JSON-serializable
     * @param  string  $message    Response message
     * @param  int     $statusCode HTTP status code
     */
    protected function success(
        mixed $data = [],
        string $message = 'Success',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message    Error message
     * @param  int     $statusCode HTTP status code
     */
    protected function error(
        string $message = 'Error',
        int $statusCode = 400
    ): JsonResponse {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
        ], $statusCode);
    }
}
