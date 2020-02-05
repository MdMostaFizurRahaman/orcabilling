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

class ParseLog extends Controller
{
    public function parse($file = "../cdr.log", $file_name = "cdr_2019-12-31_09-50-00.log.gz")
    {
        $file = fopen($file, "r");

        $rowsCount = 0;

        while (!feof($file)) {

            $line = \fgets($file);

            $line = \str_replace("'", "", $line);

            $convert_to_array = explode(',', $line);

            $end_array = array();

            $rowsCount++;

            for ($i = 0; $i < count($convert_to_array); $i++) {
                $key_value = explode('=', $convert_to_array[$i]);
                $end_array[$key_value[0]] = !empty($key_value[1]) ? $key_value[1] : null;
            }

            if (isset($end_array['SipIP']))
            {
                $end_array['SipIP'] = str_replace([' ', '\r\n', "\r", "\n"], "", $end_array['SipIP']);

                if($end_array['Direction'] == 'answer')
                {
                    $client_id = DB::table('ips')->where('ip', '=', $end_array['SipIP'])->value('client_id');
                    if($end_array['client'] = Client::find($client_id))
                    {
                        $end_array['client_rate'] = $end_array['client']->tariff->rate($end_array);
                    } else {
                        $this->saveCdrReport($file_name, $rowsCount, $msg = "Client not found!");
                    }
                } elseif($end_array['Direction'] == 'originate') {

                    if($end_array['gateway'] = GateWay::where('ip', $end_array['SipIP'])->first())
                    {
                        $end_array['gateway_rate'] = $end_array['gateway']->tariff->rate($end_array);

                    } else {
                        $this->saveCdrReport($file_name, $rowsCount, $msg = "Gateway not found!");
                    }
                }

                if ($end_array["Duration"] > 0) {
                    try {
                        $call = $this->insertIntoCalls($end_array);

                        $this->checkAndUpdateCdrReport($file_name, $rowsCount);

                    } catch (\Exception $e) {
                        // take line number and error msg and push into raw_process table
                        $this->saveCdrReport($file_name, $rowsCount, $msg = $e->getMessage());
                    }
                } else {
                    try {
                        $this->insertIntoFailedCalls($end_array);
                    } catch (\Exception $e) {
                        // take line number and error msg and push into raw_process table
                        $this->saveCdrReport($file_name, $rowsCount, $msg = $e->getMessage());
                    }
                }
            }
        }

        fclose($file);
        // dump((microtime(true) - $startTime) / 1000) . 'mili seconds';
        return $rowsCount;
    }

    public function insertIntoFailedCalls($data)
    {
        // Insert into database
        $failedCall = FailedCall::firstOrNew(['session_id' => $data['SessionId']]);

        // General Information
        $failedCall->calling = $data['Calling'];
        $failedCall->called = $data['Called'];

        // Convert Unix Time
        $failedCall->call_start = $this->getDateTime($data['StartTime']);
        $failedCall->pdd = 0;

        if($data['Direction'] == 'answer')
        {
            // Get Client Data
            $failedCall->client_id = $data['client']->id;
            $failedCall->ip_number = $data['SipIP'];
            // Get Tariff Data
            $failedCall->tariff_id = $data['client']->tariff->id;
            $failedCall->tariff_name = $data['client']->tariff->name;
            $failedCall->tariff_prefix = $data['client_rate']->prefix;
            // Get Meida Data
            $failedCall->orig_call_id = $data['LegId'];
            if (!empty($data['Media'])) {
                $media_data = $this->getMediaData($data['Media']);
                $failedCall->codec = $media_data['codec'];
                $failedCall->org_media_ip = $media_data['media_ip'];
            }
        } elseif ($data['Direction'] == 'originate') {
            // Get Gateway Data
            $failedCall->id_route = $data['gateway']->id;
            $failedCall->term_call_id = $data['LegId'];
            $failedCall->release_reason = $data['TerminationCause'];
            // Get Meida Data
            if (!empty($data['Media'])) {
                $media_data = $this->getMediaData($data['Media']);
                $failedCall->codec = $media_data['codec'];
                $failedCall->term_media_ip = $media_data['media_ip'];
            }
        }

        return $failedCall->save();
    }

