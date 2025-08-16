<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\Group;

class GroupUserController extends Controller
{
    public function index(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($group_id);
        if (!$userPermissions->contains('group-user.read')) {
            return ApiHelper::errorResponse('You do not have permission to view group users', 403);
        }

        $group = Group::find($group_id);
        $users = $group->users()
            ->with('roles', function ($query) use ($group) {
                $query->where('group_roles.group_id', $group->id);
            })
            ->paginate(
                $request->input('per_page', 10),
                ['users.username', 'users.email', 'users.firstname', 'users.lastname', 'users.phone', 'users.id'],
                'page',
                $request->input('page', 1)
            );

        return ApiHelper::successResponse(
            $users,
            'Group users retrieved successfully'
        );
    }
}
