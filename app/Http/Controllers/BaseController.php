<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{

    /**
     * Response for Http response 200
     *
     * @param string $message,
     * @param array|object $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse(string $message, array|object $data = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => 200,
            'data' => $data
        ]);
    }

    /**
     * Response for Http response 500
     *
     * @param string $message,
     * @param array|object $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message, array|object $data = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => 500,
            'data' => $data
        ], 500);
    }
}
