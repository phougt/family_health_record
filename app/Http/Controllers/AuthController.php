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

        $user->tokens()->delete();
        $accessTokenExpiry = now('UTC')->addDays(7);
        $refreshTokenExpiry = now('UTC')->addDays(14);
        $newAccessToken = $user->createToken('access_token', [], $accessTokenExpiry)->plainTextToken;
        $newRefreshToken = $user->createToken('refresh_token', [], $refreshTokenExpiry)->plainTextToken;

        return ApiHelper::successResponse(
            [
                'access_token' => $newAccessToken,
                'access_token_expiry' => $accessTokenExpiry,
                'refresh_token' => $newRefreshToken,
                'refresh_token_expiry' => $refreshTokenExpiry
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

        $user->tokens()->delete();
        $accessTokenExpiry = now('UTC')->addDays(7);
        $refreshTokenExpiry = now('UTC')->addDays(14);
        $newAccessToken = $user->createToken('access_token', [], $accessTokenExpiry)->plainTextToken;
        $newRefreshToken = $user->createToken('refresh_token', [], $refreshTokenExpiry)->plainTextToken;

        return ApiHelper::successResponse(
            [
                'access_token' => $newAccessToken,
                'access_token_expiry' => $accessTokenExpiry,
                'refresh_token' => $newRefreshToken,
                'refresh_token_expiry' => $refreshTokenExpiry
            ],
            'Login successfully'
        );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ApiHelper::successResponse([], 'Logout successful');
    }

    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $rawTokenParts = explode('|', $request->refresh_token);

        if (count($rawTokenParts) !== 2) {
            return ApiHelper::errorResponse('Refresh token is invalid', 401);
        }

        $id = $rawTokenParts[0];
        $inputToken = $rawTokenParts[1];

        $refreshToken = PersonalAccessToken::where('id', $id)
            ->where('expires_at', '>', now('UTC'))
            ->first();
        $isValid = hash('sha256', $inputToken) === $refreshToken?->token ?? '';

        if (!$isValid) {
            return ApiHelper::errorResponse('Refresh token is invalid', 401);
        }

        $user = $refreshToken->tokenable;
        $user->tokens()->delete();
        $accessTokenExpiry = now('UTC')->addDays(7);
        $refreshTokenExpiry = now('UTC')->addDays(14);
        $newAccessToken = $user->createToken('access_token', [], $accessTokenExpiry)->plainTextToken;
        $newRefreshToken = $user->createToken('refresh_token', [], $refreshTokenExpiry)->plainTextToken;

        return ApiHelper::successResponse(
            [
                'access_token' => $newAccessToken,
                'access_token_expiry' => $accessTokenExpiry,
                'refresh_token' => $newRefreshToken,
                'refresh_token_expiry' => $refreshTokenExpiry
            ],
            'Token refreshed successfully'
        );
    }
}
