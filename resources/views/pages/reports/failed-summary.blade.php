@extends('layouts.app')

@section('title')
    Failed Calls Summary
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
                                        <h5>Failed Calls Summary</h5>
                                        <small>{{request()->from_date.' to '. request()->to_date}}</small>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="btn-group">
                                            <a href="{{route('failed-calls.summary.export', array_merge(request()->all(), ['mime' => 'csv']))}}" data-toggle="tooltip" title="Export to csv"  class="btn btn-rounded btn-info"> <span class="fa fa-file-code"></span> Export CSV</a>
                                            <a href="{{route('failed-calls.summary.export', array_merge(request()->all(), ['mime' => 'xlsx']))}}" data-toggle="tooltip" title="Export to excel"  class="btn btn-rounded btn-info"> <span class="far fa-file-excel"></span> Export Excel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive">
                                <table id="summary_table" style="font-size: 13px;" class="table table-bordered nowrap display" v-bind="summary">
                                    <thead class="bg-dark text-white" style="font-size: 12px;">
                                        <tr>
                                            <th scope="col">Called</th>
                                            <th scope="col">Start Time</th>
                                            <th scope="col">PDD</th>
                                            <th scope="col">IP Number</th>
                                            <th scope="col">Client</th>
                                            <th scope="col">Destination</th>
                                            <th scope="col">Prefix</th>
                                            <th scope="col">Dialing Prefix</th>
                                            <th scope="col">Route</th>
                                            <th scope="col">Discon. Reason</th>
                                            <th scope="col">Madia Prox.</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($collection['calls'] as $group)
                                        @foreach ($group as $call)
                                        <tr>
                                            <td scope="row">{{$call->called}}</td>
                                            <td>{{$call->call_start}}</td>
                                            <td>{{$call->pdd}}</td>
                                            <td>{{$call->ip_number}}</td>
                                            <td>{{$call->client_name}}</td>
                                            <td>{{$call->calling}}</td>
                                            <td>{{$call->tariff_prefix}}</td>
                                            <td>{{'Dialing Prefix'}}</td>
                                            <td>{{$call->gateway_name}}</td>
                                            <td>{{$call->release_reason}}</td>
                                            <td>{{'False'}}</td>
                                        </tr>
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <th scope="row" colspan="2">Total Failed Calls</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>{{$collection['totalCalls']}}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
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
    $('#summary_table').DataTable({
        destroy: true,
        // "order": [[ 0, "asc"]],
        columns: [
                    { data: 'called', name: 'called' },
                    { data: 'call_start', name: 'call_start' },
                    { data: 'client_name', name: 'client_name' },
                    { data: 'ip_number', name: 'ip_number' },
                    { data: 'pdd', name: 'pdd' },
                    { data: 'tariff_prefix', name: 'tariff_prefix' },
                    { data: 'calling', name: 'calling' },
                    { data: 'dialing_prefix', name: 'Dialing Prefix' },
                    { data: 'gateway_name', name: 'gateway_name' },
                    { data: 'release_reason', name: 'release_reason' },
                    { data: 'media_proxy', name: 'media_proxy' },
                ],
    });
});
</script>
@endpush('scripts')
