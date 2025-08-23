<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Storage;

class GroupProfileController extends Controller
{
    public function read(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $group = $user->groups()->find($request->group_id);
        if (!$group) {
            return ApiHelper::errorResponse('You do not have permission to view group profile', 403);
        }

        if (!$group->group_profile ||!Storage::disk('local')->exists($group->group_profile)) {
            return ApiHelper::errorResponse('Group profile not found', 404);
        }

        return Storage::download(
            $group->group_profile,
            $group->name . '_profile.' . pathinfo($group->group_profile, PATHINFO_EXTENSION)
        );
    }
}
