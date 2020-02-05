<?php

namespace App\Http\Controllers;

use App\Ip;
use Illuminate\Http\Request;
use App\Client;

class IpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Ip::where('client_id', $request->id)->get();
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
            'ip' => "required|ipv4|unique:ips",
            'client_id' => 'required',
        ]);
        $ip = new Ip();
        $ip->create($request->all());
        return $ip;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ip  $ip
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return Ip::find($request->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ip  $ip
     * @return \Illuminate\Http\Response
     */
    public function edit(Ip $ip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ip  $ip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $ip = Ip::find($request->id);
        $ip->update($request->all());
        return $ip;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ip  $ip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ip $ip)
    {
        try {
            $ip->delete();
            return "Ip deleted successfully";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function clientsIps()
    {
        return $clientsIps = Ip::all();
    }
}
