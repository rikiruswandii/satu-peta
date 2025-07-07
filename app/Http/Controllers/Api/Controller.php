<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    public function sendResponse(
        $data = [],
        ?string $message = '',
        $meta = [],
        ?int $code = 200,
    ): JsonResponse {
        $response = [
            'statusCode' => $code,
            'code' => 'OK',
            'message' => $message,
        ];

        if (count($data) > 0) {
            $response['data'] = $data;
        }

        if (count($meta) > 0) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    public function sendError(
        ?string $error = 'Permintaan Anda tidak dapat kami proses.',
        $errors = [],
        ?int $code = 400
    ): JsonResponse {
        $response = [
            'statusCode' => $code,
            'code' => constant("\App\Enums\HttpCode::E_$code"),
            'message' => $error,
        ];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
