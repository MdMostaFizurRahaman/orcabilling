<?php

namespace App\Http\Controllers;

use DateTime;
use App\Call;
use App\Client;
use App\GateWay;
use App\FailedCall;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SimulationController extends Controller
{
    //
    public function getSimulate()
    {
        return view('pages.simulate.index');
    }

    public function simulate(Request $request)
    {
        $this->validate($request, [
            'client_ip' => 'required',
            'gateway' => 'required',
            'number' => 'required',
            'duration' => 'required',
        ]);
        $data = [];
        $data['Called'] = $request->number;
        $data['StartTime'] = \time();
        $data['EndTime'] = $data['StartTime'] + $request->duration;

        $client_id = DB::table('ips')->where('ip', '=', $request->client_ip)->value('client_id');
        $client = Client::where('id', $client_id)->first();
        $rate = $client->tariff->rate($data);
        return $rate;

    }

    public function test(){
        $client_id = '2';
        $duration = 12000;
        $data = [];
        $data['Called'] = '7189890175';
        $data['StartTime'] = \time();
        $data['EndTime'] = $data['StartTime'] + $duration;
        $client = Client::where('id', $client_id)->first();
        $rate = $client->tariff->rate($data);
        dump($rate);
    }
}
