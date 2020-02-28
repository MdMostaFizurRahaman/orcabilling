<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use stdClass;
use App\Call;
use App\Client;
use App\Invoice;
use Carbon\Carbon;
use NumberFormatter;
use App\System\Company;
use App\Http\Requests\InvoiceRequest;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('pages.invoice.index');
    }

    public function dataTable()
    {
        $outputs = Invoice::select(['inv_number', 'client_id', 'user_id', 'inv_date', 'inv_total', 'inv_currency', 'id']);

        return DataTables::of($outputs)
            ->addColumn('client', function ($query) {
                return $query->client->username;
            })

            ->addColumn('user', function ($query) {
                return $query->user->username;
            })
            ->addColumn('inv_total', function ($query) {
                return '<h6>'.$query->inv_total. ' ' . $query->inv_currency .'</h6>';
            })

            ->addColumn('download', function($query){
                return '<a href="'.route('invoice.download', $query->id).'" class="btn btn-sm btn-secondary pdf"><i class="fas fa-file-pdf"></i></a>';
            })

            ->addColumn('print', function($query){
                return '<a href="'.route('invoice.preview', $query->id).'" class="btn btn-sm btn-info view"><i class="fas fa-eye"></i></a>';
            })

            ->addColumn('delete',function ($query) {
                return '<a href="'.route('invoice.delete', $query->id).'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['client', 'user', 'inv_total', 'download', 'print', 'delete'])
            ->make(true);
    }

    public function draftFormShow()
    {
        return view('pages.invoice.generate');
    }

    public function show(Invoice $invoice)
    {
        return view('pages.invoice.show')->with('invoice', $invoice);
    }

    public function draft(InvoiceRequest $request)
    {
        if($invoiceDraft = $this->prepareInvoieDraft($request))
        {
            if($request->generate_invoice)
            {
                $invoice = $this->generate($request, $invoiceDraft);
                return view('pages.invoice.show')->with(compact('invoice'));
            }

            $invoice = $invoiceDraft['invoiceData'];
            $invoice->id = 'draft';
            $invoice->items = $invoiceDraft['prefixSummaries'];
            return view('pages.invoice.show')->with('invoice', $invoice);

        } else {
            $client = Client::find($request->client_id)->username;
            Alert::info('Sorry!', $client . ' has not calls during the period.');
            return redirect()->back();
        }
    }

    public function generate($request, $invoiceDraft)
    {
        $invoiceData = $invoiceDraft['invoiceData'];
        $items = $invoiceDraft['prefixSummaries'];

        $invoice = Invoice::create([
            'inv_number' => $invoiceData->inv_number,
            'from_date' => $invoiceData->from_date,
            'to_date' => $invoiceData->to_date,
            'client_id' => $invoiceData->client->id,
            'company_id' => $invoiceData->company->id,
            'total_calls' => $invoiceData->total_calls,
            'total_duration' => $invoiceData->total_duration,
            'sub_total' => $invoiceData->sub_total,
            'vat_total' => $invoiceData->vat_total,
            'total_inc_vat' => $invoiceData->total_inc_vat,
            'inv_total' => $invoiceData->inv_total,
            'inv_date' => $invoiceData->inv_date,
            'due_date' => $invoiceData->due_date,
            'inv_currency' => $invoiceData->inv_currency,
            'user_id' => $request->user()->id,
        ]);

        foreach($items as $item)
        {
            $invoice->items()->create([
                'prefix' => $item->prefix,
                'rate' => $item->rate,
                'description' => $item->description,
                'total_calls' => $item->total_calls,
                'prefix' => $item->prefix,
                'total_duration' => $item->total_duration,
                'total_cost' => $item->total_cost,
            ]);
        }
        return $invoice;
        return Invoice::find($invoice->id);
    }

    public function prepareInvoieDraft($request)
    {
        $query = Call::whereBetween('call_start', [$request->from_date, $request->to_date ?: date('Y-m-d')])
                        ->whereClientId($request->client_id);

        if($request->tariff_prefix){
            $query = $query->where('tariff_prefix', $request->tariff_prefix);
        }

        $calls = $query->get();

        if($calls->count())
        {
            foreach($calls as $call){
                $call->clientRatio = $call->client->currency->ratio;
                $call->convertedCost = ($call->cost / $call->clientRatio);
            }

            $groupedCalls = $calls->groupBy(function($call, $key) {
                                return $call['tariff_prefix'].':'.$call['tariffdesc'].':'.$call['call_rate'];
                            }, $preserveKeys = true);

            $prefixSummaries = $groupedCalls
                                ->map(function ($calls, $groupKey) {
                                    $rateArray = explode(':', $groupKey);
                                    $prefix = $rateArray[0];
                                    $description = $rateArray[1];
                                    $rate = $rateArray[2];

                                    $prefixSummary = new stdClass();

                                    $prefixSummary->prefix = $prefix;
                                    $prefixSummary->rate = $rate;
                                    $prefixSummary->description = $description;
                                    $prefixSummary->total_calls = $calls->count();
                                    $prefixSummary->total_duration = $calls->sum('duration') / 60;
                                    $prefixSummary->total_cost = $calls->sum('convertedCost');
                                    return $prefixSummary;
                                });

            $client = Client::find($request->client_id);
            $company = Company::find($request->company_id);
            // Vat & previous balace set here.
            $vat = config('app.vat');

            // Invoice data calculation variables.
            $total_calls = $total_duration = $sub_total = $vat_total = $total_inc_vat = $inv_total = $due_amount = 0;


            foreach($prefixSummaries as $prefix => $summary){
                $total_calls += $summary->total_calls;
                $total_duration += $summary->total_duration;
                $sub_total += $summary->total_cost;
            }

            $vat_total = $sub_total * $vat;
            $total_inc_vat = $sub_total + $vat_total;
            $inv_total = $total_inc_vat;
            $speller = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            $amount_in_words = $speller->format($due_amount);

            $invoiceData = new stdClass();

            $invoiceData->inv_number = $this->getInvoiceNumber($request->company_id);
            $invoiceData->inv_date = Carbon::today()->format('Y-m-d');
            $invoiceData->due_date = Carbon::today()->addDays(3)->format('Y-m-d');
            $invoiceData->from_date = $request->from_date;
            $invoiceData->to_date = $request->to_date;
            $invoiceData->client = $client;
            $invoiceData->company = $company;

            $invoiceData->total_calls = $total_calls;
            $invoiceData->total_duration = $total_duration;
            $invoiceData->sub_total = $sub_total;
            $invoiceData->vat_total = $vat_total;
            $invoiceData->total_inc_vat = $total_inc_vat;
            $invoiceData->inv_total = $inv_total;
            $invoiceData->inv_currency = 'USD';
            $invoiceData->amount_in_words = $amount_in_words;

            $invoiceDraft['prefixSummaries'] = $prefixSummaries;
            $invoiceDraft['invoiceData'] = $invoiceData;

            return $invoiceDraft;
        } else {
            return false;
        }
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

    public function downloadPDF(Invoice $invoice)
    {
        $pdf = PDF::loadView('pages.invoice.invoice', compact('invoice'))->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download($invoice->inv_number . '.pdf');
    }

    public function viewPDF(Invoice $invoice)
    {
        $pdf = PDF::loadView('pages.invoice.invoice', compact('invoice'))->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->stream($invoice->inv_number . '.pdf');
    }

    public function printPreview(Invoice $invoice)
    {
        return view('pages.invoice.preview')->with('invoice', $invoice);
    }

    public function delete(Invoice $invoice)
    {
        if($invoice->delete())
        return response(['status' => 'success', 'message' => 'Invoice deleted successfully.']);
    }

}
