<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Record;
use App\Helpers\LinkHelper;
use App\Models\RecordLink;

class RecordLinkController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('record-link.read')) {
            return ApiHelper::errorResponse('You do not have permission to view record links in this group', 403);
        }

        $paginate = $request->input('page', 10);
        $recordLinks = RecordLink::whereHas('record', function ($query) use ($request) {
            $query->where('group_id', $request->group_id);
        })->paginate($paginate);

        return ApiHelper::successResponse(
            $recordLinks,
            'Record links retrieved successfully'
        );
    }

    public function create(Request $request)
    {
        $request->validate([
            'record_id' => ['required', 'integer']
        ]);

        $record = Record::find($request->record_id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($record->group_id ?? 0);

        if (!$userPermissions->contains('record-link.create')) {
            return ApiHelper::errorResponse('You do not have permission to create record links in this group', 403);
        }

        $link = LinkHelper::generateLinkString();

        while (RecordLink::where('link', $link)->exists()) {
            $link = LinkHelper::generateLinkString();
        }

        $recordLink = RecordLink::create([
            'record_id' => $record->id,
            'link' => $link
        ]);

        return ApiHelper::successResponse(
            $recordLink,
            'Record link created successfully'
        );
    }

    public function read(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        $recordLink = RecordLink::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($recordLink->record->group_id ?? 0);

        if (!$userPermissions->contains('record-link.read')) {
            return ApiHelper::errorResponse('You do not have permission to view this record link', 403);
        }

        return ApiHelper::successResponse(
            $recordLink,
            'Record link retrieved successfully'
        );
    }

    public function delete(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        $recordLink = RecordLink::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($recordLink->record->group_id ?? 0);
        if (!$userPermissions->contains('record-link.delete')) {
            return ApiHelper::errorResponse('You do not have permission to invoke this record link', 403);
        }

        $recordLink->delete();

        return ApiHelper::successResponse(
            [],
            'Record link deleted successfully'
        );
    }
}
