@extends('layouts.app')

@section('title')
    Companies
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid" id="client">

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
            <div class="col-lg-12 col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center m-b-30">
                            <h4 class="card-title">All Companies</h4>
                            <div class="ml-auto">
                                <div class="btn-group">
                                    <a href="{{route('company.settings.create')}}" class="btn btn-rounded btn-dark">Add New Company</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered wrap display">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>city</th>
                                        <th>Country</th>
                                        <th>Logo</th>
                                        <th>View</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
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

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
        $(function(){
            getCompanies();
            $('[data-toggle="tooltip"]').tooltip()
        })

        function getCompanies(){

        $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "order": [[ 0, "desc" ]],
                ajax:  "{{route('company.settings.datatable')}}",
                columns: [
                            { data: 'id', name: 'id' },
                            { data: 'company_name', name: 'company_name' },
                            { data: 'city', name: 'city' },
                            { data: 'country', name: 'country' },
                            // { data: 'tariff_id', name: 'tariff_id', render:function(data, type, row){
                            //         return "<a href='" + route('rate.index', row.tariff_id) + "' class='view' data-id='" + row.tariff_id +"'>" + row.tariff_id + "</a>"
                            //     }
                            // },
                            { data: 'logo', name: 'logo' },
                            { data: 'view', name: 'view' },
                            { data: 'edit', name: 'edit' },
                            { data: 'delete', name: 'delete' },
                        ],
        });
    }

</script>
@endpush