    public function insertIntoCalls($data)
    {
        // Insert into database
        $call = Call::firstOrNew(['session_id' => $data['SessionId']]);
        $oldCost = '';
        $oldCostD = '';
        if($is_old = $call->exists())
        {
            $oldCost = $call->cost;
            $oldCostD = $call->costD;
        }

        // General Information
        $call->calling = $data['Calling'];
        $call->called = $data['Called'];
        $call->call_start = $this->getDateTime($data['StartTime']);
        $call->call_end = $this->getDateTime($data['EndTime']);
        $call->pdd = $this->connectionDelay($data);
        $call->duration = $this->secondsResolution($data['Duration']);

        if($data['Direction'] == 'answer')
        {
            // Get Client Data
            $call->client_id = $data['client']->id;
            $call->ip_number = $data['SipIP'];
            $call->tariff_id = $data['client']->tariff->id;
            $call->call_rate = $data['client_rate']->voice_rate;
            $call->cost = $this->calculateCost($call->duration, $data['client_rate']);
            $call->tariffdesc = $data['client_rate']->description;
            $call->tariff_prefix = $data['client_rate']->prefix;
            $call->effective_duration = $call->duration > $data['client_rate']->minimal_time ? $this->getResolution($call->duration, $data['client_rate']->resolution) : $data['client_rate']->minimal_time;

            // Get Meida Data
            $call->orig_call_id = $data['LegId'];
            if (!empty($data['Media'])) {
                $media_data = $this->getMediaData($data['Media']);
                $call->codec = $media_data['codec'];
                $call->org_media_ip = $media_data['media_ip'];
            }

            DB::transaction(function () use ($call, $is_old, $oldCost)
            {
                $call->save();
                $call->is_old = $is_old;

                if($call->is_old && $call->cost != $oldCost)
                {
                    $client = $call->client;
                    $newAccountState = ($client->account_state + $oldCost) - $call->cost;
                    $client->update([
                        'account_state' => $newAccountState,
                    ]);
                } elseif(!$call->is_old) {
                    $call->client->update([
                        'account_state' => $call->client->account_state - $call->cost
                    ]);
                }
            });
        } elseif($data['Direction'] == 'originate'){
            // Get Gateway Data
            $call->costD = $this->calculateCost($call->duration, $data['gateway_rate']);
            $call->id_route = $data['gateway']->id;
            $call->route_rate_prefix = $data['gateway_rate']->prefix;
            // Get Meida Data
            $call->term_call_id = $data['LegId'];
            if (!empty($data['Media'])) {
                $media_data = $this->getMediaData($data['Media']);
                $call->codec = $media_data['codec'];
                $call->term_media_ip = $media_data['media_ip'];
            }

            DB::transaction(function () use ($call, $is_old, $oldCostD)
            {
                $call->save();
                $call->is_old = $is_old;

                if($call->is_old && $call->costD != $oldCostD)
                {
                    $gateway = $call->gateway;
                    $newAccountState = ($gateway->account_state + $oldCostD) - $call->costD;
                    $gateway->update([
                        'account_state' => $newAccountState,
                    ]);
                } elseif(!$call->is_old) {
                    $call->gateway->update([
                        'account_state' => $call->gateway->account_state - $call->costD
                    ]);
                }
            });
        }
        return $call;
    }

    public function saveCdrReport ($file_name, $index, $msg)
    {
        $cdr_query = DB::table('raw_process')->where('file_name', $file_name);
        if($cdr_report = $cdr_query->first())
        {
            $report_array[$index] = $msg;
            $update = $cdr_query->update([
                'status' => 3,
                'status_report' => serialize($report_array),
            ]);
        } else {
            return false;
        }
    }

    public function checkAndUpdateCdrReport ($file_name, $index)
    {
        $cdr_report = DB::table('raw_process')->where('file_name', $file_name);
        if(!$cdr_report)
        {
            return false;
        }

        if($oldReport = $cdr_report->first()->status_report)
        {
            $report_array = unserialize($oldReport);
            if(!array_key_exists($index, $report_array))
            {
                return false;
            }
            unset($report_array[$index]);
        }

        $status = empty($report_array) ? 2 : 3;
        $status_report = empty($report_array) ? NULL : serialize($report_array);
        return $update = $cdr_report->update([
            'status' => $status,
            'status_report' => $status_report,
        ]);
    }

    public function getMediaData($data)
    {
        $media_data = explode('@', $data);
        $data_array['codec'] = $media_data[0];
        $media_ip = explode(':', $media_data[1]);
        $data_array['media_ip'] = $media_ip[0];
        return $data_array;
    }

    public function getDateTime($unix_timestamp)
    {
        $datetime = new DateTime("@$unix_timestamp");
        return $datetime->format('Y-m-d H:i:s');
    }

