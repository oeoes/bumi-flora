<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UserManagementController extends Controller
{
    public function index () {
        $roles = Role::all();
        $permissions = Permission::all();
        $users = User::all();

        return view('pages.admin.access-roles', ['roles' => $roles, 'permissions' => $permissions, 'users' => $users]);
    }

    public function invite_user (Request $request) {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'role' => $request->role
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error: ' . $th->getMessage()], 400);
        }

        return response()->json(['status' => true, 'message' => 'Success: User added']);
    }

    public function create_role (Request $request) {
        Role::create(['name' => $request->role_name]);
        return back();
    }

    public function add_permission (Request $request) {
        Permission::create(['name' => $request->permission_name]);
        return back();
    }

    public function assign_role (User $user, Request $request) {
        if(count(User::role('root')->get()) > 0 && $request->assignrole == 'root')
            return back();

        $user->syncRoles($request->assignrole);
        return back();
    }

    public function assign_permission (Role $role, Request $request) {
        $role->syncPermissions($request->assignpermission);
        return back();
    }

    public function update_permission (Permission $permission, Request $request) {
        $permission->update(['name' => $request->permission_name]);
        return back();
    }

    public function update_role (Role $role, Request $request) {
        $role->update(['name' => $request->role_name]);
        return back();
    }

    public function destroy (User $id) {
        $id->delete();
        return back();
    }
}
