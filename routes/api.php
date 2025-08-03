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
                Route::get('/{id}', [App\Http\Controllers\TagController::class, 'read'])->name('read');
                Route::post('/', [App\Http\Controllers\TagController::class, 'create'])->name('create');
                Route::patch('/', [App\Http\Controllers\TagController::class, 'update'])->name('update');
                Route::delete('/{id}', [App\Http\Controllers\TagController::class, 'delete'])->name('delete');
            });

            Route::group(['prefix' => 'record-type', 'as' => 'record-type.'], function () {
                Route::get('/', [App\Http\Controllers\RecordTypeController::class, 'index'])->name('index');
                Route::get('/{id}', [App\Http\Controllers\RecordTypeController::class, 'read'])->name('read');
                Route::post('/', [App\Http\Controllers\RecordTypeController::class, 'create'])->name('create');
                Route::patch('/', [App\Http\Controllers\RecordTypeController::class, 'update'])->name('update');
                Route::delete('/{id}', [App\Http\Controllers\RecordTypeController::class, 'delete'])->name('delete');
            });
        });

        Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    });
});