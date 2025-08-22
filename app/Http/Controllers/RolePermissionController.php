<?php

namespace App\Http\Controllers;

use App\Models\GroupRole;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class RolePermissionController extends Controller
{
    public function index(Request $request, int $group_role_id)
    {
        $request->merge(['group_role_id' => $group_role_id]);
        $request->validate([
            'group_role_id' => ['required', 'integer'],
        ]);

        $groupRole = GroupRole::find($group_role_id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($groupRole->group->id ?? 0);
        if (!$userPermissions->contains('role-permission.read')) {
            return ApiHelper::errorResponse('You do not have permission to view permissions in this group', 403);
        }

        $permissions = DB::table('group_role_permissions')
            ->join('permissions', 'group_role_permissions.permission_id', '=', 'permissions.id')
            ->where('group_role_permissions.role_id', $groupRole->id)
            ->get(['permissions.id', 'permissions.name as name', 'permissions.description as description']);

        return ApiHelper::successResponse(
            $permissions,
            'Permissions retrieved successfully'
        );
    }

    public function create(Request $request, int $group_role_id)
    {
        $groupRole = GroupRole::find($group_role_id);

        $request->merge(['group_role_id' => $group_role_id]);
        $request->validate([
            'permission_ids' => ['required', 'array'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($groupRole->group->id ?? 0);
        $areValidInputIds = Permission::whereIn('id', $request->input('permission_ids'))->count()
            == count($request->input('permission_ids'));

        if (!$userPermissions->contains('role-permission.create') || !$areValidInputIds) {
            return ApiHelper::errorResponse('You do not have permission to assign permissions to role in this group', 403);
        }

        $selfRole = $user->roles()
            ->where('group_roles.group_id', $groupRole->group_id)
            ->first();
        $targetRole = $groupRole;

        if ((!$selfRole->is_owner && $targetRole->is_owner) || ($selfRole->id == $groupRole->id)) {
            return ApiHelper::errorResponse('You do not have permission to assign permissions to this role in this group', 403);
        }

        $syncArr = $groupRole->permissions()
            ->sync($request->input('permission_ids'));

        return ApiHelper::successResponse(
            $syncArr,
            'Permission assigned successfully'
        );
    }
}