<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\Doctor;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DoctorController extends Controller
{
    public function index(Request $request, int $group_id)
    {
        $request->merge(['group_id' => $group_id]);
        $request->validate([
            'group_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);
        if (!$userPermissions->contains('doctor.read')) {
            return ApiHelper::errorResponse('You do not have permission to view doctors in this group', 403);
        }

        $doctors = Doctor::where('group_id', $request->group_id)
            ->paginate($request->input('per_page', 10),
            ['*'],
            'page',
            $request->input('page', 1)
        );

        return ApiHelper::successResponse(
            $doctors,
            'Doctors retrieved successfully'
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
        if (!$userPermissions->contains('doctor.create')) {
            return ApiHelper::errorResponse('You do not have permission to create doctors in this group', 403);
        }

        $doctor = Doctor::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
        ]);

        return ApiHelper::successResponse(
            $doctor,
            'Doctor created successfully'
        );
    }

    public function read(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        $doctor = Doctor::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($doctor->group_id ?? 0);
        if (!$userPermissions->contains('doctor.read')) {
            return ApiHelper::errorResponse('You do not have permission to view this doctor', 403);
        }

        return ApiHelper::successResponse(
            $doctor,
            'Doctor retrieved successfully'
        );
    }

    public function update(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $doctor = Doctor::find($request->id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($doctor->group_id ?? 0);
        if (!$userPermissions->contains('doctor.update')) {
            return ApiHelper::errorResponse('You do not have permission to update this doctor', 403);
        }

        $doctor->update([
            'name' => $request->filled('name') ? $request->name : $doctor->name,
        ]);

        return ApiHelper::successResponse(
            $doctor,
            'Doctor updated successfully'
        );
    }

    public function delete(Request $request, int $id)
    {
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        $doctor = Doctor::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($doctor->group_id ?? 0);
        if (!$userPermissions->contains('doctor.delete')) {
            return ApiHelper::errorResponse('You do not have permission to delete this doctor', 403);
        }

        $doctor->delete();
        
        return ApiHelper::successResponse(
            [],
            'Doctor deleted successfully'
        );
    }
}
