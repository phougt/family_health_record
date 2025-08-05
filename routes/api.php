<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::post('/sign-up', [App\Http\Controllers\AuthController::class, 'signup'])->name('sign-up');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/refresh-token', [App\Http\Controllers\AuthController::class, 'refreshToken']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [App\Http\Controllers\UserController::class, 'read'])->name('user.read');
        Route::put('/user', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');

        Route::get('/group/{group_id}/tag', [App\Http\Controllers\TagController::class, 'index'])->name('tag.index');
        Route::post('/group/{group_id}/tag', [App\Http\Controllers\TagController::class, 'create'])->name('tag.create');
        Route::get('/group/{group_id}/record-type', [App\Http\Controllers\RecordTypeController::class, 'index'])->name('record-type.index');
        Route::post('/group/{group_id}/record-type', [App\Http\Controllers\RecordTypeController::class, 'create'])->name('record-type.create');
        Route::get('/group/{group_id}/invite-link', [App\Http\Controllers\InviteLinkController::class, 'index'])->name('invite-link.index');
        Route::post('/group/{group_id}/invite-link', [App\Http\Controllers\InviteLinkController::class, 'create'])->name('invite-link.create');
        Route::get('/group/{group_id}/hospital', [App\Http\Controllers\HospitalController::class, 'index'])->name('hospital.index');
        Route::post('/group/{group_id}/hospital', [App\Http\Controllers\HospitalController::class, 'create'])->name('hospital.create');

        Route::get('/tag/{id}', [App\Http\Controllers\TagController::class, 'read'])->name('tag.read');
        Route::put('/tag/{id}', [App\Http\Controllers\TagController::class, 'update'])->name('tag.update');
        Route::delete('/tag/{id}', [App\Http\Controllers\TagController::class, 'delete'])->name('tag.delete');

        Route::get('/record-type/{id}', [App\Http\Controllers\RecordTypeController::class, 'read'])->name('record-type.read');
        Route::put('/record-type/{id}', [App\Http\Controllers\RecordTypeController::class, 'update'])->name('record-type.update');
        Route::delete('/record-type/{id}', [App\Http\Controllers\RecordTypeController::class, 'delete'])->name('record-type.delete');

        Route::post('/record/{record_id}/record-link', [App\Http\Controllers\RecordLinkController::class, 'create'])->name('record-link.create');
        Route::delete('/record-link/{id}', [App\Http\Controllers\RecordLinkController::class, 'delete'])->name('record-link.delete');

        Route::delete('/invite-link/{id}', [App\Http\Controllers\InviteLinkController::class, 'delete'])->name('invite-link.delete');

        Route::get('/hospital/{id}', [App\Http\Controllers\HospitalController::class, 'read'])->name('hospital.read');
        Route::put('/hospital/{id}', [App\Http\Controllers\HospitalController::class, 'update'])->name('hospital.update');
        Route::delete('/hospital/{id}', [App\Http\Controllers\HospitalController::class, 'delete'])->name('hospital.delete');

        Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    });
});