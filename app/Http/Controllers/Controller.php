<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function responseSuccess(string $message, $data = null) : JsonResponse {
        $data = $data ? ['data' => $data] : [];

        return response()->json([
            'status' => 'success',
            'message' => $message,
            ...$data
        ]);
    }

    public function responseError(?string $message = null) : JsonResponse {
        $message = $message ?? 'Xảy ra lỗi không xác định';

        return response()->json([
            'status' => 'error',
            'message' => $message
        ]);
    }
}
