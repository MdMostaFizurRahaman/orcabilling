@extends('layouts.app')

@section('title')
    Generate Invoice
@endsection

@push('styles')
    <link href="{{asset('theme')}}/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid loaded" id="invoice">

    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="d-flex no-block align-items-center m-b-0">
                        <div class="card-title">
                            <h5>Invoice</h5>
                            <small>{{$invoiceSummary['invoiceFromDate'].' to '. $invoiceSummary['invoiceToDate']}}</small>
                        </div>
                        <div class="ml-auto">
                            <div class="btn-group">
                                <a href="{{route('invoice.download')}}" data-toggle="tooltip" title="Export to pdf"  class="btn btn-rounded btn-danger"> <i class="fas fa-file-pdf"></i> PDF</a>
                                <a href="{{route('failed-calls.summary.export', array_merge(request()->all(), ['mime' => 'csv']))}}" data-toggle="tooltip" title="Export to csv"  class="btn btn-rounded btn-secondary"> <i class="fas fa-print"></i> Print</a>
                                {{-- <a class="btn" href="" target="_blank" title="Export as PDF"><i class="fas fa-file-pdf"></i></a>
                                <a class="btn" href="" target="_blank" title="Print Invoice"><i class="fas fa-print"></i></a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                    <div id="invoice">
                        <div class="top-bar">
                            <div class="company d-flex justify-content-between">
                                <div class="">
                                    <img class="logo" src="{{asset($company->logo)}}" width="150" height="50" alt="LogicBag-logo">
                                </div>
                                <div class="d-flex-column text-right">
                                    <div class="name h3">{{strtoupper($company->company_name)}}</div>
                                    <div class="h6">{{$company->postal_address}}</div>
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
                                            <td colspan="2"><strong>{{$client->name}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">{{$client->address}}, {{$client->zip}}<br>
                                                {{$client->city}}-{{$client->country}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone</strong> </td>
                                            <td>: {{$client->mobile}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong> </td>
                                            <td>: {{$client->email}}</td>
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
                                        <tr></tr>
                                        <tr>
                                            <td><strong>Invoice Number</strong></td>
                                            <td>: {{$invoiceSummary['invoiceNumber']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date</strong></td>
                                            <td>: {{$invoiceSummary['invoiceDate']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Period</strong></td>
                                            <td>: {{$invoiceSummary['invoiceFromDate'].' to '. $invoiceSummary['invoiceToDate']}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Due On</strong></td>
                                            <td>: {{$invoiceSummary['invoiceDueDate']}}</td>
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
                                    @foreach ($invoiceSummary['groupedCallsSummary'] as $summary)
                                    <tr>
                                        <th>{{sprintf('%02d', $loop->count)}}</th>
                                        <td>{{$summary->description}}</td>
                                        <td>{{$summary->prefix}}</td>
                                        <td class="text-right">{{number_format($summary->call_rate, 2)}}</td>
                                        <td class="text-right">{{number_format($summary->totalCalls)}}</td>
                                        <td class="text-right">{{number_format($summary->totalDuration, 2)}}</td>
                                        <td class="text-right">{{number_format($summary->totalCost, 2)}}</td>
                                    </tr>
                                        @if ($loop->count < 3)
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th class="text-right">{{$invoiceSummary['totalCalls']}}</th>
                                        <th class="text-right">{{$invoiceSummary['totalDuration']}}</th>
                                        <th class="text-right">{{number_format($invoiceSummary['totalCost'], 2)}}</th>
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
                                            <th><strong> &nbsp;&nbsp;&nbsp;{{$client->currency->name}}</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Invoice Currency</td>
                                            <td>&nbsp;&nbsp;&nbsp;:</td>
                                            <th><strong> &nbsp;&nbsp;&nbsp;{{'USD'}}</strong></th>
                                        </tr>
                                        <tr>
                                            <td>1 USD</td>
                                            <td> &nbsp;&nbsp;= </td>
                                            <th> &nbsp;&nbsp;&nbsp;{{number_format($client->currency->ratio, 2) . ' ' . strtoupper($client->currency->name)}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6 summary-table">
                                <table class="table table-bordered table-striped">
                                    @php
                                        $subTotal = $invoiceSummary['totalCost'];
                                        $vatTotal = $subTotal * config('app.vat');
                                        $totalIncludingVat = $subTotal + $vatTotal;
                                        $previousBalance = $client->accoutn_state ?: 0;
                                        $grandTotal = $totalIncludingVat + ($client->accoutn_state < 0 ? -($client->accoutn_state) : 0);
                                        $dueAmount = number_format($grandTotal, 4);
                                        $speller = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                    @endphp
                                    <tbody class="text-right">
                                        <tr>
                                            <td>Sub Total</td>
                                            <th>{{number_format($subTotal, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <td>VAT (0%)</td>
                                            <th>{{number_format($vatTotal, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <td>Total inc. VAT</td>
                                            <th>{{number_format($totalIncludingVat, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <td>Previous Balance</td>
                                            <th>{{number_format($previousBalance, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <td>Invoice Total</td>
                                            <th>{{number_format($grandTotal, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <td>Amount Due</td>
                                            <th>{{number_format($grandTotal, 4) . ' USD'}}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">
                                                In words: {{strtoupper($speller->format($dueAmount)). ' USD'}}
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
    {{-- Datetime Picker --}}
    <script src="{{asset('theme')}}/assets/libs/moment/moment.js"></script>
    <script src="{{asset('theme')}}/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    {{-- Datetime Picker --}}

<script>


    // Datetiem picker
    $(function ()
    {
        // Datetiem picker
        $('#fromDate').datetimepicker({
            showClose: true,
            showClear: true,
            format: 'YYYY-MM-DD',
            useCurrent: 'month',
            // format: 'YYYY-MM-DD HH:mm',
            // inline: true,
            // sideBySide: true
        });

        $('#toDate').datetimepicker({
            useCurrent: 'day',
            showClose: true,
            showClear: true,
            format: 'YYYY-MM-DD',
        });

    });

// Vue js One page app
    const app = new Vue({
        el: '#invoice',
        data:{
            clients: [],
            companies: [],
        },
        methods:{
           getCompanies(){
                axios.get('{{route("company.all")}}')
                    .then(res=>{
                        this.companies = res.data
                    })
                    .catch(e=>alert(e))
            },
            getClients(){
                axios.get('{{route("clients")}}')
                    .then(res=>{
                        this.clients = res.data
                    })
                    .catch(e=>alert(e))
            },
        },
        mounted() {
            this.getCompanies();
            this.getClients();
            $('#invoice').addClass('loaded');
        }
    });


</script>
@endpush
