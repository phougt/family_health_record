<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\GroupRole;

class GroupRoleController extends Controller
{
    public function index(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);
        if (!$userPermissions->contains('group-role.read')) {
            return ApiHelper::errorResponse('You do not have permission to view roles in this group', 403);
        }

        $paginate = $request->input('page', 10);
        $roles = GroupRole::where('group_id', $request->group_id)
            ->paginate($paginate);

        return ApiHelper::successResponse(
            $roles,
            'Roles retrieved successfully'
        );
    }

    public function create(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);
        if (!$userPermissions->contains('group-role.create')) {
            return ApiHelper::errorResponse('You do not have permission to create roles in this group', 403);
        }

        $role = GroupRole::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
        ]);

        return ApiHelper::successResponse(
            $role,
            'Role created successfully'
        );
    }

    public function update(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $role = GroupRole::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($role->group_id ?? 0);
        if (!$userPermissions->contains('group-role.update')) {
            return ApiHelper::errorResponse('You do not have permission to update this role', 403);
        }

        if ($role->is_owner || $role->name == 'Member') {
            return ApiHelper::errorResponse('You cannot update this role', 403);
        }

        $role->update([
            'name' => $request->filled('name') ? $request->name : $role->name,
        ]);

        return ApiHelper::successResponse(
            $role,
            'Role updated successfully'
        );
    }

    public function read(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $role = GroupRole::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($role->group_id ?? 0);
        if (!$userPermissions->contains('group-role.read')) {
            return ApiHelper::errorResponse('You do not have permission to view this role', 403);
        }

        return ApiHelper::successResponse(
            $role,
            'Role retrieved successfully'
        );
    }

    public function delete(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => 'integer|required',
        ]);

        $role = GroupRole::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($role->group_id ?? 0);
        if (!$userPermissions->contains('group-role.delete')) {
            return ApiHelper::errorResponse('You do not have permission to delete this role', 403);
        }

        if ($role->is_owner || $role->name == 'Member') {
            return ApiHelper::errorResponse('You cannot delete this role', 403);
        }

        $role->delete();

        return ApiHelper::successResponse(
            null,
            'Role deleted successfully'
        );
    }
}
