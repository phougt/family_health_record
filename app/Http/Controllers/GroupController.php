<?php

namespace App\Http\Controllers;

use App\Models\GroupRole;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\Group;
use App\Models\Permission;
use App\Enums\RoleType;
use Illuminate\Support\Facades\Hash;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $groups = $user->groups()
            ->where('is_archived', $request->input('is_archived', false))
            ->orderByDesc('created_at')
            ->paginate(
                $request->input('per_page', 10),
                ['*'],
                'page',
                $request->input('page', 1)
            );

        foreach ($groups as $group) {
            $group->group_profile = $group->group_profile != null ? route('group-profile.read', ['group_id' => $group->id]) : null;
        }

        return ApiHelper::successResponse(
            $groups,
            'Groups retrieved successfully'
        );
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
            'group_profile' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:1048'],
        ]);

        $groupProfileName = null;

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
            'is_archived' => false,
        ]);

        $ownerRole = $group->roles()->create([
            'name' => 'Owner',
            'group_id' => $group->id,
            'type' => RoleType::OWNER
        ]);

        $memberRole = $group->roles()->create([
            'name' => 'Member',
            'group_id' => $group->id,
            'type' => RoleType::MEMBER
        ]);

        $ownerRole->permissions()->attach(
            Permission::all()->pluck('id')->toArray()
        );

        $memberRole->permissions()->attach(Permission::whereIn('slug', [
            'hospital.read',
            'doctor.read',
            'record-type.read',
            'tag.read',
            'invite-link.read',
            'record-link.read',
            'group-user.read',
        ])->pluck('id')->toArray());

        $user->groups()->attach($group->id, [
            'role_id' => $ownerRole->id
        ]);

        $group->group_profile = route('group-profile.read', ['group_id' => $group->id]);

        return ApiHelper::successResponse(
            $group,
            'Group created successfully'
        );
    }

    public function update(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
            'name' => ['nullable', 'string', 'max:255', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
            'group_profile' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:1048'],
        ]);

        $user = $request->user();
        $permission = $user->getPermissions($group_id);
        if (!$permission->contains('group.update')) {
            return ApiHelper::errorResponse('You do not have permission to update this group', 403);
        }

        $groupProfileName = null;

        if ($request->hasFile('group_profile')) {
            $oldProfile = $request->user()->groups()->find($group_id)->group_profile;
            if (Storage::disk('local')->exists($oldProfile ?? '')) {
                Storage::disk('local')->delete($oldProfile ?? '');
            }

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

        $group = Group::find($group_id);
        $group->name = $request->name ?? $group->name;
        $group->description = $request->description ?? $group->description;
        $group->group_profile = $groupProfileName ?? $group->group_profile;
        $group->save();

        if ($group->group_profile != null) {
            $group->group_profile = route('group-profile.read', ['group_id' => $group->id]);
        }

        return ApiHelper::successResponse(
            $group,
            'Group updated successfully'
        );
    }

    public function read(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $group = $user->groups()->find($group_id);
        if (!$group) {
            return ApiHelper::errorResponse('You do not have permission to view this group', 403);
        }

        $group = $user->groups()->find($group_id);

        if ($group->group_profile != null) {
            $group->group_profile = route('group-profile.read', ['group_id' => $group->id]);
        }

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
        $isOwner = $user->roles()
            ->where('group_roles.group_id', $group_id)
            ->where('type', RoleType::OWNER)
            ->exists();

        if (!$isOwner) {
            return ApiHelper::errorResponse('You do not have permission to archive this group', 403);
        }

        $group = $user->groups()->find($group_id);
        $group->is_archived = true;
        $group->save();

        return ApiHelper::successResponse(
            null,
            'Group deleted successfully'
        );
    }
}
