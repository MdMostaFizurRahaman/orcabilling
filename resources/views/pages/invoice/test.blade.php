<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice | {{$company->company_name}}</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <link href="{{asset('theme')}}/assets/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            margin: -15px 50px 40px 72px;
        }

        .page-break {
		    page-break-after: always;
		}


		body {
			font-family: "Poppins";
			font-weight: 400;
            font-size: 12px;
        }

        strong, th {
			font-weight: 500;
        }

        h3, h4 {
			font-weight: 600;
        }

        .logo {
            vertical-align: baseline;
            transform: translateY(15px);
        }

        .name h3 {
            transform: translateY(15px);
        }

        .address {
            /* transform: translateY(-15px); */
        }

        .company,
        .address-section {
            width: 100%;
        }

        .client, .invoice
        {
			display: inline-block;
			width: 50%;
		}

        .column-table tr {
            border-top: 0;
            border-bottom: 0;
        }

        .column-table tr td,
        .column-table tr th
        {
            border-top: 0;
            border-bottom: 0;
            border-right: 1px gray;
        }

        .general-table tr,
        .general-table th,
        .general-table td
        {
            vertical-align: baseline;
            max-height: 16px;
            line-height: 12px;
            padding: 0 2px;
        }

        .period-table {
            /* margin-right: auto; */
        }

        .period-table tr,
        .period-table th,
        .period-table td
        {
            max-height: 16px;
            line-height: 12px;
            padding: 0 2px;
        }

        .column-table tr,
        .column-table th,
        .column-table td
        {
            max-height: 22px;
            line-height: 14px;
            padding: 2px 10px;
        }

        .currency-table tr,
        .currency-table th,
        .currency-table td,

        .summary-table tr,
        .summary-table th,
        .summary-table td
        {
            max-height: 22px;
            line-height: 14px;
            padding: 2px 5px
        }

        thead tr,
        thead th,
        thead td,
        tfoot tr,
        tfoot th,
        tfoot td
        {
            font-size: 12px;
            line-height: 15px;
            max-height: 25px;
            padding: 0px 5px 2px;
            margin: 0
        }
    </style>
    @php
        $subTotal = $invoiceSummary['totalCost'];
        $vatTotal = $subTotal * config('app.vat');
        $totalIncludingVat = $subTotal + $vatTotal;
        $previousBalance = $client->accoutn_state ?: 0;
        $grandTotal = $totalIncludingVat + ($client->accoutn_state < 0 ? -($client->accoutn_state) : 0);
        $dueAmount = number_format($grandTotal, 4);
        $speller = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    @endphp
</head>
<body>
    <div class="container-fluid loaded no-gutters p-0" id="invoice">

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row justify-content-center no-gutters">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="">
                            <div class="top-bar" style="border-bottom: 2px gray">
                                <table class="company">
                                    <tr>
                                        <td class="">
                                            <img class="logo" src="{{asset($company->logo)}}" width="100" height="30" alt="LogicBag-logo">
                                        </td>
                                        <td class="text-right">
                                            <div class="name"><h3 class="mb-0">{{strtoupper($company->company_name)}}</h3></div>
                                            <div class="address" style="top: 90.5px;"><strong>{{$company->postal_address}}</strong></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <hr class="mt-0 mb-0">
                            <hr class="mt-0 mb-0">
                            <div class="address-section">
                                <div class="client">
                                    <table class=" general-table" style="margin-right: auto;">
                                        <tbody>
                                            <tr><td colspan="2">To</td></tr>
                                            <tr><td colspan="2">{{$client->name}}</td></tr>
                                            <tr><td colspan="2">
                                                {{$client->address}}, {{$client->zip}},<br>
                                                {{$client->city}}, {{$client->country}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Phone : {{$client->mobile}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Email : {{$client->email}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="invoice">
                                    <table class="period-table" style="margin-left: auto;">
                                        <tbody>
                                            <tr><td colsapn="2">&nbsp;</td></tr>
                                            <tr>
                                                <td>Invoice Number</td>
                                                <td>: {{$invoiceSummary['invoiceNumber']}}</td>
                                            </tr>
                                            <tr>
                                                <td>Date</td>
                                                <td>: {{$invoiceSummary['invoiceDate']}}</td>
                                            </tr>
                                            <tr>
                                                <td>Period</td>
                                                <td>: {{$invoiceSummary['invoiceFromDate'].' to '. $invoiceSummary['invoiceToDate']}}</td>
                                            </tr>
                                            <tr>
                                                <td>Due On</td>
                                                <td>: {{$invoiceSummary['invoiceDueDate']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><br><br><br><br><br><br>

                            <div class="title-section text-center">
                                <h4 class="text-center">INVOICE</h4>
                            </div>
                            <div class="items-section">
                                <table class="table table-bordered">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th class="serial">Sl #</th>
                                            <th class="description">Description</th>
                                            <th >Prefix</th>
                                            <th class="text-right">Rate</th>
                                            <th class="text-right">Calls</th>
                                            <th class="text-right">Duration (m)</th>
                                            <th class="text-right">Amount (USD)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="column-table">
                                        @foreach ($invoiceSummary['groupedCallsSummary'] as $summary)
                                        <tr>
                                            <td>{{sprintf('%02d', $loop->count)}}</td>
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
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">{{'Total'}}</th>
                                            <th class="text-right">{{$invoiceSummary['totalCalls']}}</th>
                                            <th class="text-right">{{$invoiceSummary['totalDuration']}}</th>
                                            <th class="text-right">{{number_format($invoiceSummary['totalCost'], 2)}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="summary-section">
                                <table class="table table-borderless">
                                    <tr>
                                        <td style="width: 30%;">
                                            <div class="general-table">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td>Customer Currency</td>
                                                            <td> : &nbsp;&nbsp;&nbsp;{{$client->currency->name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Invoice Currency</td>
                                                            <td> : &nbsp;&nbsp;&nbsp;{{'USD'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{number_format(1, 2) . ' USD'}}</td>
                                                            <td> = &nbsp;&nbsp;&nbsp;{{number_format($client->currency->ratio, 2) . ' ' . strtoupper($client->currency->name)}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                        <td style="width: 50%;">
                                            <div class="summary-table">
                                                <table class="table table-bordered table-striped">
                                                    <tbody class="text-right">
                                                        <tr>
                                                            <td>Sub Total</td>
                                                            <td>
                                                                <strong>
                                                                    {{number_format($subTotal, 4) . ' USD'}}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>VAT (0%)</td>
                                                            <td>
                                                            <strong>
                                                                {{number_format($vatTotal, 4) . ' USD'}}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total inc. VAT</td>
                                                            <td>
                                                                <strong>
                                                                {{number_format($totalIncludingVat, 4) . ' USD'}}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Previous Balance</td>
                                                            <td>
                                                                <strong>
                                                                    {{number_format($previousBalance, 4) . ' USD'}}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Invoice Total</td>
                                                            <td>
                                                                <strong>
                                                                    {{number_format($grandTotal, 4) . ' USD'}}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Amount Due</td>
                                                            <td>
                                                                <strong>
                                                                    {{number_format($grandTotal, 4) . ' USD'}}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                    In Words: <strong>{{strtoupper($speller->format($dueAmount)). ' USD'}}</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>

                                                    </tfoot>
                                                </table>
                                            </div>
                                        </tr>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <span class="text-danger">{{'Note : '}}</span> {{'This is a computer generated Invoice,No signature is required.
                                                    '}}
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->

    </div>
</body>
</html>
