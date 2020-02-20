@extends('layouts.app')

@section('title')
    Payment History
@endsection

@push('styles')
    <link href="{{asset('theme')}}/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid loaded" id="payments">

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
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <h4 class="card-title">Payment History</h4>
                        </div>
                        <div class="col-md-6">
                            <form action="{{route('client.payments.history')}}" method="POST" class="form-row ">
                                <div class="col-4">
                                    <input type='text' id='fromDate' name="from_date" class="form-control form-control-sm" placeholder="yyyy-mm-dd" required/>
                                    <div class="alert alert-danger fade show d-none" role="alert">
                                        <span class="error"></span>
                                        <span class="close" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="close">&times;</span>
                                    </div>
                                </div>
                                <div class="col-1 text-center">
                                    <label for="toDate"> To</label>
                                </div>
                                <div class="col-4">
                                    <input type='text' id='toDate' name="to_date" placeholder="yyyy-mm-dd" class="form-control form-control-sm" />
                                    <div class="alert alert-danger fade show d-none" role="alert">
                                        <span class="error"></span>
                                        <span class="close" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="close">&times;</span>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn-sm btn-rounded form-control form-control-sm btn-primary">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-md-center">
                        <div class="col-md-12">
                            <table id="payment_history" class="table table-bordered nowrap display" v-bind="summary">
                                <thead class="bg-dark text-white">
                                    <th>Id</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th >Previous Balance</th>
                                </thead>

                                <tbody>
                                    <tr>
                                        <th colspan="6" class="text-center">Search your payment history.</th>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-light">

                                </tfoot>
                            </table>
                        </div>
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
    {{-- Datetime Picker --}}
    <script src="{{asset('theme')}}/assets/libs/moment/moment.js"></script>
    <script src="{{asset('theme')}}/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    {{-- Datetime Picker --}}
    {{-- DataTable --}}
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    {{-- DataTable --}}
<script>

    $(document).ready( function () {
        // CSRF TOKEN SETUP
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

        // VALIDATION MESSAGE CLOSE
        $('.close').on('click', function(e){
            var alert = $(this).parent();
            alert.addClass('d-none');
        })

        // FORM SUBMIT AND DRAW DATATABLE
        $('form').on('submit', function(e) {
            e.preventDefault();
            drawTable();
        });

        function drawTable(){
            $('#payment_history').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "order": [[ 0, "desc" ]],
                ajax   : {
                            "url": "{{route('client.payments.history')}}",
                            "type": "POST",
                            "dataType": 'json',
                            "data": {
                                        "from_date" : $('input[name="from_date"]').val(),
                                        "to_date" : $('input[name="to_date"]').val(),
                                    },
                            "error": function(xhr){
                                    errorProcess(xhr)
                            }
                        },
                columnDefs: [
                                { "className": "text-right", "targets": [2,5] }
                            ],
                columns: [
                            { data: 'id', name: 'id' },
                            { data: 'date', name: 'date' },
                            { data: 'balance', name: 'balance' },
                            { data: 'type', name: 'type' },
                            { data: 'description', name: 'description' },
                            { data: 'actual_value', name: 'actual_value',  },
                        ],
            }).on( 'error.dt', function ( e, settings, techNote, message ) {
                // SHOW DATATABLE ERROR MESSSAGE
                console.log( message ); // for test purpose
                return true;;
            });
        }


        // SHOW RESPECTIVE VALIDATION ERROR MESSAGES
        function errorProcess(xhr){
            var form = $('form');
            if (xhr.status == 422) {
                var errors_obj = JSON.parse(xhr.responseText);
                var errors = errors_obj.errors;
                for (name in errors) {
                    $("[name="+name+"]").siblings('.alert').children('.error').html(errors[name][0]);
                    $("[name="+name+"]").siblings('.alert').removeClass('d-none');
                }
            } else {
                if (xhr['warning']) {
                    $("[name="+name+"]").siblings('.alert').children('.error').html(errors['warning']);
                    $("[name="+name+"]").siblings('.alert').removeClass('d-none');
                }
            }
        }

    });

</script>
@endpush
