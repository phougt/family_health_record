<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;

class UserGroupPermissionController extends Controller
{
    public function read(Request $request, $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => 'required|integer',
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);
        if (!$userPermissions->contains('user-group-permission.read')) {
            return ApiHelper::errorResponse('You do not have permission to view group permissions', 403);
        }

        return ApiHelper::successResponse(
            $user->getPermissions($request->group_id),
            'User group permissions retrieved successfully'
        );
    }
}
