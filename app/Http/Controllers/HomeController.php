<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // Client home
    public function index()
    {
        return view('home');
    }

    // Admin home
    public function clientHome(){

        return view('home');
    }


    public function getCountries()
    {
        return DB::table('countries')->get();
    }

    public function getPaymentTypes()
    {
        return DB::table('payment_types')->get();
    }
}
