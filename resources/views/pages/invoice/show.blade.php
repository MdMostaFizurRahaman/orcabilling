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

    <!--Preloader-->

    @if(session()->has("success"))
    <div class="alert alert-bordered alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        <strong><i class="fa fa-check-circle"></i> Success!</strong> {{session()->get('success')}}
    </div>
    @endif

    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex no-block align-items-center m-b-0">
                        <div class="card-title">
                            <h5>Invoice</h5>
                            <small>{{request()->from_date.' to '. request()->to_date}}</small>
                        </div>
                        <div class="ml-auto">
                            <div class="btn-group">
                                <a href="{{route('failed-calls.summary.export', array_merge(request()->all(), ['mime' => 'csv']))}}" data-toggle="tooltip" title="Export to csv"  class="btn btn-rounded btn-info"> <i class="fas fa-file-pdf"></i> PDF</a>
                                <a href="{{route('failed-calls.summary.export', array_merge(request()->all(), ['mime' => 'csv']))}}" data-toggle="tooltip" title="Export to csv"  class="btn btn-rounded btn-info"> <i class="fas fa-print"></i> Print</a>
                                {{-- <a class="btn" href="" target="_blank" title="Export as PDF"><i class="fas fa-file-pdf"></i></a>
                                <a class="btn" href="" target="_blank" title="Print Invoice"><i class="fas fa-print"></i></a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            {{-- <div class="card card-primary card-outline">
                @if()
                <div class="card-header">
                    <h3 class="card-title">{{ 'Generate Invoice' }}</h3>
                    <div class="card-tools">
                        <a class="btn btn-tool" href="" target="_blank" title="Export as PDF"><i class="fas fa-file-pdf"></i></a>
                        <a class="btn btn-tool" href="" target="_blank" title="Print Invoice"><i class="fas fa-print"></i></a>
                        <button class="btn btn-tool" data-widget="collapse" title="Minimize window"><i class="far fa-minus-square"></i></button>
                    </div>
                </div> --}}
                <div class="card-body">
                    <div id="invoice">
                        <div class="top-bar">
                            <div class="company">
                                <span class="name">{{config('app.name')}}</span>
                                <img class="logo" src="{{asset('/resource/img/icons/favicon.png')}}"  alt="LogicBag-logo">
                            </div>

                            <div class="date">Date: {{'created_at'}}</div>
                        </div><br>

                        {{-- <div class="title-section">
                            INVOICE
                        </div><br><br> --}}

                        <div class="address-section">
                            <div class="from">
                                From<br>
                                <span class="title"><strong>{{config('app.name')}}</strong></span><br>
                                <span>
                                    Chowrongi Bhaban (4th floor),<br>
                                    124/A, New Elephant Road,<br>
                                    Dhaka-1205. Phone: 01847-277630<br>
                                    Email: {{'info@logicbag.com.bd'}}<br>
                                </span>
                            </div>
                            <div class="to">
                                To<br>
                                <span class="title"><strong>{{'name'}}</strong></span><br>
                                <span>
                                    {{'street_address'}},<br>
                                    {{'union'}}, {{'city'}}-{{'zipcode'}},<br>
                                    Phone: {{'phone'}}<br>
                                    Email: {{'email'}}<br>
                                </span>
                            </div>
                            <div class="invoice-id">
                                <span class="invoice_number"><strong>Invoice Number:</strong> {{'invoice_number'}}</span><br><br>
                            <span class="order_id"><strong>Order ID:</strong> {{'order_number'}}><br>
                                <span class="order_date"><strong>Order Date:</strong> {{'created_at'}}</span><br>
                                <span class="payment_mode"><strong>Payment Mode:</strong> {{'mode'}}</span><br>
                            </div>
                        </div><br>

                        <div class="items-section">
                            <h2>INVOICE</h2>
                            <table>
                                <colgroup>
                                    <col class="serial">
                                    <col class="title">
                                    <col class="model">
                                    <col class="color">
                                    <col class="quantity">
                                    <col class="rate">
                                    <col class="subtotal">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th width="5%">Sl.</th>
                                        <th width="45%">Item</th>
                                        <th width="10%">Model</th>
                                        <th width="10%">Color</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit Rate</th>
                                        <th width="10%">Subtotal</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @php
                                        $sl = $price = $subTotal = $deliveryFee = 0;
                                    @endphp
                                    @foreach ( $item)
                                    @php
                                        $price = ($item->quantity * $item->price);
                                        $deliveryFee += ($item->quantity * 100);
                                        $subTotal += $price;
                                        $sl += 1;
                                    @endphp
                                    <tr>
                                        <td>{{sprintf('%02d', $sl)}}</td>
                                        <td style="text-align: left;">&nbsp;&nbsp;{{$item->product->title}}</td>
                                        <td>{{$item->model}}</td>
                                        <td>{{$item->variant->color}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td style="text-align: right;">{{number_format($item->price, 2)}}</td>
                                        <td style="text-align: right; font-weight: bold;">{{number_format($price, 2)}}</td>
                                    </tr>
                                    @endforeach
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                </tbody>
                                <tfoot>
                                    @php
                                        $tax = ($subTotal * 0.05);
                                        $shipping = 100;
                                        $grandTotal = $subTotal + $tax + $shipping;
                                        $due = $grandTotal - ->payment;
                                    @endphp
                                    <tr>
                                        <th colspan="5" class="value">Subtotal</th>
                                        <th colspan="2" class="value">{{number_format($subTotal, 2)}}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="value">{{'Tax (0.5%)'}}</th>
                                        <th colspan="2" class="value">{{number_format($tax, 2)}}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="value">{{'Delivery Fee'}}</th>
                                        <th colspan="2" class="value">{{number_format(100, 2)}}</th>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                        <div class="payment-section">
                            <div class="payment-methods">
                                <p>Payment Methods:</p>
                                <img title="Master card" src="{{asset('/backend/img/credit/mastercard.png')}}"  alt="Master card">
                                <img title="Paypal" src="{{asset('/backend/img/credit/paypal2.png')}}"  alt="Paypal">
                                <img title="Visa" src="{{asset('/backend/img/credit/visa.png')}}"  alt="Visa">
                                <img title="Bkash" src="{{asset('/backend/img/credit/bkash.jpg')}}"  alt="Bkash">
                                <img title="Cash on Delivery" src="{{asset('/backend/img/credit/cash.png')}}"  alt="Cash on Delivery"><br><br>

                                <p>Pay full cash after you have received the product in hand and ensured that the delivered product is appropriate.</p>
                            </div>
                            <div class="payment-summary">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Payment Due Date</th>
                                            <th class="value">{{'created_at'}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Grand Total</td>
                                            <td class="value">{{'total'}}</td>
                                        </tr>
                                        <tr>
                                            <td>Paid Amount</td>
                                            <td class="value">{{'total'}}</td>
                                        </tr>
                                        <tr>
                                            <td>Due Amount</td>
                                            <td class="value">{{'total'}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                        </div><br><br>
                        <div class="signature-section">
                            <div class="authorized">
                                <p>Authorized Signature</p>
                            </div>
                            <div class="customer">
                                <p>Customer's Signature</p>
                            </div>
                        </div>
                        <div class="action-section">
                            <div class="print">
                                <a class="btn btn-secondary" href=""><i class="fas fa-print"></i> Print</a>
                            </div>
                            <div class="export">
                                <a class="btn btn-danger" href=""><i class="far fa-file-pdf"></i> Export as PDF</a>
                            </div>
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
