<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::post('/sign-up', [App\Http\Controllers\AuthController::class, 'signup'])->name('sign-up');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [App\Http\Controllers\UserController::class, 'read'])->name('user.read');
        Route::put('/user', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
        Route::post('/user/{user_id}/group-role', [App\Http\Controllers\UserGroupRoleController::class, 'create'])->name('user-group-role.create');
        Route::delete('/user/group/{group_id}', [App\Http\Controllers\UserGroupController::class, 'delete'])->name('user-group.delete');
        Route::post('/invite-link', [App\Http\Controllers\UserGroupController::class, 'create'])->name('user-group.create');

        Route::get('/group', [App\Http\Controllers\GroupController::class, 'index'])->name('group.index');
        Route::get('/group/{group_id}', [App\Http\Controllers\GroupController::class, 'read'])->name('group.read');
        Route::delete('/group/{group_id}', [App\Http\Controllers\GroupController::class, 'delete'])->name('group.delete');
        Route::post('/group', [App\Http\Controllers\GroupController::class, 'create'])->name('group.create');
        Route::get('/group/{group_id}/group-profile', [App\Http\Controllers\GroupProfileController::class, 'read'])->name('group-profile.read');
        Route::get('/group/{group_id}/tag', [App\Http\Controllers\TagController::class, 'index'])->name('tag.index');
        Route::post('/group/{group_id}/tag', [App\Http\Controllers\TagController::class, 'create'])->name('tag.create');
        Route::get('/group/{group_id}/record-type', [App\Http\Controllers\RecordTypeController::class, 'index'])->name('record-type.index');
        Route::post('/group/{group_id}/record-type', [App\Http\Controllers\RecordTypeController::class, 'create'])->name('record-type.create');
        Route::get('/group/{group_id}/invite-link', [App\Http\Controllers\InviteLinkController::class, 'index'])->name('invite-link.index');
        Route::post('/group/{group_id}/invite-link', [App\Http\Controllers\InviteLinkController::class, 'create'])->name('invite-link.create');
        Route::get('/group/{group_id}/hospital', [App\Http\Controllers\HospitalController::class, 'index'])->name('hospital.index');
        Route::post('/group/{group_id}/hospital', [App\Http\Controllers\HospitalController::class, 'create'])->name('hospital.create');
        Route::get('/group/{group_id}/doctor', [App\Http\Controllers\DoctorController::class, 'index'])->name('doctor.index');
        Route::post('/group/{group_id}/doctor', [App\Http\Controllers\DoctorController::class, 'create'])->name('doctor.create');
        Route::get('/group/{group_id}/role', [App\Http\Controllers\GroupRoleController::class, 'index'])->name('group-role.index');
        Route::post('/group/{group_id}/role', [App\Http\Controllers\GroupRoleController::class, 'create'])->name('group-role.create');
        Route::get('/group/{group_id}/user', [App\Http\Controllers\GroupUserController::class, 'index'])->name('group-user.index');
        Route::get('/user/group/{group_id}/permission', [App\Http\Controllers\UserGroupPermissionController::class, 'read'])->name('user-group-permission.read');

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

        Route::get('/doctor/{id}', [App\Http\Controllers\DoctorController::class, 'read'])->name('doctor.read');
        Route::put('/doctor/{id}', [App\Http\Controllers\DoctorController::class, 'update'])->name('doctor.update');
        Route::delete('/doctor/{id}', [App\Http\Controllers\DoctorController::class, 'delete'])->name('doctor.delete');

        Route::get('/group-role/{id}', [App\Http\Controllers\GroupRoleController::class, 'read'])->name('group-role.read');
        Route::put('/group-role/{id}', [App\Http\Controllers\GroupRoleController::class, 'update'])->name('group-role.update');
        Route::delete('/group-role/{id}', [App\Http\Controllers\GroupRoleController::class, 'delete'])->name('group-role.delete');
        Route::get('/group-role/{group_role_id}/permission', [App\Http\Controllers\RolePermissionController::class, 'index'])->name('permission.index');
        Route::post('/group-role/{group_role_id}/permission', [App\Http\Controllers\RolePermissionController::class, 'create'])->name('permission.create');

        Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    });
});