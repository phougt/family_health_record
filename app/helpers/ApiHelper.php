<?php
namespace App\Helpers;

class ApiHelper
{
    public static function successResponse($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function errorResponse($message = 'Error', $code = 400, $errors = [])
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}