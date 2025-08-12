<?php
namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class ApiHelper
{
    public static function successResponse($data, $message = 'Success', $code = 200)
    {
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'message' => $message,
                'data' => $data->items(),
                'pagination' => [
                    'total' => $data->total(),
                    'count' => $data->count(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'next_page_url' => $data->nextPageUrl(),
                    'prev_page_url' => $data->previousPageUrl(),
                ]
            ], $code);
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function errorResponse($message = 'Error', $code = 400, $errors = null)
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}