<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecordLink;
use App\Helpers\ApiHelper;
use App\Helpers\LinkHelper;
use App\Models\InviteLink;

class InviteLinkController extends Controller
{
    public function index(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('invite-link.read')) {
            return ApiHelper::errorResponse('You do not have permission to view invite links in this group', 403);
        }

        $paginate = $request->input('page', 10);
        $inviteLinks = InviteLink::where('group_id', $request->group_id)
            ->paginate($paginate);

        return ApiHelper::successResponse(
            $inviteLinks,
            'Record links retrieved successfully'
        );
    }

    public function create(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('invite-link.create')) {
            return ApiHelper::errorResponse('You do not have permission to create invite links in this group', 403);
        }

        $link = LinkHelper::generateLinkString();

        while (InviteLink::where('link', $link)->exists() || RecordLink::where('link', $link)->exists()) {
            $link = LinkHelper::generateLinkString();
        }

        $inviteLink = InviteLink::create([
            'group_id' => $request->group_id,
            'link' => $link
        ]);

        return ApiHelper::successResponse(
            $inviteLink,
            'Invite link created successfully'
        );
    }

    public function delete(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        $inviteLink = InviteLink::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($inviteLink->group_id ?? 0);

        if (!$userPermissions->contains('invite-link.delete')) {
            return ApiHelper::errorResponse('You do not have permission to delete this invite link', 403);
        }

        $inviteLink->delete();

        return ApiHelper::successResponse([], 'Invite link deleted successfully');
    }
}
