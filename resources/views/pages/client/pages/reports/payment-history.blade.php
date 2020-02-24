@extends('layouts.app')

@section('title')
    Payments History
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid loaded" id="summary">
    <div class="scoped">
        <!-- Modal -->
        {{-- <div class="modal animated slideInRight result-modal" id="summary-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-fluid" role="document"> --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex no-block align-items-center m-b-0">
                                    <div class="card-title">
                                        <h5>Payments History</h5><small>{{request()->from_date.' to '. request()->to_date}}</small>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="btn-group">
                                            <a href="{{route('client.payment-history.export', array_merge(request()->all(), ['mime' => 'csv']))}}" data-toggle="tooltip" title="Export to csv"  class="btn btn-rounded btn-info"> <span class="fa fa-file-code"></span> Export CSV</a>
                                            <a href="{{route('client.payment-history.export', array_merge(request()->all(), ['mime' => 'xlsx']))}}" data-toggle="tooltip" title="Export to excel"  class="btn btn-rounded btn-info"> <span class="far fa-file-excel"></span> Export Excel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <table id="payment_history" style="font-size: 13px;" class="table table-bordered nowrap display" v-bind="summary">
                                    <thead class="bg-dark text-white" style="font-size: 12px;">
                                        <th>Id</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Previous Balance</th>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                    <tfoot class="bg-light">

                                    </tfoot>
                                </table>
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
            {{-- </div>
        </div> --}}
    </div>
</div>


@endsection

@push('scripts')

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready( function () {
    var form;
    $('form').on('submit', function(e) {
        table.draw();
        e.preventDefault();
        form = this;

    });

    var table = $('#payment_history').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        "order": [[ 0, "desc" ]],
        ajax   : {
                    "url": "{{route('client.payments.dataTable')}}",
                    "type": "POST",
                    "data": form.serialize(),
                    // "data": {
                    //             "user_id": 451
                    //         },
                },

        columns: [
                    { data: 'id', name: 'id' },
                    { data: 'date', name: 'date' },
                    { data: 'balance', name: 'balance' },
                    { data: 'type', name: 'type' },
                    { data: 'description', name: 'description' },
                    { data: 'actual_value', name: 'actual_value' },
                ],
    });
});
</script>
@endpush('scripts')