    public function getTime($unix_timestamp)
    {
        $datetime = new DateTime("@$unix_timestamp");
        return $datetime->format('H:i:s');
    }

    public function secondsResolution($miliseconds, $resolution = 500)
    {
        $divider = 1000;
        $remainder = $miliseconds % $divider;
        $seconds = floor($miliseconds / $divider) + (isset($resolution) && $remainder >= $resolution ? 1 : 0);
        return $seconds > 0 ? $seconds : 1;
    }

    public function connectionDelay($data)
    {
        $connectedTime = $this->getDateTime($data['ConnectedTime']);
        $startTime = $this->getDateTime($data['StartTime']);
        $connection = Carbon::parse($connectedTime);
        $start = Carbon::parse($startTime);
        $delay = $start->diffInSeconds($connection);
        return $delay;
    }

    public function getResolution ($duration, $resolution)
    {
        return (floor($duration / $resolution) + ($duration % $resolution ? 1 : 0)) * $resolution;
    }

    public function calculateCost($duration, $rate)
    {
        if ($duration > $rate->grace_period) {
            if ($duration > $rate->minimal_time) {
                return $this->getResolution($duration, $rate->resolution) * (($rate->voice_rate * $rate->rate_multiplier) / 60);
            } else {
                return $rate->minimal_time * (($rate->voice_rate * $rate->rate_multiplier) / 60);
            }
        } else {
            return 0;
        }
    }

    public function getSimulate()
    {
        return view('pages.simulate.index');
    }

    public function simulate(Request $request)
    {
        $startTime = microtime(true);
        $this->validate($request, [
            'dialed_number' => 'required',
            'client_ip' => 'required',
            'gateway_ip' => 'required',
            'duration' => 'required',
            'dialing_number' => '',
        ]);

        $duration = $this->secondsResolution($request->duration);
        $result['duration'] = $duration;

        $data = [];
        $result['dialed'] = $data['Called'] = $request->dialed_number;
        $result['dialing'] = $data['Calling'] = $request->dialing_number;
        $data['StartTime'] = time();
        $data['EndTime'] = $data['StartTime'] + $duration;
        $result['StartTime'] = $this->getDateTime($data['StartTime']);
        $result['EndTime'] = $this->getDateTime($data['EndTime']);
        $result['c_ip'] = $request->client_ip;
        $result['g_ip'] = $request->gateway_ip;

        // Client process
        $client_id = DB::table('ips')->where('ip', '=', $request->client_ip)->value('client_id');
        $client = Client::where('id', $client_id)->first();

        if(!($client_id || $client))
        {
            return $response = $this->composeResponse(false, 'Client not found!');
        }

        $result['c_rate'] = $c_rate = $client->tariff->rate($data);

        if(!$c_rate)
        {
            return $response = $this->composeResponse(false, 'Call not allowed for this Client!');
        }

        $result['c_pre_balance'] = number_format($client->account_state, 4);
        $result['c_effective_duration'] = $duration > $c_rate->minimal_time ? $this->getResolution($duration, $c_rate->resolution) : $c_rate->minimal_time;
        $result['c_cost'] = number_format($this->calculateCost($duration, $c_rate), 2);
        $result['c_symbol'] = $client->tariff->currency->symbol;
        $result['c_cur_balance'] = number_format($client->account_state - $result['c_cost'], 4);

        // Gateway process
        $gateway = Gateway::where('ip', $request->gateway_ip)->first();
        if(!$gateway)
        {
            return $response = $this->composeResponse(false, 'Gateway not found!');
        }

        $result['g_rate'] = $g_rate = $gateway->tariff->rate($data);

        if(!$g_rate)
        {
            return $response = $this->composeResponse(false, 'Call not allowed through this gateway!');
        }

        $result['g_pre_balance'] = number_format($gateway->account_state, 4);
        $result['g_effective_duration'] = $duration > $g_rate->minimal_time ? $this->getResolution($duration, $g_rate->resolution) : $g_rate->minimal_time;
        $result['g_cost'] = number_format($this->calculateCost($duration, $g_rate), 2);
        $result['g_symbol'] = $gateway->tariff->currency->symbol;
        $result['g_cur_balance'] = number_format($gateway->account_state - $result['g_cost'], 4);
        $result['status'] = true;
        $result['execution_time'] = $executionTime = number_format((microtime(true) - $startTime), 2) . " ms";
        return $result;

    }

    public function test(){
        $result = '';


        return $result;
    }

    public function composeResponse($status, $msg)
    {
        $response['status'] = $status;
        $response['msg'] = $msg;
        return $response;
    }

}
