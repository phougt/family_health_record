<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;

class UserGroupPermissionController extends Controller
{
    public function index(Request $request, $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => 'required|integer',
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($group_id);
        if ($userPermissions->isEmpty()) {
            return ApiHelper::errorResponse('You are not in this group', 403);
        }

        return ApiHelper::successResponse(
            $userPermissions,
            'User group permissions retrieved successfully'
        );
    }
}
