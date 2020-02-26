<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600&display=swap" rel="stylesheet">
    <link href="{{asset('theme')}}/assets/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
    <style>
        .page-break {
		    page-break-after: always;
		}

		@page {
            margin: 20px 50px 40px 72px;
        }

		body {
			font-family: "Poppins";
			font-weight: 300;
			font-size: 12px;
        }
        .logo {
            vertical-align: baseline;
        }
        .company,
        .address-section {
            width: 100%;
        }

        .client, .invoice,
        .payment-overview, .summary-table
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
        .general-table tr, .general-table th, .general-table td
        {
            max-height: 15px;
            line-height: 15px;
            padding: 2px 4px;
        }

        .summary-table tr,
        .summary-table th,
        .summary-table td
        {
            max-height: 20px;
            line-height: 20px;
            padding: 2px 4px;
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
                                            <div class="name"><h2 class="mb-0">{{strtoupper($company->company_name)}}</h2></div>
                                            <div class=""><h6> {{$company->postal_address}}</h6></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <hr class="mt-0 mb-0">
                            <hr class="mt-0"><br>
                            <div class="address-section">
                                <div class="client">
                                    <table class="general-table">
                                        <tr><td colspan="2">To</td></tr>
                                        <tr><td colspan="2"><strong>{{$client->name}}</strong></td></tr>
                                        <tr><td colspan="2">
                                            {{$client->address}}, {{$client->zip}}<br>
                                            {{$client->city}}-{{$client->country}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone</strong></td>
                                            <td> : {{$client->mobile}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td> : {{$client->email}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="invoice">
                                    <table class="general-table" style="margin-left: auto;">
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
                                    </table>
                                </div>
                            </div>
                            <div class="title-section text-center">
                                <h2 class="">INVOICE</h2>
                            </div>
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
                                            <th colspan="4"></th>
                                            <th class="text-right">{{$invoiceSummary['totalCalls']}}</th>
                                            <th class="text-right">{{$invoiceSummary['totalDuration']}}</th>
                                            <th class="text-right">{{number_format($invoiceSummary['totalCost'], 2)}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="summary-section">
                                <div class="payment-overview">
                                    <table class="">
                                        {{-- <tbody> --}}
                                            <tr>
                                                <td>Customer Currency</td>
                                                <td>&nbsp;&nbsp;&nbsp;:</td>
                                                <td> &nbsp;&nbsp;&nbsp;{{$client->currency->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Invoice Currency</td>
                                                <td>&nbsp;&nbsp;&nbsp;:</td>
                                                <td> &nbsp;&nbsp;&nbsp;{{'USD'}}</td>
                                            </tr>
                                            <tr>
                                                <td>1 USD</td>
                                                <td> &nbsp;&nbsp;= </td>
                                                <td> &nbsp;&nbsp;&nbsp;{{number_format($client->currency->ratio, 2) . ' ' . strtoupper($client->currency->name)}}</td>
                                            </tr>
                                        {{-- </tbody> --}}
                                    </table>
                                </div>
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
                                                    <strong>
                                                        In words: {{strtoupper($speller->format($dueAmount)). ' USD'}}
                                                    </strong>
                                                </td>
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
