<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFormPortalRequest;
use App\Services\Base64FileUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
class UsersAdminController extends BaseAdminController
{
    public function index(): View
    {
        return view('users::admin.index');
    }

    public function export(Request $request)
    {
        $filename = date('Y-m-d').' '.config('app.name').' users.xlsx';
        return Excel::download(new UsersExport(), $filename);
    }

    public function create(): View
    {
        $model = new User();
        $model->checked_roles = [];
        $roles = Role::get();

        return view('users::admin.create')
            ->with(compact('model', 'roles'));
    }

    public function edit(User $user)
    {
        $user->checked_roles = $user->roles()->pluck('id')->all();
        $roles = Role::get();
        return ['model' => $user, 'roles' => $roles];
    }


    public function updateStatus(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:portal_users,id',
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }


        // Update the user status
        $updated = User::where('id', $request->id)->update(['activated' => $request->status]);

        if ($updated) {
            return response()->json(['success' => 'User status updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to update user status'], 500);
        }
    }
    public function store(UsersFormPortalRequest $request, Base64FileUploader $fileUploader)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->input('password'));
        $data['email_verified_at'] = Carbon::now();
        $data['api_token'] = Str::uuid();
        if(isset($data['image']) && !empty($data['image'])){
            $url = $fileUploader->handle($data["image"]);
            $data['image'] = url($url);
        }

        $user = User::create($data);
        $user->roles()->sync($request->input('checked_roles', []));
        return ['request' => $request, 'user' => $user];
        // return $this->redirect($request, $user);
    }

    public function update(User $user, UsersFormPortalRequest $request,Base64FileUploader $fileUploader)
    {

        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        } else {
            unset($data['password']);
        }
        if(isset($data['image']) && !empty($data['image'])){
            $oldImagePath = public_path('images/' . $user->image);
            // Check if the old image file exists and delete it
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $url = $fileUploader->handle($data["image"]);
            $data['image'] = url($url);
        }

        //no need id attribute after validation checked now update request initiated and id should not update
        unset($data['id']);

        $user->update($data);
        $user->roles()->sync($request->input('checked_roles', []));
        (new Role())->flushCache();
        return ['request' => $request, 'user' => $user];
    }
    public function destroy($user_id)
    {
        $user = User::find($user_id);
        $user->roles()->detach();
        $user->delete();
        return response()->json('success');
    }
}
