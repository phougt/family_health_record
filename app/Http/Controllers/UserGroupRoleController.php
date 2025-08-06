<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\GroupRole;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class UserGroupRoleController extends Controller
{
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

        if (!$selfUserPermissions->contains('user-group-role.create')) {
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
            || (!$selfRole->is_owner && $targetUserRole->is_owner)
            || ($selfRole->is_owner && $groupRole->is_owner)
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
