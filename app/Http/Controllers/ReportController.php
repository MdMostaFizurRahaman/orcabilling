<?php

namespace App\Http\Controllers;

use stdClass;
use App\Call;
use App\Client;
use App\Gateway;
use App\FailedCall;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\SummaryExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class ReportController extends Controller
{
    public function successCallsSearchPanel()
    {
        return view('pages.reports.success-panel');
    }

    public function failedCallsSearchPanel()
    {
        return view('pages.reports.failed-panel');
    }

    public function origTermCallsSearchPanel()
    {
        return view('pages.reports.orig-term-panel');
    }

    public function lossProfitSearchPanel()
    {
        return view('pages.reports.loss-profit-panel');
    }

    public function successCallsSummary(Request $request)
    {
        $this->validateSummaryRequest($request);
        $collection = $this->fetchSuccessSummary($request);
        return view('pages.reports.success-summary')->with(compact('collection'));
    }

    public function failedCallsSummary(Request $request)
    {
        $this->validateSummaryRequest($request);
        $collection = $this->fetchFailedSummary($request);
        return view('pages.reports.failed-summary')->with(compact('collection'));
    }

    public function origTermSummary(Request $request)
    {
        $this->validateSummaryRequest($request);

        // Origination Report
        $groupedOriginationCollection = $this->fetchOrigTermSummary($request);
        $report['origination'] = $this->prepareOriginationReport($groupedOriginationCollection);

        // Termination Report
        $groupedTerminationColleciton = $this->fetchOrigTermSummary($request);
        $report['termination'] = $this->prepareTerminationReport($groupedTerminationColleciton);

        // $collection = $this->fetchClientsSummary($request);
        return view('pages.reports.orig-term-summary')->with(compact('report'));
    }

    public function lossProfitSummary(Request $request)
    {
        $this->validateSummaryRequest($request);
        $report = $this->fetchLossProfitSummary($request);

        return view('pages.reports.loss-profit-summary')->with(compact('report'));
    }

    public function exportLossProfitSummary(Request $request)
    {
        $validate = $this->validateSummaryRequest($request);

        $collection = $this->fetchLossProfitSummary($request);

        $file_name = 'Loss-Profit Summary ('.$request->from_date.' - '.$request->to_date.').'.$request->mime;

        return $this->export($collection, $file_name, 'loss-profit', $request->mime);
    }

    public function exportOrigTermSummary(Request $request)
    {
        $validate = $this->validateSummaryRequest($request);

        // Origination Report
        $groupedOriginationCollection = $this->fetchOrigTermSummary($request);
        $collection['origination'] = $this->prepareOriginationReport($groupedOriginationCollection);

        // Termination Report
        $groupedTerminationColleciton = $this->fetchOrigTermSummary($request);
        $collection['termination'] = $this->prepareTerminationReport($groupedTerminationColleciton);

        $file_name = 'Orig-Term Calls Summary ('.$request->from_date.' - '.$request->to_date.').'.$request->mime;

        return $this->export($collection, $file_name, 'orig-term', $request->mime);
    }

    public function exportSuccessSummary(Request $request)
    {
        $validate = $this->validateSummaryRequest($request);

        $collection = $this->fetchSuccessSummary($request);
        $file_name = 'Success Calls Summary ('.$request->from_date.' - '.$request->to_date.').'.$request->mime;

        return $this->export($collection, $file_name, 'success', $request->mime);
    }

    public function exportFailedSummary(Request $request)
    {
        $validate = $this->validateSummaryRequest($request);

        $collection = $this->fetchFailedSummary($request);
        $file_name = 'Failed Calls Summary ('.$request->from_date.' - '.$request->to_date.').'.$request->mime;
        // return view('pages.reports.export-success-summary', [
        //     'collection' => $collection,
        // ]);
        return $this->export($collection, $file_name, 'failed', $request->mime);
    }

    public function fetchClientsSummary($request)
    {
        $clientCollection = Client::whereHas('calls', function (Builder $query) use ($request) {
                                $query = $query->whereBetween('call_start', [$request->from_date, $request->to_date]);
                                if($request->client_ip){
                                    $query = $query->where('ip_number', $request->client_ip);
                                }
                                if($request->gateway_id){
                                    $query = $query->where('id_route', $request->gateway_id);
                                }
                                if($request->prefix){
                                    $query = $query->where('tariff_prefix', $request->prefix)
                                                ->orWhere('route_rate_prefix', $request->prefix);
                                }
                            })
                            ->orWhereHas('failed_calls', function (Builder $query) use ($request) {
                                $query = $query->whereBetween('call_start', [$request->from_date, $request->to_date]);
                                if($request->client_ip){
                                    $query = $query->where('ip_number', $request->client_ip);
                                }
                                if($request->gateway_id){
                                    $query = $query->where('id_route', $request->gateway_id);
                                }
                                if($request->prefix){
                                    $query = $query->where('tariff_prefix', $request->prefix);
                                }
                            })
                            ->withCount([
                                'calls',
                                'failed_calls',
                                'calls as totalCost' => function($query) {
                                    $query->select(DB::raw('sum(cost)'));
                                },
                                'calls as avgPdd' => function($query) {
                                    $query->select(DB::raw('avg(pdd)'));
                                },
                                'calls as totalDuration' => function($query){
                                    $query->select(DB::raw('sum(duration)'));
                                },
                                'calls as ACD' => function($query){
                                    $query->select(DB::raw('avg(duration)'));
                                }
                            ]);

        $clients = $clientCollection->get();

        $clients = $clients->map(function ($client) {
            $client->avgPdd = $this->getSecondsToTimeFormat($client->avgPdd);
            $client->ACD = $this->getSecondsToTimeFormat($client->ACD);

            $client->totalCalls = $totalCalls = $client->calls_count + $client->failed_calls_count;
            $client->ASR = ($client->calls_count / $totalCalls) * 100;

            return $client;
        });

        $totalCalls = number_format($clients->sum('totalCalls'));
        $totalSuccessCalls = number_format($clients->sum('calls_count'));
        $totalDuration = number_format($clients->sum('totalDuration') / 60, 2);
        $totalCost = number_format($clients->sum('totalCost'), 2);

        if($request->prefix)
        {
            $collection = $clients->groupBy($request->sort_by ?: 'prefix');
            $collection = $collection->sortBy($request->sort_by ?: 'daily');
        }

        $clientSummary['clients'] = $clients;
        $clientSummary['totalCalls'] = $totalCalls;
        $clientSummary['totalSuccessCalls'] = $totalSuccessCalls;
        $clientSummary['totalCost'] = $totalCost;
        $clientSummary['totalDuration'] = $totalDuration;

        $gatewayCollection = Gateway::whereHas('calls', function (Builder $query) use ($request) {
                                        $query = $query->whereBetween('call_start', [$request->from_date, $request->to_date]);
                                        if($request->client_ip){
                                            $query = $query->where('ip_number', $request->client_ip);
                                        }
                                        if($request->gateway_id){
                                            $query = $query->where('id_route', $request->gateway_id);
                                        }
                                        if($request->prefix){
                                            $query = $query->where('tariff_prefix', $request->prefix)
                                                        ->orWhere('route_rate_prefix', $request->prefix);
                                        }
                                    })
                                    ->orWhereHas('failed_calls', function (Builder $query) use ($request) {
                                        $query = $query->whereBetween('call_start', [$request->from_date, $request->to_date]);
                                        if($request->client_ip){
                                            $query = $query->where('ip_number', $request->client_ip);
                                        }
                                        if($request->gateway_id){
                                            $query = $query->where('id_route', $request->gateway_id);
                                        }
                                        if($request->prefix){
                                            $query = $query->where('tariff_prefix', $request->prefix);
                                        }
                                    })
                                    ->withCount([
                                        'calls',
                                        'failed_calls',
                                        'calls as totalCost' => function($query) {
                                            $query->select(DB::raw('sum(costD)'));
                                        },
                                        'calls as avgPdd' => function($query) {
                                            $query->select(DB::raw('avg(pdd)'));
                                        },
                                        'calls as totalDuration' => function($query){
                                            $query->select(DB::raw('sum(duration)'));
                                        },
                                        'calls as ACD' => function($query){
                                            $query->select(DB::raw('avg(duration)'));
                                        }
                                    ]);

        $gateways = $gatewayCollection->get();

        $gateways = $gateways->map(function ($gateway) {
            $gateway->avgPdd = $this->getSecondsToTimeFormat($gateway->avgPdd);
            $gateway->ACD = $this->getSecondsToTimeFormat($gateway->ACD);

            $gateway->totalCalls = $totalCalls = $gateway->calls_count + $gateway->failed_calls_count;
            $gateway->ASR = ($gateway->calls_count / $totalCalls) * 100;
            return $gateway;
        });

        $totalCalls = number_format($gateways->sum('totalCalls'));
        $totalSuccessCalls = number_format($gateways->sum('calls_count'));
        $totalDuration = number_format($gateways->sum('totalDuration') / 60, 2);
        $totalCost = number_format($gateways->sum('totalCost'), 2);

        // $collection = $collection->sortBy($request->sort_by ?: 'daily');
        // $collection = $calls->groupBy($request->group_by ?: 'monthly');

        $gatewaySummary['gateways'] = $gateways;
        $gatewaySummary['totalCalls'] = $totalCalls;
        $gatewaySummary['totalSuccessCalls'] = $totalSuccessCalls;
        $gatewaySummary['totalCost'] = $totalCost;
        $gatewaySummary['totalDuration'] = $totalDuration;

        $summary['client'] = $clientSummary;
        $summary['gateway'] = $gatewaySummary;
        return $summary;
    }

    public function fetchLossProfitSummary ($request)
    {
        $collection = $this->searchCallsSummary('App\Call', $request)->get();

        $calls = $collection->map(function($call){
            $call->client_id = $call->client->id;
            $call->gateway_id = $call->gateway->id;
            $call->clientRatio = $call->client->tariff->currency->ratio;
            $call->gatewayRatio = $call->gateway->tariff->currency->ratio;
            $call->convertedCost = (1 / $call->clientRatio) * $call->cost;
            $call->convertedCostD = (1 / $call->gatewayRatio) * $call->costD;
            $call->gateway_id = $call->gateway->id;
            $call->monthly = Carbon::parse($call->call_start)->format('m');
            $call->daily = Carbon::parse($call->call_start)->toDateString();
            $call->hourly = Carbon::parse($call->call_start);
            return $call;
        });

        $sortedCalls = $calls->sortBy($request->sort_by ?: 'daily');
        $groupedCalls = $sortedCalls->groupBy($request->group_by, $preserveKeys = true);

        $groupedCallsSummary = $groupedCalls->transform(function($clients, $key) {
                return $clients->groupBy(function($call, $key) {
                    return $call['client_id'] . ':' . $call['gateway_id'];
                })
                ->map(function ($calls) {
                    $groupSummary = new stdClass();
                    $groupSummary->totalCalls = $calls->count();
                    $groupSummary->totalDuration = $calls->sum('duration');
                    $groupSummary->totalCost = $calls->sum('convertedCost');
                    $groupSummary->totalCostD = $calls->sum('convertedCostD');
                    $groupSummary->totalMargin = $groupSummary->totalCost - $groupSummary->totalCostD;
                    $groupSummary->ACD = $this->getSecondsToTimeFormat($calls->avg('duration'));
                    $groupSummary->avgPdd = $this->getSecondsToTimeFormat($calls->avg('pdd'));
                    return $groupSummary;
                });
            });

        $totalCalls = $totalDuration = $totalCost = $totalCostD = $totalMargin = 0;

        foreach($groupedCallsSummary as $groupName => $group){
            foreach($group as $clientAndGatewayId => $callsSummary){
                $totalCalls += $callsSummary->totalCalls;
                $totalDuration += $callsSummary->totalDuration;
                $totalCost += $callsSummary->totalCost;
                $totalCostD += $callsSummary->totalCostD;
                $totalMargin += $callsSummary->totalMargin;
            }
        }

        $lossProfitReport['groupedCallsSummary'] = $groupedCallsSummary;
        $lossProfitReport['totalCalls'] = number_format($totalCalls);
        $lossProfitReport['totalDuration'] = number_format($totalDuration / 60, 2);
        $lossProfitReport['totalCost'] = number_format($totalCost, 2);
        $lossProfitReport['totalCostD'] = number_format($totalCostD, 2);
        $lossProfitReport['totalMargin'] = number_format($totalMargin, 2);

        return $lossProfitReport;
    }

    public function fetchSuccessSummary($request)
    {
        $collection = $this->searchCallsSummary("App\Call", $request)->get();
        foreach($collection as $call){
            $call->client_name = $call->client->username;
            $call->gateway_name = $call->gateway->name;
            $call->monthly = Carbon::parse($call->call_start)->format('m');
            $call->daily = Carbon::parse($call->call_start)->toDateString();
            $call->hourly = Carbon::parse($call->call_start);
        }
        $totalCalls = number_format($collection->count());
        $totalCost = number_format($collection->sum('cost'), 2);
        $totalCostD = number_format($collection->sum('costD'), 2);
        $totalDuration = number_format($collection->sum('duration') / 60, 2);

        $collection = $collection->sortBy($request->sort_by ?: 'daily');
        $calls = $collection->groupBy($request->group_by ?: 'monthly');
        $data['calls'] = $calls;
        $data['totalCalls'] = $totalCalls;
        $data['totalCost'] = $totalCost;
        $data['totalCostD'] = $totalCostD;
        $data['totalDuration'] = $totalDuration;

        return $data;
    }

    public function fetchFailedSummary($request)
    {
        $collection = $this->searchCallsSummary("App\FailedCall", $request)->get();

        foreach($collection as $call){
            $call->client_name = $call->client->username;
            $call->gateway_name = $call->gateway->name;
            $call->monthly = Carbon::parse($call->call_start)->format('m');
            $call->daily = Carbon::parse($call->call_start)->toDateString();
            $call->hourly = Carbon::parse($call->call_start);
        }
        $totalCalls = $collection->count();

        $collection = $collection->sortBy($request->sort_by ?: 'daily');
        $calls = $collection->groupBy($request->group_by ?: 'monthly');
        $data['calls'] = $calls;
        $data['totalCalls'] = $totalCalls;

        return $data;
    }

    public function fetchOrigTermSummary($request)
    {
        $calls = $this->searchCallsSummary('App\Call', $request)->get();
        $failedCalls = $this->searchCallsSummary('App\FailedCall', $request)->get();

        $mergedCollections = $calls->merge($failedCalls);

        $mergedCollections = $mergedCollections->map(function($call){
            $className = get_class($call);
            $baseClass = Str::camel(class_basename($className));
            $call->type = $baseClass;
            $call->client_id = $call->client->id;
            $call->gateway_id = $call->gateway->id;
            $call->monthly = Carbon::parse($call->call_start)->format('m');
            $call->daily = Carbon::parse($call->call_start)->toDateString();
            $call->hourly = Carbon::parse($call->call_start);
            return $call;
        });

        $mergedCollections = $mergedCollections->sortBy($request->sort_by ?: 'daily');
        $groupedCollection = $mergedCollections->groupBy($request->group_by, $preserveKeys = true);
        return $groupedCollection;
    }

    protected function prepareOriginationReport($groupedCollection)
    {
        $groupedOriginationSummary = $groupedCollection->transform(function($clients, $key) {
            return $clients->groupBy('client_id')->map(function ($calls) {
                $clientSummary = new stdClass();
                $clientSummary->callsCount = $calls->countBy('type');
                $clientSummary->totalSuccessCalls =  !empty($clientSummary->callsCount['call']) ? $clientSummary->callsCount['call'] : 0;
                $clientSummary->totalFailedCalls =  !empty($clientSummary->callsCount['failed_call']) ? $clientSummary->callsCount['failed_call'] : 0;
                $clientSummary->totalCalls = $calls->count();
                $clientSummary->totalDuration = $calls->sum('duration');
                $clientSummary->totalCost = $calls->sum('cost');
                $clientSummary->ACD = $this->getSecondsToTimeFormat($calls->avg('duration'));
                $clientSummary->avgPdd = $this->getSecondsToTimeFormat($calls->avg('pdd'));
                $clientSummary->ASR = ($clientSummary->totalSuccessCalls / $clientSummary->totalCalls) * 100;
                return $clientSummary;
            });
        });

        $totalOriginationCalls = $totalOriginationSuccessCalls = $totalOriginationFailedCalls = $totalOriginationDuration = $totalOriginationCost = 0;

        foreach($groupedOriginationSummary as $groupName => $group){
            foreach($group as $client_id => $clientSummary){
                $totalOriginationSuccessCalls += $clientSummary->totalSuccessCalls;
                $totalOriginationFailedCalls += $clientSummary->totalFailedCalls;
                $totalOriginationCalls += $clientSummary->totalCalls;
                $totalOriginationDuration += $clientSummary->totalDuration;
                $totalOriginationCost += $clientSummary->totalCost;
            }
        }

        $originationSummary['groupedOriginationSummary'] = $groupedOriginationSummary;
        $originationSummary['totalCalls'] = number_format($totalOriginationCalls);
        $originationSummary['totalSuccessCalls'] = number_format($totalOriginationSuccessCalls);
        $originationSummary['totalDuration'] = number_format($totalOriginationDuration / 60, 2);
        $originationSummary['totalCost'] = number_format($totalOriginationCost, 2);

        return $originationSummary;
    }

    protected function prepareTerminationReport($groupedCollection)
    {
        $groupedTerminationSummary = $groupedCollection->transform(function($gateways, $key) {
            return $gateways->groupBy('gateway_id')->map(function ($calls) {
                $routeSummary = new stdClass();
                $routeSummary->callsCount = $calls->countBy('type');
                $routeSummary->totalSuccessCalls =  !empty($routeSummary->callsCount['call']) ? $routeSummary->callsCount['call'] : 0;
                $routeSummary->totalFailedCalls =  !empty($routeSummary->callsCount['failed_call']) ? $routeSummary->callsCount['failed_call'] : 0;
                $routeSummary->totalCalls = $calls->count();
                $routeSummary->totalDuration = $calls->sum('duration');
                $routeSummary->totalCost = $calls->sum('costD');
                $routeSummary->ACD = $this->getSecondsToTimeFormat($calls->avg('duration'));
                $routeSummary->avgPdd = $this->getSecondsToTimeFormat($calls->avg('pdd'));
                $routeSummary->ASR = ($routeSummary->totalSuccessCalls / $routeSummary->totalCalls) * 100;
                return $routeSummary;
            });
        });

        $totalTerminationCalls = $totalTerminationSuccessCalls = $totalTerminationFailedCalls = $totalTerminationDuration = $totalTerminationCost = 0;

        foreach($groupedTerminationSummary as $groupName => $group){
            foreach($group as $gateway_id => $gatewaySummary){
                $totalTerminationSuccessCalls += $gatewaySummary->totalSuccessCalls;
                $totalTerminationFailedCalls += $gatewaySummary->totalFailedCalls;
                $totalTerminationCalls += $gatewaySummary->totalCalls;
                $totalTerminationDuration += $gatewaySummary->totalDuration;
                $totalTerminationCost += $gatewaySummary->totalCost;
            }
        }

        $terminationSummary['groupedTerminationSummary'] = $groupedTerminationSummary;
        $terminationSummary['totalCalls'] = number_format($totalTerminationCalls);
        $terminationSummary['totalSuccessCalls'] = number_format($totalTerminationSuccessCalls);
        $terminationSummary['totalDuration'] = number_format($totalTerminationDuration / 60, 2);
        $terminationSummary['totalCost'] = number_format($totalTerminationCost, 2);

        return $terminationSummary;
    }

    public function validateSummaryRequest(Request $request)
    {
        return $validation = $this->validate($request, [
            'client_ip' => 'string|nullable',
            'gateway_id' => 'string|nullable',
            'called' => 'string|nullable',
            'calling' => 'string|nullable',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'group_by' => 'string',
            'sort_by' => 'string',
        ]);
    }

    public function searchCallsSummary($model, $request)
    {
        $query = $model::whereBetween('call_start', [$request->from_date, $request->to_date]);
        if($request->client_ip){
            $query = $query->where('ip_number', $request->client_ip);
        }
        if($request->gateway_id){
            $query = $query->where('id_route', $request->gateway_id);
        }
        if($request->prefix){
            $query = $query->where('tariff_prefix', $request->prefix)
                        ->orWhere('route_rate_prefix', $request->prefix);
        }
        if($request->calling){
            $query = $query->where('calling', $request->calling);
        }
        if($request->called){
            $query = $query->where('called', $request->called);
        }

        return $query;
    }

    protected function getFormattedNumber($num){
        return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))?/i", "$1,", $num);
    }

    protected function getFormattedCurrency($num){
        return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
    }

    protected function getSecondsToTimeFormat($seconds)
    {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = round($seconds % 60);
        return $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    }

    protected function getTotalDuration($times)
    {
        $all_seconds= 0;
        foreach ($times as $time)
        {
            list($hour, $minute, $second) = explode(':', $time);
            $all_seconds += $hour * 3600;
            $all_seconds += $minute * 60;
            $all_seconds += $second;
        }

        $total_minutes = floor($all_seconds/60);
        $seconds = $all_seconds % 60;
        $hours = floor($total_minutes / 60);
        $minutes = $total_minutes % 60;

        // returns the time already formatted
        return sprintf('%02d:%02d:%02d', $hours, $minutes,$seconds);
    }

    public function export($collection, $file_name, $summary_type, $mime_type)
    {
        $export = new SummaryExport();
        $export->forCollection($collection);
        $export->forSummaryType($summary_type);
        if($mime_type == 'csv'){
            return Excel::download($export, $file_name, \Maatwebsite\Excel\Excel::CSV, [
                        'Content-Type' => 'text/csv',
                ]);
        } else {
            return Excel::download($export, $file_name);
        }
    }

    public function testLossProfitSummary ($request)
    {
        $collection = $this->searchCallsSummary('App\Call', $request)->get();

        $calls = $collection->map(function($call){
            $call->client_id = $call->client->id;
            $call->gateway_id = $call->gateway->id;
            $call->monthly = Carbon::parse($call->call_start)->format('m');
            $call->daily = Carbon::parse($call->call_start)->toDateString();
            $call->hourly = Carbon::parse($call->call_start);
            return $call;
        });

        $sortedCalls = $calls->sortBy($request->sort_by ?: 'daily');
        $groupedCalls = $sortedCalls->groupBy($request->group_by);

        $groupedCallsSummary = $groupedCalls->transform(function($clients, $key) {
                return $clients->groupBy(function($call, $key) {
                    return $call['client_id'].':'.$call['gateway_id'];
                })
                ->map(function ($calls) {
                    $groupSummary = new stdClass();
                    $groupSummary->totalCalls = $calls->count();
                    $groupSummary->totalDuration = $calls->sum('duration');
                    $groupSummary->totalCost = $calls->sum('cost');
                    $groupSummary->totalCostD = $calls->sum('costD');
                    $groupSummary->totalMargin = $groupSummary->totalCost - $groupSummary->totalCostD;
                    $groupSummary->ACD = $this->getSecondsToTimeFormat($calls->avg('duration'));
                    $groupSummary->avgPdd = $this->getSecondsToTimeFormat($calls->avg('pdd'));
                    return $groupSummary;
                });
            });

        $totalCalls = $totalDuration = $totalCost = $totalCostD = $totalMargin = 0;

        foreach($groupedCallsSummary as $groupName => $group){
            foreach($group as $clientAndGatewayId => $callsSummary){
                $totalCalls += $callsSummary->totalCalls;
                $totalDuration += $callsSummary->totalDuration;
                $totalCost += $callsSummary->totalCost;
                $totalCostD += $callsSummary->totalCostD;
                $totalMargin += $callsSummary->totalMargin;
            }
        }

        $lossProfitReport['groupedCallsSummary'] = $groupedCallsSummary;
        $lossProfitReport['totalCalls'] = number_format($totalCalls);
        $lossProfitReport['totalDuration'] = number_format($totalDuration / 60, 2);
        $lossProfitReport['totalCost'] = number_format($totalCost, 2);
        $lossProfitReport['totalCostD'] = number_format($totalCostD, 2);
        $lossProfitReport['totalMargin'] = number_format($totalMargin, 2);

        return $lossProfitReport;
    }

}
