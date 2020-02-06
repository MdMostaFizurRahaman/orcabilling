@extends('layouts.app')

@section('title')
    Client Calls Summary
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
                <div class="card-body">
                    <div class="align-items-center">
                        <h4 class="card-title">Client Calls Summary</h4>
                    </div>
                    <form action="{{route('loss-profit.summary.fetch')}}" method="GET">
                    @csrf
                    <div class="row">
                        {{-- General Information --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex no-block align-items-center">
                                        <h4 class="card-title">Carrier - Router</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Carrier</label>
                                        <select name="client_id" class="form-control">
                                            <option value="" selected>All</option>
                                            <option v-for='client in clients' v-bind:value='client.id'> @{{client.username}}</option>
                                        </select>
                                        @error('client_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Router</label>
                                        <select name="gateway_id" class="form-control">
                                            <option value="" selected>All</option>
                                            <option v-for='gateway in gateways' v-bind:value='gateway.id'> @{{gateway.name}}</option>
                                        </select>
                                        @error('gateway_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for=""> Group By</label>
                                        <table class="table table-striped radio">
                                            <tr>
                                                <th><input checked type="radio" name="group_by" value="monthly"> Monthly<br></th>
                                                <th><input type="radio" name="group_by" value="daily"> Daily<br></th>
                                                <th><input type="radio" name="group_by" value="hourly"> Hourly<br></th>
                                                <th><input type="radio" name="group_by" value="tariff_prefix"> Prefix<br></th>
                                            <tr>
                                                <th><input type="radio" name="group_by" value="tariff_desc"> Tariff Desc.<br></th>
                                                <th><input type="radio" name="group_by" value="ip_number"> IP Address<br></th>
                                                <th><input type="radio" disabled name="group_by" value="account"> Account<br></th>
                                                {{-- <th><input type="radio" name="group_by" value="none"> None<br></th> --}}
                                            </tr>
                                        </table>
                                        @error('group_by')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <div class="d-flex no-block align-items-center">
                                        <h4 class="card-title">Date - Time (TiemZone)</h4>
                                    </div>
                                </div>
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
                                        <label for=""> Sort By</label>
                                        <table class="table table-striped radio">
                                            <tr>
                                                <th><input type="radio" name="sort_by" value="tariffdesc"> Tariff Desc.<br></th>
                                                <th><input checked type="radio" name="sort_by" value="tariff_prefix"> Prefix<br></th>
                                                <th><input type="radio" name="sort_by" value="call_rate"> Rate<br></th>
                                                <th><input type="radio" name="sort_by" value=""> None<br></th>
                                            </tr>
                                        </table>
                                        @error('sort_by')
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
            format: 'YYYY-MM-DD HH:mm',
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
            this.getGateways();
            this.getClients();
        }
    });


</script>
@endpush
