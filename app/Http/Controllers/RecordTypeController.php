<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\RecordType;

class RecordTypeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'group_id' => ['integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('record-type.read')) {
            return ApiHelper::errorResponse('You do not have permission to index record types in this group', 403);
        }

        $paginate = $request->input('page', 10);

        $recordTypes = RecordType::where('group_id', $request->group_id)
            ->paginate($paginate);

        return ApiHelper::successResponse(
            $recordTypes,
            'Record types retrieved successfully'
        );
    }

    public function create(Request $request)
    {
        $request->validate([
            'group_id' => ['integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('record-type.create')) {
            return ApiHelper::errorResponse('You do not have permission to create record types in this group', 403);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($request) {
                    $isExistedName = RecordType::where('group_id', $request->group_id ?? 0)
                        ->where('name', $value)->exists();

                    if ($isExistedName) {
                        $fail("The {$attribute} is already existed in this group.");
                    }
                }
            ],
            'group_id' => 'required|exists:groups,id',
        ]);

        $recordType = RecordType::create($request->only('name', 'group_id'));

        return ApiHelper::successResponse(
            $recordType,
            'Record type created successfully'
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'integer',
        ]);

        $recordType = RecordType::find($request->id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($recordType->group_id ?? 0);

        if (!$userPermissions->contains('record-type.update')) {
            return ApiHelper::errorResponse('You do not have permission to update this record type', 403);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($recordType) {
                    $isExistedName = RecordType::where('group_id', $recordType->group_id ?? 0)
                        ->where('name', $value)
                        ->where('id', '!=', $recordType->id)
                        ->exists();

                    if ($isExistedName) {
                        $fail("The {$attribute} is already existed in this group.");
                    }
                }
            ],
        ]);

        $recordType->update($request->only('name'));

        return ApiHelper::successResponse(
            $recordType,
            'Record type updated successfully'
        );
    }

    public function delete(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => 'integer',
        ]);

        $recordType = RecordType::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($recordType->group_id ?? 0);

        if (!$userPermissions->contains('record-type.delete')) {
            return ApiHelper::errorResponse('You do not have permission to delete this record type', 403);
        }

        $recordType->delete();

        return ApiHelper::successResponse([], 'Record type deleted successfully');
    }

    public function read(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['integer']
        ]);

        $recordType = RecordType::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($recordType->group_id ?? 0);

        if (!$userPermissions->contains('record-type.read')) {
            return ApiHelper::errorResponse('You do not have permission to view this record type', 403);
        }

        return ApiHelper::successResponse(
            $recordType,
            'Record type retrieved successfully'
        );
    }
}
