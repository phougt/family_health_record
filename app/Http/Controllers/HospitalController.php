<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\Hospital;

class HospitalController extends Controller
{
    public function index(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('hospital.read')) {
            return ApiHelper::errorResponse('You do not have permission to view hospitals in this group', 403);
        }

        $paginate = $request->input('page', 10);
        $hospitals = Hospital::where('group_id', $request->group_id)
            ->paginate($paginate);

        return ApiHelper::successResponse(
            $hospitals,
            'Hospitals retrieved successfully'
        );
    }

    public function create(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('hospital.create')) {
            return ApiHelper::errorResponse('You do not have permission to create hospitals in this group', 403);
        }

        $hospital = Hospital::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
        ]);

        return ApiHelper::successResponse(
            $hospital,
            'Hospital created successfully'
        );
    }

    public function update(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $hospital = Hospital::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($hospital->group_id ?? 0);

        if (!$userPermissions->contains('hospital.update')) {
            return ApiHelper::errorResponse('You do not have permission to update this hospital', 403);
        }

        $hospital->update($request->only('name'));

        return ApiHelper::successResponse(
            $hospital,
            'Hospital updated successfully'
        );
    }

    public function delete(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        $hospital = Hospital::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($hospital->group_id ?? 0);

        if (!$userPermissions->contains('hospital.delete')) {
            return ApiHelper::errorResponse('You do not have permission to delete this hospital', 403);
        }

        $hospital->delete();

        return ApiHelper::successResponse(
            [],
            'Hospital deleted successfully'
        );
    }

    public function read(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        $hospital = Hospital::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($hospital->group_id ?? 0);
        if (!$userPermissions->contains('hospital.read')) {
            return ApiHelper::errorResponse('You do not have permission to view this hospital', 403);
        }

        return ApiHelper::successResponse(
            $hospital,
            'Hospital retrieved successfully'
        );
    }
}
