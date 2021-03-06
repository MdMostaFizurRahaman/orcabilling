<?php

namespace App\Http\Controllers;

use App\Rate;
use App\Exports\RatesExport;
use App\Imports\RatesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return view('pages.tariffname.rates-index')->with('tariffname_id', $id);
    }

    public function getRatesTable($id){

        $rates = $this->getTariffRates($id);

        return DataTables::of($rates)
            ->addColumn('delete', function($query){
                return '<button  data-id="'.$query->id.'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></button>';
            })
            ->addColumn('edit',function ($query) {
                return '<button   data-id="'.$query->id.'" class="btn btn-sm btn-info edit"><i class="fa fa-edit"></i></button>';
            })
            ->addColumn('view',function ($query) {
                return '<button   data-id="'.$query->id.'" class="btn btn-sm btn-success view"><i class="fa fa-binoculars"></i></button>';
            })
            ->rawColumns(['edit', 'delete', 'view'])
            ->make(true);
    }

    public function getTariffRates($id)
    {
        return $outputs = Rate::select([
            'id', 'description', 'prefix', 'voice_rate', 'grace_period', 'minimal_time',
            'resolution', 'rate_multiplier', 'from_hour', 'to_hour', 'effective_date'
            ])->where('tariffname_id', $id);
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
            'prefix' => 'required|string|unique:rates|max:191',
            'description' => 'required|string|max:191',
            'from_day' => 'required|between:0,5',
            'to_day' => 'required|between:1,6|greater_than:from_day',
            'from_hour' => 'required|max:6',
            'to_hour' => 'required|max:6|greater_than:from_hour',
            'voice_rate' => 'required|max:20',
            'grace_period' => 'required|max:11',
            'minimal_time' => 'required|max:6',
            'resolution' => 'required|max:6',
            'rate_multiplier' => 'required|max:20',
            'effective_date' => 'required|string|max:20',
        ]);

        $rate = Rate::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return Rate::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tariffId, $id)
    {
        $this->validate($request, [
            'prefix' => 'required|string|max:191|unique:rates,id,'.$id,
            'description' => 'required|string|max:191',
            'from_day' => 'required|between:0,5',
            'to_day' => 'required|between:1,6|greater_than:from_day',
            'from_hour' => 'required|max:6',
            'to_hour' => 'required|max:6|greater_than:from_hour',
            'voice_rate' => 'required|max:20',
            'grace_period' => 'required|max:11',
            'minimal_time' => 'required|max:6',
            'resolution' => 'required|max:6',
            'rate_multiplier' => 'required|max:20',
            'effective_date' => 'required|string|max:20',
        ]);

        $rate = Rate::find($id);
        $rate->update($request->all());
        return $rate;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        try {
            $rate->delete();
            return "Rate deleted successfully";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function export($tariff)
    {
        return (new RatesExport)->forTariff($tariff)->download('rates.xlsx');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xlsx',
        ]);

        $temp_path = $request->file('file')->store('temp');
        $storage_path = storage_path('app').'/'.$temp_path;
        $data = Excel::import(new RatesImport, $storage_path);

        if($data){
            return "File imported successfully";
        }else{
            throw new \ErrorException('An unexpected error occured!');
        }
    }


    public function download()
    {
        $file= public_path(). "/downloads/sample.xlsx";
        return response()->download($file);
    }
}
