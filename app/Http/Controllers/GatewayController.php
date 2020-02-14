<?php

namespace App\Http\Controllers;

use App\Gateway;
use App\Payment;
use Carbon\Carbon;
use App\TariffName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.gateway.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gateways()
    {
        return $gateways = Gateway::all();
    }

    /**
     * Display a datatable of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataTable()
    {
        $outputs = Gateway::select(['id', 'name', 'ip', 'port', 'call_limit', 'media_proxy', 'tariff_id', 'account_state']);

        return DataTables::of($outputs)
            ->addColumn('balance', function ($query) {
                return $query->account_state;
            })
            ->addColumn('payment', function ($query) {
                return '<button   data-id="' . $query->id . '" class="btn btn-sm btn-success payment"><i class="fa fa-plus"></i> Add</button>';
            })
            ->addColumn('action', function ($query) {
                return '<div class="btn-group">
                            <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <a  data-id="' . $query->id . '" class="dropdown-item view" href="javascript:void(0)"><i class="fa fa-binoculars m-r-5"></i> View</a>
                                <a  data-id="' . $query->id . '" class="dropdown-item edit" href="javascript:void(0)"><i class="fa fa-edit m-r-5"></i> Edit</a>
                                <a  data-id="' . $query->id . '" class="dropdown-item delete" href="javascript:void(0)"><i class="fa fa-trash m-r-5"></i> Delete</a>
                            </div>
                        </div>';
            })
            ->editColumn('media_proxy', function ($query) {
                return $query->media_proxy ? 'Active' : 'Inactive';
            })
            ->addColumn('tariff', function ($query) {
                return '<a href="'.route('rate.index', $query->tariff_id).'" class="tariff"><i class="fa fa-book"></i> ' . $query->tariff->name . '</a>';
            })
            ->rawColumns(['tariff', 'payment', 'action'])
            ->make(true);
    }



    public function create()
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:gateways',
            'username' => 'required|string|max:191|unique:gateways',
            'password' => 'required|min:4|max:20',
            'ip' => 'required|max:20',
            'port' => 'required|max:6',
            'tariff_id' => 'required',
            'call_limit' => 'required|max:20',
        ]);

        $gateway = new Gateway();
        $gateway->create($request->all());
        return $gateway;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gateway  $gateway
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return Gateway::find($request->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gateway  $gateway
     * @return \Illuminate\Http\Response
     */
    public function edit(Gateway $gateway)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gateway  $gateway
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gateway $gateway)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:gateways,name,' . $request->id,
            'username' => 'required|string|max:191|unique:gateways,username,' . $request->id,
            'password' => 'required|min:4|max:20',
            'ip' => 'required|max:20',
            'port' => 'required|max:6',
            'tariff_id' => 'required',
            'call_limit' => 'required|max:20',
        ]);

        $gateway->update($request->all());
        return $gateway;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gateway  $gateway
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gateway $gateway)
    {
        try {
            $gateway->delete();
            return "Rate deleted successfully";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function paymentTypes()
    {
        return DB::table('payment_types')->get();
    }

    public function paymentStore(Request $request)
    {
        $this->validate($request, [
            'client_id' => 'required',
            'type' => 'required',
            'description' => 'required|max:191',
            'balance' => 'required|max:20',
        ]);

        $gateway = Gateway::find($request->client_id);
        $actual_balance = 0;

        if ($request->type == 'payment') {
            $actual_balance = $gateway->account_state;
            $gateway->increment('account_state', $request->balance);
        } else {
            $actual_balance = $gateway->account_state;
            $gateway->decrement('account_state', $request->balance);
        }

        $payment = Payment::create([
            'client_id' => $request->client_id,
            'client_type' => 100,
            'balance' => $request->balance,
            'date' => Carbon::today()->toDateString(),
            'type' => $request->type,
            'description' => $request->description,
            'actual_value' => $actual_balance,
            'user_id' => Auth::user()->id,
            'user_ip' => $request->ip(),
        ]);

        return $payment;
    }

    public function payment(Request $request)
    {
        return DB::table('payments')->where('client_id', $request->id)->where('client_type', $request->type)->latest()->limit(10)->get();
    }
}
