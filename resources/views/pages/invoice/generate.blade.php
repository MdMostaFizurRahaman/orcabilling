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
                    <div class="align-items-center">
                        <h4 class="card-title">Generate Invoice</h4>
                    </div>
                    {{-- <div class="d-flex no-block align-items-center">
                        <h4 class="card-title">Select Date</h4>
                    </div> --}}
                </div>
                <div class="card-body">
                    <form action="{{route('invoice.generate')}}" method="post">
                    @csrf
                    <div class="row justify-content-md-center">
                        {{-- General Information --}}
                        <div class="col-md-6">
                            <div class="card animated slideInRight">

                                <div class="card-body mb-0">
                                    <div class="form-group">
                                        <label for="client_id"> Client</label>
                                        <select id="client_id" name="client_id" class="form-control" required>
                                            <option value="" selected disabled>Select Client</option>
                                            <option v-for='client in clients' v-bind:value='client.id'> @{{client.username}}</option>
                                        </select>
                                        @error('client_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="fromDate"> From Date</label>
                                        <input id="fromDate" type='text' name="from_date" class="form-control" required />
                                        @error('from_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="toDate"> To Date</label>
                                        <input id="toDate" type='text' id='toDate' name="to_date" class="form-control" required />
                                        @error('to_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tariff_prefix"> Prefix</label>
                                        <input id="tariff_prefix" type="text" name="tariff_prefix" class="form-control">
                                        @error('tariff_prefix')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="company_id"> Company</label>
                                        <select id="company_id" name="company_id" class="form-control" required>
                                            <option value="" disabled selected>Select Company</option>
                                            <option v-for='company in companies' v-bind:value='company.id'> @{{company.company_name}}</option>
                                        </select>
                                        @error('company_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-0 @error('generate_invoice'){{'is-invalid'}}@enderror">
                                        {!! Form::label('generate_invoice', 'Add to History', ['class' => '']) !!}
                                        {!! Form::checkbox('generate_invoice', '1', false, ['class' => '']) !!}
                                        @error('generate_invoice')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="form-group text-right mb-0">
                                        <button type="submit" class="btn btn-primary btn-rounded form-control">Generate</button>
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
</div>

@endsection


@push('scripts')

    @include('sweetalert::alert')

    {{-- Datetime Picker --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
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
            // format: 'YYYY-MM-DD',
            // useCurrent: 'month',
            format: 'YYYY-MM-DD HH:mm',
            // inline: true,
            // sideBySide: true
        });

        $('#toDate').datetimepicker({
            // useCurrent: 'day',
            showClose: true,
            showClear: true,
            // format: 'YYYY-MM-DD',
            // format: 'YYYY-MM-DD HH:mm',
            format: 'YYYY-MM-DD 23:59:59',
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
