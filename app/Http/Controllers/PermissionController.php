<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.permission.index');
    }

    public function assignPermissions(Request $request)
    {
        $this->validate($request,[
            'user' => 'required_without:role',
            'role' => 'required_without:user',
            'checkedPermissions' =>'required'
        ]);

       try {
            if(!empty($request->user)){
                $user = User::find($request->user);
                $permissions = $request->checkedPermissions;
                $user->syncPermissions($permissions);
            }

            if(!empty($request->role)){
                $role = Role::findByName($request->role);
                $permissions = $request->checkedPermissions;
                $role->syncPermissions($permissions);
            }
       } catch (\Throwable $th) {
           return $th;
       }


    }

    public function getPermissions()
    {
        return Permission::all();
    }

    public function getUserPermissions(Request $request)
    {
        $user = User::find($request->id);
        return $user->getAllPermissions()->pluck('name');
    }

    public function getRolePermissions(Request $request)
    {
        $role = Role::findByName($request->role);
        return $role->getAllPermissions()->pluck('name');
    }
}
