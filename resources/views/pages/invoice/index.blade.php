@extends('layouts.app')

@section('title')
    Companies
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link href="{{asset('theme')}}/assets/libs/toastr/toast.min.css" rel="stylesheet">
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
                            <h4 class="card-title">All Invoices</h4>
                            <div class="ml-auto">

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered wrap display">
                                <thead>
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Invoice Amount</th>
                                        <th>Added By</th>
                                        <th>Download</th>
                                        <th>View</th>
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
<script src="{{asset('theme')}}/assets/libs/toastr/toast.min.js"></script>
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
                ajax:  "{{route('invoice.datatable')}}",
                columnDefs: [
                                { "className": "text-right", "targets": [3] }
                            ],
                columns: [
                            { data: 'inv_number', name: 'inv_number' },
                            { data: 'client', name: 'client' },
                            { data: 'inv_date', name: 'inv_date' },
                            { data: 'inv_total', name: 'inv_total' },
                            // { data: 'tariff_id', name: 'tariff_id', render:function(data, type, row){
                            //         return "<a href='" + route('rate.index', row.tariff_id) + "' class='view' data-id='" + row.tariff_id +"'>" + row.tariff_id + "</a>"
                            //     }
                            // },
                            { data: 'user', name: 'user' },
                            { data: 'download', name: 'download' },
                            { data: 'print', name: 'print' },
                            { data: 'delete', name: 'delete' },
                        ],
                "drawCallback": function( settings ) {
                    $(".delete").click(function() {
                        event.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: "Are you sure?",
                            text: "You won\'t be able to revert this!",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, delete it!"
                            }).then((result) => {
                            if (result.value) {
                                var url = $(this).attr('href');
                                $.ajax({
                                    url: url,
                                    type: "GET",
                                    success: function(response){
                                        responseToast(response)
                                    }
                                });
                            }
                        })
                    });
                }
            });
        }

        function responseToast(response){
            $.toast({
                heading: response.status,
                text: response.message,
                icon: response.status,
                loader: true,
                loaderBg: '#9EC600',  // To change the background
                position: 'bottom-right',
                hideAfter : 2000,
                showHideTransition: 'slide',
                // transition : 'slide',
            })
            setTimeout(function(){
                window.location.reload(true);
                // window.location.replace("http://logicbag.com.bd/backend/products");
            }, 2000)
        }

</script>
@endpush
