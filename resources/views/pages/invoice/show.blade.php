@extends('layouts.app')

@section('title')
    Generate Invoice
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <style>
        .page-break {
            page-break-after: always;
        }

        @page { size: auto;  margin: 0mm; }

        @media print {

            body {
                -webkit-print-color-adjust: exact !important;
            }
            .items-section > table > thead,
            .items-section > table > thead > tr,
            .items-section > table > thead > tr > th
            {
                background-color: #5A6268 !important;
                /* background-color: black !important; */
            }

            .column-table td {
                border: 0px solid white !important;
                border-right: 1px solid #E2E6E9 !important;
                border-bottom-style: none;
            }
        }

        .a4 {
            background-color: black;
            margin: 0 calc((100vw - 70%)/2);
        }

        #invoice-inner {
            padding: 20px 72px;
            height: 297mm;
            background-color: white;
            /* background-color: black; */
        }


    </style>
@endpush

@php
    $disabled = $invoice->id == 'draft' ? 'isDisabled' : false;
    $draft = $invoice->id == 'draft' ? 'Draft' : '';
@endphp

@section('content')
<div class="container-fluid loaded" id="invoice-section">

    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="d-flex no-block align-items-center m-b-0">
                        <div class="card-title">
                            <h5>Invoice {{$draft}}</h5>
                            <small>{{$invoice->from_date.' to '. $invoice->to_date}}</small>
                        </div>
                        <div class="ml-auto">
                            <div class="btn-group">
                                <a href="{{route('invoice.download', $invoice->id)}}" data-toggle="tooltip" title="Download to pdf"  class="btn btn-rounded btn-danger {{$disabled}}"> <i class="fas fa-file-pdf"></i> PDF</a>
                                {{-- <a href="{{route('invoice.preview', $invoice->id)}}" data-toggle="tooltip" title="Preview Invoice"  class="btn btn-rounded btn-secondary"> <i class="fas fa-eye"></i> Preview</a> --}}
                                <a href="#" onclick="printDiv('invoice')" data-toggle="tooltip" title="Print Invoice"  class="btn btn-rounded btn-secondary"> <i class="fas fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="invoice">
                    <div class="" id="invoice-inner" style="">
                        <div class="top-bar">
                            <div class="company d-flex justify-content-between">
                                <div class="">
                                    <img class="logo" src="{{asset($invoice->company->logo)}}" style="max-width: 150px;" alt="LogicBag-logo">
                                </div>
                                <div class="d-flex-column text-right">
                                    <div class="name h3">{{strtoupper($invoice->company->company_name)}}</div>
                                    <div class="h6">{{$invoice->company->postal_address}}</div>
                                </div>
                            </div>
                            <hr class="mt-0 mb-0">
                            <hr class="mt-0">
                        </div>

                        <div class="address-section d-flex justify-content-between">
                            <div class="client">
                                <table>
                                    <thead>
                                        <td colspan="2">To</td>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><strong>{{$invoice->client->name}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">{{$invoice->client->address}}, {{$invoice->client->zip}}<br>
                                                {{$invoice->client->city}}-{{$invoice->client->country}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone</strong> </td>
                                            <td>: {{$invoice->client->mobile}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong> </td>
                                            <td>: {{$invoice->client->email}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="invoice">
                                <table>
                                    <thead>
                                        <tr>
                                            <th colspan="2">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colsapn="2">&nbsp;</td></tr>
                                        <tr>
                                            <td><strong>Invoice Number</strong></td>
                                            <td>: {{$invoice->inv_number}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date</strong></td>
                                            <td>: {{$invoice->inv_date}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Period</strong></td>
                                            <td>: {{$invoice->from_date.' to '. $invoice->to_date}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Due On</strong></td>
                                            <td>: {{$invoice->due_date}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><br><br>
                        <div class="title-section text-center">
                            <h2>INVOICE</h2>
                        </div><br>
                        <div class="items-section">
                            <table class="table table-bordered">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th style="max-width: 5%;">Sl #</th>
                                        <th style="min-width: 25%; max-width: 30%;">Description</th>
                                        <th >Prefix</th>
                                        <th class="text-right">Call Rate</th>
                                        <th class="text-right">Calls</th>
                                        <th class="text-right">Duration (min.)</th>
                                        <th class="text-right">Amount (USD)</th>
                                    </tr>
                                </thead>
                                <tbody class="column-table">
                                    @foreach ($invoice->items as $item)
                                    <tr>
                                        <td>{{sprintf('%02d', $loop->count)}}</td>
                                        <td>{{$item->description}}</td>
                                        <td>{{$item->prefix}}</td>
                                        <td class="text-right">{{number_format($item->rate, 2)}}</td>
                                        <td class="text-right">{{number_format($item->total_calls)}}</td>
                                        <td class="text-right">{{number_format($item->total_duration, 2)}}</td>
                                        <td class="text-right">{{number_format($item->total_cost, 2)}}</td>
                                    </tr>
                                        @if ($loop->count < 3)
                                            <tr>
                                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total</th>
                                        <th class="text-right">{{number_format($invoice->total_calls)}}</th>
                                        <th class="text-right">{{number_format($invoice->total_duration, 2)}}</th>
                                        <th class="text-right">{{number_format($invoice->sub_total, 2)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="summary-section row justify-content-between">
                            <div class="col-6 payment-overview">
                                <table class="">
                                    <tbody>
                                        <tr>
                                            <td>Customer Currency</td>
                                            <td>&nbsp;&nbsp;&nbsp;:</td>
                                            <th><strong> &nbsp;&nbsp;&nbsp;{{$invoice->client->currency->name}}</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Invoice Currency</td>
                                            <td>&nbsp;&nbsp;&nbsp;:</td>
                                            <th><strong> &nbsp;&nbsp;&nbsp;{{$invoice->inv_currency}}</strong></th>
                                        </tr>
                                        <tr>
                                            <td>1 USD</td>
                                            <td> &nbsp;&nbsp;= </td>
                                            <th> &nbsp;&nbsp;&nbsp;{{number_format($invoice->client->currency->ratio, 2) . ' ' . strtoupper($invoice->client->currency->name)}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6 summary-table">
                                <table class="table table-bordered table-striped">
                                    <tbody class="text-right">
                                        <tr>
                                            <td>Sub Total</td>
                                            <th>{{number_format($invoice->sub_total, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <td>VAT (0%)</td>
                                            <th>{{number_format($invoice->vat_total, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <td>Total inc. VAT</td>
                                            <th>{{number_format($invoice->total_inc_vat, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <td>Invoice Total</td>
                                            <th>{{number_format($invoice->inv_total, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">
                                                @php
                                                    $speller = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                                @endphp
                                                In words: {{strtoupper($speller->format($invoice->inv_total)). ' USD'}}
                                            </th>
                                        </tr>

                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                        </div><br><br>
                        <p>
                            <span class="text-danger">{{'Note : '}}</span> {{'This is a computer generated Invoice,No signature is required.
                                                '}}
                        </p>
                    </div>
                </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>
@endsection


@push('scripts')
    <script>
        function printDiv(divName) {
            var originalContents = $('body').clone(true);
            var styleA4 = document.body.classList.toggle('a4');
            var printContents = document.getElementById(divName).innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            var removeA4 = document.body.classList.toggle('a4');
            $('body').replaceWith(originalContents.clone(true));
            // window.location.reload(true);
        }

    </script>

@endpush
