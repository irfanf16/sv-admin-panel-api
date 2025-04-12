<?php

namespace App\Http\Controllers;

use App\Models\RoleTypes;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FilterOr;
use App\Models\Role;

class RolesApiController extends BaseApiController
{
    public function index(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Role::class)
            ->allowedSorts(['name'])
            ->allowedFilters([
                AllowedFilter::custom('name', new FilterOr()),
            ])
            ->with('permissions')
            ->with('role_type')
            ->paginate($request->input('per_page'));

        return $data;
    }

    public function destroy(Role $role)
    {
        //delete roletype relation first (custom build functionality)
        \DB::table('role_types')->where('role_id',$role->id)->delete();

        $role->delete();
        return $role;
    }
}
