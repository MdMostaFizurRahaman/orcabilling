<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.currency.index');
    }

    public function currencies()
    {
        return $currencies = Currency::all();
    }

    public function dataTable()
    {
        $outputs = Currency::select(['id', 'name', 'symbol', 'ratio']);

        return DataTables::of($outputs)
            ->addColumn('delete', function($query){
                return '<button  data-id="'.$query->id.'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></button>';
            })
            ->addColumn('edit',function ($query) {
                return '<a  href="#currency" data-id="'.$query->id.'" class="btn btn-sm btn-info edit"><i class="fa fa-edit"></i></a>';
            })
           ->rawColumns(['edit', 'delete'])
            ->make(true);
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
            'name' => 'required|string|max:45|unique:currencies',
            'symbol' => 'required|string|max:5',
            'ratio' => 'required|max:45',
        ]);

        $currency = new Currency();
        $currency->name = strtoupper($request->name);
        $currency->symbol = $request->symbol;
        $currency->ratio = $request->ratio;
        $currency->save();
        return $currency;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $currency = Currency::find($request->id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $this->validate($request, [
            'name' => 'required|string|max:45|unique:currencies,name,'.$request->id,
            'symbol' => 'required|string|max:5',
            'ratio' => 'required|max:45',
        ]);

        $currency->name = strtoupper($request->name);
        $currency->symbol = $request->symbol;
        $currency->ratio = $request->ratio;
        $currency->save();
        return $currency;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        try {
            $currency->delete();
            return "Currency deleted successfully";
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
