<?php

namespace App\Http\Controllers;

use PDF;
use stdClass;
use App\Call;
use App\Client;
use App\Invoice;
use Carbon\Carbon;
use App\System\Company;
use App\Http\Requests\InvoiceRequest;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('pages.invoice.index');
    }

    public function dataTable()
    {
        $outputs = Invoice::select(['invoice_number', 'company_id', 'from_date', 'to_date', 'id']);

        return DataTables::of($outputs)
            ->addColumn('company_name', function ($query) {
                return $query->default_company ? '<h6>' . $query->company_name . '<span class="badge badge-success">Default</span></h6>' : '<h6>'.$query->company_name.'</h6>';
            })
            ->addColumn('view', function($query){
                return '<a href="'.route('invoice.view', $query->id).'" class="btn btn-sm btn-success view"><i class="fas fa-binoculars"></i></a>';
            })
            // ->addColumn('edit',function ($query) {
            //     return '<a href="'.route('invoice.edit', $query->id).'" class="btn btn-sm btn-info edit"><i class="fa fa-edit"></i></a>';
            // })
            // ->addColumn('delete',function ($query) {
            //     return '<a href="'.route('invoice.delete', $query->id).'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></a>';
            // })
            ->addColumn('logo', function ($query) {
                return '<img width="100" height="40" src="'.asset($query->logo).'" alt="'.$query->company_name.' logo">';
            })
            ->rawColumns(['company_name', 'logo', 'view'])
            ->make(true);
    }

    public function invoiceFormShow()
    {
        return view('pages.invoice.generate');
    }

    public function show()
    // public function show(InvoiceRequest $request)
    {   $request = new stdClass();
        $request->client_id = '2';
        $request->company_id = 17;
        $request->prefix = '';
        $request->generate_invoice = '';
        $request->from_date = '2019-11-01';
        $request->to_date = '2020-02-01';
        $invoiceSummary = $this->prepareInvoieSummary($request);

        // Test purpose
        $company = Company::find($request->company_id);
        $client = Client::find($request->client_id);

        if($request->generate_invoice)
        {
           $newInvoice = $this->generateInvoice($request);
        }

        return view('pages.invoice.show')->with(compact('invoiceSummary', 'company', 'client'));
    }

    public function generate($request)
    {
        $invoice = Invoice::create([
            'inv_number' => $this->getInvoiceNumber($request->company_id),
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'client_id' => $request->client_id,
            'tariff_prefix' => $request->tariff_prefix,
            'company_id' => $request->company_id,
            'user_id' => $request->user()->id,
        ]);
    }

    public function prepareInvoieSummary($request)
    {
        $query = Call::whereBetween('call_start', [$request->from_date, $request->to_date ?: date('Y-m-d')])
                        ->whereClientId($request->client_id);

        if($request->prefix){
            $query = $query->where('tariff_prefix', $request->prefix);
        }

        $calls = $query->get();

        foreach($calls as $call){
            $call->clientRatio = $call->client->currency->ratio;
            $call->convertedCost = ($call->cost / $call->clientRatio);
        }

        $groupedCalls = $calls->groupBy(function($call, $key) {
                            return $call['tariff_prefix'].':'.$call['tariffdesc'].':'.$call['call_rate'];
                        }, $preserveKeys = true);

        $groupedCallsSummary = $groupedCalls
                                ->map(function ($calls, $groupKey) {
                                    $rateArray = explode(':', $groupKey);
                                    $prefix = $rateArray[0];
                                    $description = $rateArray[1];
                                    $call_rate = $rateArray[2];

                                    $groupSummary = new stdClass();

                                    $groupSummary->call_rate = $call_rate;
                                    $groupSummary->prefix = $prefix;
                                    $groupSummary->description = $description;
                                    $groupSummary->totalCalls = $calls->count();
                                    $groupSummary->totalDuration = $calls->sum('duration') / 60;
                                    $groupSummary->totalCost = $calls->sum('convertedCost');
                                    return $groupSummary;
                                });


        $totalCalls = $totalDuration = $totalCost = 0;

        foreach($groupedCallsSummary as $prefix => $callsSummary){
            $totalCalls += $callsSummary->totalCalls;
            $totalDuration += $callsSummary->totalDuration;
            $totalCost += $callsSummary->totalCost;
        }


        $invoiceSummary['groupedCallsSummary'] = $groupedCallsSummary;
        $invoiceSummary['invoiceNumber'] = $this->getInvoiceNumber($request->company_id);
        $invoiceSummary['invoiceDate'] = Carbon::today()->format('Y-m-d');
        $invoiceSummary['invoiceDueDate'] = Carbon::today()->addDays(3)->format('Y-m-d');
        $invoiceSummary['invoiceFromDate'] = $request->from_date;
        $invoiceSummary['invoiceToDate'] = $request->to_date;

        $invoiceSummary['totalCalls'] = number_format($totalCalls);
        $invoiceSummary['totalDuration'] = number_format($totalDuration, 2);
        $invoiceSummary['totalCost'] = $totalCost;

        return $invoiceSummary;
    }

    protected function getInvoiceNumber($company_id)
    {
        // Get the last created order
        $lastInvoice = Invoice::latest()->first();
        $newDateString = Carbon::now()->format("Ymd");

        if ( ! $lastInvoice ){
            // We get here if there is no order at all
            // If there is no number set it to 0, which will be 1 at the end.
            $number = 0;
        } else {
            // Get previous date
            $dateString = substr($lastInvoice->inv_number, -15, -7);
            // if not new date then continue from last number
            if($dateString == $newDateString) {
                $number = substr($lastInvoice->inv_number, -6);
            } else {
                // if new date then start from new number
                $number = 0;
            }
        }

        // If we have ORD000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %05d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.
        $invPrefix = Company::find($company_id)->invoice_prefix;
        return $invPrefix . $newDateString . '-' . sprintf('%06d', intval($number) + 1);
    }

    public function downloadPDF() {
        $request = new stdClass();
        $request->client_id = '2';
        $request->company_id = 17;
        $request->prefix = '';
        $request->generate_invoice = '';
        $request->from_date = '2019-11-01';
        $request->to_date = '2020-02-01';
        $invoiceSummary = $this->prepareInvoieSummary($request);

        // Test purpose
        $company = Company::find($request->company_id);
        $client = Client::find($request->client_id);

        // return view('pages.invoice.test')->with(compact('invoiceSummary', 'company', 'client'));

        $invoice = PDF::loadView('pages.invoice.test', compact('invoiceSummary', 'company', 'client'))->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $invoice->stream();
}

}
