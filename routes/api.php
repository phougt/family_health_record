<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/refresh-token', [App\Http\Controllers\AuthController::class, 'refreshToken']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    });
});