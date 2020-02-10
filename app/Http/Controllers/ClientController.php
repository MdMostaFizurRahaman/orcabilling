<?php

namespace App\Http\Controllers;

use App\Client;
use App\Payment;
use App\TariffName;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.client.index');
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
            'username' => 'required|string|unique:clients',
            'password' => 'required|min:4|max:20',
            'tariff_id' => 'required',
            'credit' => 'required|max:20',
            'capacity' => 'required|max:20',
            'route_type' => 'required',
            'full_name' => 'required|string|max:45',
            'email' => 'required|email|unique:clients',
            'mobile' => 'required|max:20',
            'city' => 'required|max:45',
            'country' => 'required|max:45',
            'address' => 'required|max:255',
            'zip' => 'required|max:20',
        ]);

        $client = new Client();
        $client->create($request->all());
        return $client;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return Client::find($request->id);
    }

    /**
     * Display a list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clients(Request $request)
    {
        return Client::all();
    }

    /**
     * Display a datatable of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataTable()
    {
        $outputs = Client::select(['id', 'username', 'capacity', 'account_state', 'tariff_id']);

        return DataTables::of($outputs)
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
            ->addColumn('ip', function ($query) {
                return '<button data-id="' . $query->id . '" class="btn btn-sm btn-primary ip"><i class="fas fa-binoculars"></i> View</button>';
            })
            ->addColumn('payment', function ($query) {
                return '<button data-id="' . $query->id . '" class="btn btn-sm btn-success payment"><i class="fa fa-plus"></i> Add</button>';
            })
            ->addColumn('tariff', function ($query) {
                return '<a href="'.route('rate.index', $query->tariff_id).'" class="btn btn-sm btn-success tariff"><i class="fa fa-book"></i> ' . $query->tariff->name . '</a>';
            })
            ->rawColumns(['tariff', 'action', 'payment', 'ip'])
            ->make(true);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $this->validate($request, [
            'username' => 'required|string|unique:clients,username,' . $request->id,
            'password' => 'required|min:4|max:20',
            'tariff_id' => 'required',
            'credit' => 'required|max:20',
            'capacity' => 'required|max:20',
            'route_type' => 'required',
            'full_name' => 'required|string|max:45',
            'email' => 'required|email|unique:clients,email,' . $request->id,
            'mobile' => 'required|max:20',
            'city' => 'required|max:45',
            'country' => 'required|max:45',
            'address' => 'required|max:255',
            'zip' => 'required|max:20',
        ]);

        $client->update($request->all());
        return $client;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return "Client deleted successfully";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function paymentStore(Request $request)
    {
        $this->validate($request, [
            'client_id' => 'required',
            'type' => 'required',
            'description' => 'required|max:191',
            'balance' => 'required|max:20',
        ]);

        $client = Client::find($request->client_id);
        $actual_balance = 0;

        if ($request->type == 'payment') {
            $actual_balance = $client->account_state;
            $client->increment('account_state', $request->balance);
        } else {
            $actual_balance = $client->account_state;
            $client->decrement('account_state', $request->balance);
        }

        $payment = Payment::create([
            'client_id' => $request->client_id,
            'client_type' => 2,
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
