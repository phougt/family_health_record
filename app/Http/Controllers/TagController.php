<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Helpers\ApiHelper;
use Closure;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('group.tag.view')) {
            return ApiHelper::errorResponse('You do not have permission to view tags in this group', 403);
        }

        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $paginate = $request->input('page', 10);

        $tags = Tag::where('group_id', $request->group_id)
            ->paginate($paginate);

        return ApiHelper::successResponse(
            $tags,
            'Tags retrieved successfully'
        );
    }

    public function read(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        $tag = Tag::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($tag->group_id ?? 0);

        if (!$userPermissions->contains('group.tag.read')) {
            return ApiHelper::errorResponse('You do not have permission to view this tag', 403);
        }

        $request->validate([
            'id' => 'required|exists:tags,id',
        ]);

        return ApiHelper::successResponse(
            $tag,
            'Tag retrieved successfully'
        );
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $userPermissions = $user->getPermissions($request->group_id ?? 0);

        if (!$userPermissions->contains('group.tag.create')) {
            return ApiHelper::errorResponse('You do not have permission to create tags in this group', 403);
        }

        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($request) {
                    $isExistedName = Tag::where('group_id', $request->group_id)
                        ->where('name', $value)->exists();

                    if ($isExistedName) {
                        $fail("The {$attribute} is already existed in this group.");
                    }
                },
            ],
            'color' => 'nullable|string|max:9',
        ]);


        $tag = Tag::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return ApiHelper::successResponse($tag, 'Tag created successfully');
    }

    public function update(Request $request)
    {
        $tag = Tag::find($request->id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($tag->group_id ?? 0);

        if (!$userPermissions->contains('group.tag.update')) {
            return ApiHelper::errorResponse('You do not have permission to update this tag', 403);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($request, $tag) {
                    $isExistedName = Tag::where('group_id', $tag->group_id ?? 0)
                        ->where('name', $value)
                        ->where('id', '!=', $request->id)
                        ->exists();

                    if ($isExistedName) {
                        $fail("The {$attribute} is already existed in this group.");
                    }
                },
            ],
            'color' => 'nullable|string|max:9',
        ]);


        $tag->update($request->only(['name', 'color']));

        return ApiHelper::successResponse($tag, 'Tag updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        $tag = Tag::find($id);
        $user = $request->user();
        $userPermissions = $user->getPermissions($tag->group_id ?? 0);

        if (!$userPermissions->contains('group.tag.delete')) {
            return ApiHelper::errorResponse('You do not have permission to delete this tag', 403);
        }

        $request->validate([
            'id' => 'required|exists:tags,id',
        ]);

        $tag->delete();

        return ApiHelper::successResponse([], 'Tag deleted successfully');
    }
}
