<?php

namespace App\Http\Controllers;

use App\Models\InviteLink;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\Group;

class UserGroupController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'invite_link' => ['required', 'string', 'max:255']
        ]);

        $user = $request->user();
        $inviteLink = InviteLink::where('link', $request->input('invite_link'))->first();

        if (!$inviteLink) {
            return ApiHelper::errorResponse('Invite link not found', 404);
        }

        $group = Group::find($inviteLink->group_id);

        if ($user->groups()->where('group_id', $group->id)->exists()) {
            return ApiHelper::errorResponse('You are already a member of this group', 400);
        }

        $user->groups()->attach($group->id, ['role_id' => $group->roles()->where('name', 'Member')->first()->id]);

        return ApiHelper::successResponse(
            null,
            'User added to group successfully'
        );
    }

    public function delete(Request $request, $group_id)
    {
        $request->merge([
            'group_id' => $group_id
        ]);

        $request->validate([
            'group_id' => ['required', 'integer']
        ]);

        $user = $request->user();
        $group = $user->groups()->find($group_id);
        
        if (!$group) {
            return ApiHelper::errorResponse('Group not found', 404);
        }

        $user->groups()->detach($group_id);
        
        return ApiHelper::successResponse(
            null,
            'User removed from group successfully'
        );
    }
}
