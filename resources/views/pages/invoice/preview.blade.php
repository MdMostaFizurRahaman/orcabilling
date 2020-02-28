<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link href="{{asset('theme')}}/assets/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('theme')}}/dist/css/custom.css" rel="stylesheet">
    <style>
        .page-break {
		    page-break-after: always;
        }

        @page {
            margin: 0 calc((100vw - 212mm)/2);
            background-color: black;
        }

        .a4 {
            margin: 0 calc((100vw - 220mm)/2);
            height: 297mm;

            background-color: black;
        }

        #invoice {
            width: 100%;
            height: 100%;
            padding: 30px 72px;
        }
        .address{
            max-width: 60%;
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
        .summary-table tr,
        .summary-table th,
        .summary-table td
        {
            height: 30px;
            line-height: 25px;
            padding: 5px 10px;
        }
    </style>
</head>
<body class="a4">

    <div id="invoice" class="bg-light">
        <div class="top-bar">
            <div class="company d-flex justify-content-between">
                <div class="">
                    <img class="logo" src="{{asset($invoice->company->logo)}}" style="max-width: 150px;" alt="{{config('app.name')}}-logo">
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
                        <th>{{sprintf('%02d', $loop->count)}}</th>
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

</body>
</html>
