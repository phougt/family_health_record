<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $groups = $user->groups()->paginate($request->input('page', 10));

        return ApiHelper::successResponse(
            $groups,
            'Groups retrieved successfully'
        );
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'group_profile' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:1048'],
        ]);

        $groupProfileName = "";

        if ($request->hasFile('group_profile')) {
            $file = $request->file('group_profile');
            $path = $uploadStatus = $file->store(
                'group_profiles',
                [
                    'disk' => 'local',
                    'visibility' => 'private',
                ]
            );

            if (!$uploadStatus) {
                return ApiHelper::errorResponse('Failed to store group profile image', 500);
            }

            $groupProfileName = $path;
        }

        $user = $request->user();
        $group = Group::create([
            'name' => $request->name ?? '',
            'description' => $request->description ?? '',
            'group_profile' => $groupProfileName,
        ]);

        $ownerRole = $group->roles()->create([
            'name' => 'Owner',
            'is_owner' => true,
            'group_id' => $group->id,
        ]);

        foreach (config('permissions.groupOwner') as $permissionPrefixs) {
            foreach ($permissionPrefixs as $permission) {
                $tempPermission = Permission::create([
                    'group_id' => $group->id,
                    'slug' => $permission[0],
                    'name' => $permission[1],
                    'description' => $permission[2]
                ]);

                $ownerRole->permissions()->attach($tempPermission->id);
            }
        }

        $user->groups()->attach($group->id, [
            'role_id' => $ownerRole->id
        ]);

        $group->group_profile = route('group-profile.read', ['group_id' => $group->id]);

        return ApiHelper::successResponse(
            $group,
            'Group created successfully'
        );
    }

    public function read(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($group_id ?? 0);
        if (!$userPermissions->contains('group.read')) {
            return ApiHelper::errorResponse('You do not have permission to view this group', 403);
        }

        $group = $user->groups()->find($group_id);
        $group->group_profile = route('group-profile.read', ['group_id' => $group->id]);

        return ApiHelper::successResponse(
            $group,
            'Group details retrieved successfully'
        );
    }

    public function delete(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($group_id);
        if (!$userPermissions->contains('group.delete')) {
            return ApiHelper::errorResponse('You do not have permission to delete this group', 403);
        }

        $group = $user->groups()->find($group_id);
        $group->delete();

        return ApiHelper::successResponse(
            null,
            'Group deleted successfully'
        );
    }
}
