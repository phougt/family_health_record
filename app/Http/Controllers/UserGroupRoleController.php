<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\GroupRole;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class UserGroupRoleController extends Controller
{
    public function index(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $group = $user->groups()->find($group_id);

        if (!$group) {
            return ApiHelper::errorResponse('You are not a member of this group', 404);
        }

        $groupRole = $user->roles()
            ->with('permissions', function ($query) use ($group) {
                if ($group->is_archived) {
                    $query->whereIn('slug', [
                        'hospital.read',
                        'doctor.read',
                        'record-type.read',
                        'tag.read',
                        'group-user.read',
                    ]);
                }
            })
            ->where('user_groups.group_id', $group->id)
            ->first();

        return ApiHelper::successResponse(
            $groupRole,
            'User\'s role retrieved successfully'
        );
    }

    public function create(Request $request, int $user_id)
    {
        $targetUser = User::find($user_id);

        $request->merge(['user_id' => $user_id]);
        $request->validate([
            'group_role_id' => ['required', 'integer'],
            'user_id' => ['integer']
        ]);

        $groupRole = GroupRole::find($request->input('group_role_id'));
        $selfUser = $request->user();
        $selfUserPermissions = $selfUser->getPermissions($groupRole->group->id ?? 0);

        if (!$selfUserPermissions->contains('group-role.manage')) {
            return ApiHelper::errorResponse('You do not have permission to assign roles to users in this group', 403);
        }

        $targetUserIsInSameGroup = $targetUser->groups()
            ->where('user_groups.group_id', $groupRole->group_id)
            ->exists();
        $selfRole = $selfUser->roles()
            ->where('group_roles.group_id', $groupRole->group_id)
            ->first();
        $targetUserRole = $targetUser->roles()
            ->where('group_roles.group_id', $groupRole->group_id)
            ->first();

        if (
            !$targetUserIsInSameGroup
            || ($selfUser->id == $targetUser->id)
            || (!$selfRole->type == RoleType::OWNER && $targetUserRole->type == RoleType::OWNER)
            || ($selfRole->type == RoleType::OWNER && $groupRole->type == RoleType::OWNER)
        ) {
            return ApiHelper::errorResponse(
                'You do not have permission to assign roles to this user in this group',
                403
            );
        }

        $syncArr = $targetUser->roles()
            ->where('group_roles.group_id', $groupRole->group_id)
            ->sync([
                $groupRole->id => ['group_id' => $groupRole->group_id]
            ]);


        return ApiHelper::successResponse(
            $syncArr,
            'Role assigned successfully'
        );
    }
}
