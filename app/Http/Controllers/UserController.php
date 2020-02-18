<?php

namespace App\Http\Controllers;

use Alert;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.user.index');
    }

    public function dataTable()
    {
        $outputs = User::select(['id', 'name', 'username', 'email']);

        return DataTables::of($outputs)
            ->addColumn('delete', function($query){
                return '<a href="'.url("users/delete", $query->id) .'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i> Delete</a>';
            })
            ->addColumn('edit',function ($query) {
                return '<a href="'.url("users", $query->id) .'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>';
            })
           ->rawColumns(['edit', 'delete'])
            ->make(true);
    }

    public function users()
    {
        return User::all();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.user.create');
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
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users',
            'email' => 'required|string|max:191|unique:users',
            'password' => 'required|string|max:20|min:4',
            'phone' => 'required|max:20|min:10',
            'address' => 'required|max:192',
            'city' => 'required|string|max:45',
            'zipcode' => 'required|string|max:45',
            'country' => 'required|string|max:45',
        ]);

        return User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'country' => $request->country,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('pages.user.edit')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users,username,'.$request->id,
            'email' => 'required|string|max:191|unique:users,email,'.$request->id,
            'phone' => 'required|max:20|min:10',
            'address' => 'required|max:192',
            'city' => 'required|string|max:45',
            'zipcode' => 'required|string|max:45',
            'country' => 'required|string|max:45',
        ]);

        // $request->merge(['password' => Hash::make($request->password)]);
        // $client->update($request->except('password'));
        $user->update($request->all());
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->delete()){
            return redirect('/users')
                        ->with('success','User deleted successfully');
        }else{
            return redirect('/users')
                            ->with('error','Cannot delete user');
        }
    }

        // User functions
        public function showPasswordForm()
        {
            return view('auth.passwords.change');
        }

        public function changePassword(Request $request)
        {
            $this->validate($request, [
                //  'old_password' => 'required',
                 'password' => 'required|string|min:6|confirmed'
            ]);

            $user = $request->user();

            // if (Hash::check($request->old_password, $user->password)) {
                $updatePassword = $user->update(['password' => Hash::make($request->password)]);
                if ($updatePassword) {
                    Alert::success('Success', 'Password has been updated successfully');
                    return redirect()->back()->with('status', 'Password has been updated successfully');
                } else {
                    Alert::warning('Oops..!', 'Something went wrong!');
                    return redirect()->back()->with('warning', 'Oops..! Something went wrong!');
                }
            // } else {
            //     Alert::warning('Oops..!', 'Please, make sure you provide the right info!');
            //     return redirect()->back()->with('warning', 'Please, make sure you provide the right info!', 'Oops..!');

            // }
        }
}
