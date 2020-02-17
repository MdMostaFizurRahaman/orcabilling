@extends('layouts.app')

@section('title')
    Call Summary
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
                        <h4 class="card-title">Access Logs Panel</h4>
                    </div>
                    <form action="{{route('system.access-log.fetch')}}" method="GET">
                    @csrf
                    <div class="row">
                        {{-- General Information --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex no-block align-items-center">
                                        <h4 class="card-title">User</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Users</label>
                                        <select name="user_id" class="form-control">
                                            <option value="" selected disabled>All</option>
                                            <option v-for='user in users' v-bind:value='user.id'> @{{user.username}}</option>
                                        </select>
                                        @error('user_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

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
                                </div>
                                <div class="card-footer text-center">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary btn-md btn-rounded">Get Logs</button>
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
            users:[],
        },
        methods:{
           getClients(){
                axios.get('{{route("clients")}}')
                    .then(res=>{
                        this.clients = res.data
                    })
                    .catch(e=>alert(e))
            },
            getUsers(){
                axios.get('{{route("users")}}')
                    .then(res=>{
                       this.users = res.data;
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
        },
        mounted() {
            $('#summary').addClass('loaded');
            this.getUsers();
            this.getClients();
        }
    });


</script>
@endpush
