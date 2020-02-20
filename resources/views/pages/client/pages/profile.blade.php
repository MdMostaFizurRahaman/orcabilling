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
                    <h4 class="card-title">My Account</h4>
                    </div>
                    <table id="product" class="table table-bordered table-striped mb-3">
                        <thead>
                            <tr>
                                <th class="text-right">Property</th>
                                <th class="text-left">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><th class="text-right">Name :</th><td class="text-left"> <i class="icon-User"></i> <strong>{{ $client->name }}</strong></td></tr>
                            <tr><th class="text-right">Balance (Credit Amount) :</th><td class="text-left"> <i class="icon-User"></i> <strong>{{ number_format($client->account_state, 4) }}</strong></td></tr>
                            <tr><th class="text-right">Rates :</th><td class="text-left"> <strong><a href="{{route('rate.index', $client->tariff->id)}}" class=""><i class="fa fa-book"></i> {{$client->tariff->name}}</a></strong></td></tr>
                            <tr>
                                <th class="text-right">IPs :</th>
                                <td class="text-left"> @foreach($client->ips as $ip){{$ip->ip}}<br>@endforeach</td>
                            </tr>
                            <tr><th class="text-right">Status :</th>
                                <td class="text-left">
                                @if($client->status)
                                    <i class="fas fa-check text-success"></i> <strong>{{'Active'}}</a></strong>
                                @else
                                    <i class="fa fa-times text-danger"></i> <strong>{{'Deacitvated'}}</a></strong>
                                @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
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



</script>

@endpush
