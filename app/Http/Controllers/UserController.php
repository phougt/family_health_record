<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function user(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return ApiHelper::errorResponse(
                'You can not access user information without authentication',
                401
            );
        }

        return ApiHelper::successResponse(
            $user,
            'User information retrieved successfully'
        );
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return ApiHelper::errorResponse(
                'You can not update user information without authentication',
            );
        }

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'confirm_password' => ['nullable', 'string', 'min:8', 'same:password'],
            'firstname' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'blood_type_id' => ['nullable', 'integer', 'exists:blood_types,id'],
        ]);

        $user->update([
            'name' => $request->filled('name') ? $request->name : $user->name,
            'email' => $request->filled('email') ? $request->email : $user->email,
            'firstname' => $request->filled('firstname') ? $request->firstname : $user->firstname,
            'lastname' => $request->filled('lastname') ? $request->lastname : $user->lastname,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            'blood_type_id' => $request->filled('blood_type_id') ? $request->blood_type_id : $user->blood_type_id,
        ]);

        return ApiHelper::successResponse(
            $user,
            'User information updated successfully'
        );
    }
}
