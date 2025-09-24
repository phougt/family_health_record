<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = DB::table('permissions')
            ->get([
                'id',
                'name',
                'slug',
                'kind',
                'description',
            ]);

        return ApiHelper::successResponse(
            $permissions,
            'Permissions retrieved successfully'
        );
    }
}
