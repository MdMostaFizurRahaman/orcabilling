<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Rate;
use App\TariffName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TariffNameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.tariffname.index');
    }

    public function tariffNames()
    {
        return TariffName::all();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function dataTable()
    {
        $outputs = TariffName::select(['id', 'name', 'created_by', 'currency_id']);

        return DataTables::of($outputs)
            ->addColumn('delete', function($query){
                return '<button  data-id="'.$query->id.'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></button>';
            })
            ->addColumn('edit',function ($query) {
                return '<button   data-id="'.$query->id.'" class="btn btn-sm btn-info edit"><i class="fa fa-edit"></i></button>';
            })
            ->addColumn('tariff',function ($query) {
                return '<a href="'.route('rate.index', $query->id).'"  data-id="'.$query->id.'" class="btn btn-sm btn-success tariff"><i class="fa fa-book"></i></a>';
            })
            ->addColumn('currency_id',function ($query) {
                return $query->currency->name;
            })
            ->addColumn('created_by',function ($query) {
                return $query->user->username;
            })
           ->rawColumns(['edit', 'delete', 'tariff'])
            ->make(true);
    }


    public function getCurrenciesName()
    {
        return Currency::all();
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
            'name' => 'required|string|max:45|unique:tariffnames',
            'currency_id' => 'required',
        ]);


        $tariffName = new TariffName();
        $tariffName->name = $request->name;
        $tariffName->currency_id = $request->currency_id;
        $tariffName->created_by = Auth::user()->id;
        $tariffName->save();
        return $tariffName;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TariffName  $tariffName
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $tariffName = TariffName::find($request->id);
    }


    public function update(Request $request, TariffName $tariffName)
    {
        $this->validate($request, [
            'name' => 'required|string|max:45|unique:tariffnames,name,'.$request->id,
            'currency_id' => 'required',
        ]);


        $tariffName->name = $request->name;
        $tariffName->currency_id = $request->currency_id;
        $tariffName->created_by = Auth::user()->id;
        $tariffName->save();
        return $tariffName;
    }


    public function destroy(TariffName $tariffName)
    {
        try {
            $tariffName->delete();
            return "Tariffname deleted successfully";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getRates ($id)
    {
        return view('pages.tariffname.rate')->with('tariffname_id', $id);
    }

}
