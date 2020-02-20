<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
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

            $this->refreshApp();

       } catch (\Throwable $th) {
           return $th;
       }
    }

    public function permissions()
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

    public function refreshApp()
    {
        $configClear = Artisan::call('config:clear');
        $cacheClear = Artisan::call('cache:clear');
        $routeClear = Artisan::call('route:cache');
        // $viewClear = Artisan::call('view:cache');
        return true; //Return anything
    }
}
