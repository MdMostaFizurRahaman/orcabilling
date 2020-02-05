<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use App\User;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:45|unique:roles',
        ]);

        return Role::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRoles()
    {
        $outputs = DB::table('roles')->select(['id', 'name']);

        return DataTables::of($outputs)
            ->addColumn('delete', function($query){
                return '<button  data-id="'.$query->id.'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i> Delete</button>';
            })
            ->addColumn('edit',function ($query) {
                return '<a  href="#role" data-id="'.$query->id.'" data-name="'.$query->name.'" class="btn btn-sm btn-info edit"><i class="fa fa-edit"></i> Edit</a>';
            })
           ->rawColumns(['edit', 'delete'])
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:roles,name,'.$request->id,
        ]);

        $role->update($request->all());
        return $role;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return "Role deleted successfully";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getUsers()
    {
        return User::all();
    }

    public function getRolesName()
    {
        return Role::all();
    }

    public function assignRole(Request $request)
    {
        $this->validate($request, [
                'user' => 'required',
                'role' => 'required',
            ]
        );


        $user= User::find($request->user);
        return $user->assignRole($request->role);
    }
}
