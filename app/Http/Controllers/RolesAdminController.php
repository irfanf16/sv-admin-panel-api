<?php

namespace App\Http\Controllers;



use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Requests\RolesFormRequest;
use App\Models\Role;

class RolesAdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('roles::admin.index');
    }
    public function usersList(Request $request): JsonResponse
    {
        $query = User::query();
        if($request->has('search') && !empty($request->search)){
            $query->whereRaw('( (CONCAT(first_name," ", last_name) LIKE "%'.strtolower($request->search).'%")
                OR (email LIKE "%'.strtolower($request->search).'%")
                )
            ');
        }
//        if($request->has('advocate_type')){
//            $query->leftJoin('role_types','role_types.role_id','=','portal')
//            $query->where('');
//        }


        if($request->has('active') ){
            $query->where('activated','=',$request->active);
        }

        $roles = $query->with('roles')->paginate($request->limit ?? config('settings.record_per_page'));
        return response()->json($roles);
    }
    public function rolesList(Request $request)
    {
        $query1 = Role::query()->where('guard_name', 'sanctum');

        if ($request->has('advocate_type') && $request->advocate_type == true) {
            $query1->join('role_types', 'role_types.role_id', '=', 'portal_roles.id')
                ->where('role_types.role_type', '=', '1');
        }

        $roles = $query1->pluck('name');

        $tempUsers = $roles->flatMap(function ($role) {
            return User::role($role, 'sanctum')->get();
        });



        return response()->json($tempUsers);
    }
    public function user($id = 0): JsonResponse
    {
        $user= User::find($id);
        return response()->json($user);
    }

    public function create(): View
    {
        $model = new Role();
        $model->checked_permissions = [];

        return view('roles::admin.create')->with(compact('model'));
    }

    public function edit(Role $role, $child = null)
    {
        $role->checked_permissions = $role->permissions()->pluck('name')->all();
        return $role;
        // return view('roles::admin.edit')
        //     ->with(['model' => $role]);
    }


    public function store(RolesFormRequest $request)
    {
        $checkedPermissions = $request->input('checked_permissions', []);
        $data = $request->except(['exit', 'checked_permissions']);

        // Store any new permissions under the correct guard
        $this->storeNewPermissions($checkedPermissions);  // Include guard if needed

        $role = Role::create($data);
        if(optional($request)->role_type){
            // Insert into the role_types table, linked by role_id
            $role->role_type()->create([
                'role_type' => $request->role_type
            ]);
        }

        $temp_array = [];
        foreach ($checkedPermissions as $permission) {
            $per = DB::table('portal_permissions')->where([ 'name' => $permission ,	'guard_name' => 'sanctum'])->first();

            if ($per != null) {
                array_push($temp_array, $permission);
            }
        }
        $role->givePermissionTo($temp_array);
        $role->syncPermissions($temp_array);

        return $role;
    }

    public function update(Role $role, RolesFormRequest $request)
    {
        $checkedPermissions = $request->input('checked_permissions', []);
        $data = $request->except(['exit', 'checked_permissions']);
        $role->update($data);

        $this->storeNewPermissions($checkedPermissions);
        $role->syncPermissions($checkedPermissions);
        $role->forgetCachedPermissions();

        return $role;
    }

    private function storeNewPermissions($permissions)
    {
        foreach ($permissions as $name) {
            $permission = DB::table('portal_permissions')->where([ 'name' => $name ,	'guard_name' => 'sanctum'])->first();

            if ($permission == null) {
                DB::table('portal_permissions')->insert([
                    'name' => $name,
                    'guard_name' => 'sanctum',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

        }
    }

//    private function storeNewPermissions($permissions)
//    {
//        foreach ($permissions as $name) {
//            Permission::firstOrCreate(['name' => $name]);
//        }
//    }



}
