<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Format response sukses yang seragam.
     */
    protected function successResponse(mixed $data = null, string $message = 'Operasi berhasil.', int $code = 200, array $meta = []): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (! empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $code);
    }

    /**
     * Format response error yang seragam.
     */
    protected function errorResponse(string $message = 'Terjadi kesalahan.', int $code = 400, mixed $errors = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }
}
