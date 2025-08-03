<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/refresh-token', [App\Http\Controllers\AuthController::class, 'refreshToken']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['prefix' => 'group', 'as' => 'group.'], function () {
            Route::group(['prefix' => 'tag', 'as' => 'tag.'], function () {
                Route::get('/', [App\Http\Controllers\TagController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\TagController::class, 'store'])->name('store');
                Route::patch('/', [App\Http\Controllers\TagController::class, 'update'])->name('update');
                Route::delete('/{id}', [App\Http\Controllers\TagController::class, 'destroy'])->name('destroy');
            });
        });

        Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    });
});