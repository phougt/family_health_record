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
    public function create(Request $request, int $record_id)
    {
        $request->merge(['record_id' => $record_id]);
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
