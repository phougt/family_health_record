<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiHelper;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
            'email' => 'required|email|unique:users,email',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
            'email' => $request->email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
        ]);

        $accessTokenExpiry = now('UTC')->addDays(7);
        $newAccessToken = $user->createToken('access_token', [], $accessTokenExpiry)->plainTextToken;

        return ApiHelper::successResponse(
            [
                'access_token' => $newAccessToken,
                'access_token_expiry' => $accessTokenExpiry,
            ],
            'User registered successfully'
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where(['username' => $request->username])->first();

        if (!Hash::check($request->password, $user->password ?? '')) {
            return ApiHelper::errorResponse('Invalid credentials', 401);
        }

        $accessTokenExpiry = now('UTC')->addDays(7);
        $newAccessToken = $user->createToken('access_token', [], $accessTokenExpiry)->plainTextToken;

        return ApiHelper::successResponse(
            [
                'access_token' => $newAccessToken,
                'access_token_expiry' => $accessTokenExpiry,
            ],
            'Login successfully'
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiHelper::successResponse([], 'Logout successful');
    }
}
