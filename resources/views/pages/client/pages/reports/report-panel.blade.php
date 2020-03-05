@extends('layouts.app')

@section('title')
    Report Table
@endsection

@push('styles')
    <link href="{{asset('theme')}}/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid loaded" id="summary">

    <!--Preloader-->
    @include('layouts.modules.preloader')

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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex no-block align-items-center">
                        <h4 class="card-title">Optimize Report</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('client.report.summary')}}" method="GET">
                    @csrf
                    <div class="row justify-content-md-center">
                        {{-- General Information --}}
                        <div class="col-md-6 animated slideInRight">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for=""> From Date</label>
                                        <input type='text' id='fromDate' data-id="fromDate" name="from_date" class="form-control" />
                                        @error('from_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for=""> To Date</label>
                                        <input type='text' id='toDate' name="to_date" class="form-control" />
                                        @error('to_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for=""> Group By</label>
                                        <table class="table table-striped radio">
                                            <tr>
                                                <th>
                                                    <div class="custom-control custom-radio">
                                                        <input id="group_by1" class="custom-control-input" checked type="radio" name="group_by" value="monthly">
                                                        <label class="custom-control-label" for="group_by1">Monthly</label>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="custom-control custom-radio">
                                                        <input id="group_by2" class="custom-control-input" type="radio" name="group_by" value="daily">
                                                        <label class="custom-control-label" for="group_by2">Daily</label>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="custom-control custom-radio">
                                                        <input id="group_by3" class="custom-control-input" type="radio" name="group_by" value="hourly">
                                                        <label class="custom-control-label" for="group_by3">Hourly</label>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="custom-control custom-radio">
                                                        <input id="group_by4" class="custom-control-input" type="radio" name="group_by" value="tariff_prefix">
                                                        <label class="custom-control-label" for="group_by4">Prefix</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <div class="custom-control custom-radio">
                                                        <input id="group_by5" class="custom-control-input" type="radio" name="group_by" value="tariff_desc">
                                                        <label class="custom-control-label" for="group_by5">Tariff  Desc.</label>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="custom-control custom-radio">
                                                        <input id="group_by6" class="custom-control-input" type="radio" name="group_by" value="ip_number">
                                                        <label class="custom-control-label" for="group_by6">IP Address</label>
                                                    </div>
                                                </th>
                                                <th>
                                                    {{-- <div class="custom-control custom-radio">
                                                        <input id="group_by7" class="custom-control-input" type="radio" disabled name="group_by" value="account">
                                                        <label class="custom-control-label" for="group_by7">Account</label>
                                                    </div> --}}
                                                </th>
                                                {{-- <th><input type="radio" name="group_by" value="none"> None<br></th> --}}
                                            </tr>
                                        </table>
                                        @error('group_by')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary btn-md btn-rounded">Summarize</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->


@endsection


@push('scripts')
    {{-- Datetime Picker --}}
    <script src="{{asset('theme')}}/assets/libs/moment/moment.js"></script>
    <script src="{{asset('theme')}}/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    {{-- Datetime Picker --}}

<script>

    $(function ()
    {
        $('#fromDate').datetimepicker({
            showClose: true,
            showClear: true,
            format: 'YYYY-MM-DD HH:mm',
            useCurrent: 'month',
        });

        var dp = $('#toDate').datetimepicker({
            useCurrent: 'day',
            showClose: true,
            showClear: true,
            format: 'YYYY-MM-DD 23:59:59',
        });

    });

// Vue js One page app
    const app = new Vue({
        el: '#summary',
        data:{
            clients: [],
            gateways:[],
        },
        methods:{
           getClients(){
                axios.get('{{route("clients")}}')
                    .then(res=>{
                        this.clients = res.data
                    })
                    .catch(e=>alert(e))
            },
            getGateways(){
                axios.get('{{route("gateways")}}')
                    .then(res=>{
                       this.gateways = res.data;
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
        },
        mounted() {
            $('#summary').addClass('loaded');
            // this.getGateways();
            // this.getClients();
        }
    });


</script>
@endpush
