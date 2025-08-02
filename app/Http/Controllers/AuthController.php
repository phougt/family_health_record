<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiHelper;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where(['username' => $request->username])->first();

        if (!Hash::check($request->password, $user->password)) {
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
                'access_token_expiry' => $accessTokenExpiry->toDateTimeString(),
                'refresh_token' => $newRefreshToken,
                'refresh_token_expiry' => $refreshTokenExpiry->toDateTimeString()
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

        $refreshToken = PersonalAccessToken::where('id', explode('|', $request->refresh_token)[0] ?? '')
            ->where('expires_at', '>', now('UTC'))
            ->first();

        if (!$refreshToken) {
            return ApiHelper::errorResponse('No refresh token found', 401);
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
                'access_token_expiry' => $accessTokenExpiry->toDateTimeString(),
                'refresh_token' => $newRefreshToken,
                'refresh_token_expiry' => $refreshTokenExpiry->toDateTimeString()
            ],
            'Token refreshed successfully'
        );
    }
}
