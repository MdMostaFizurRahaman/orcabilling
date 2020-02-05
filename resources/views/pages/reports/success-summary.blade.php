@extends('layouts.app')

@section('title')
    Success Calls Summary
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
                                        <h5>Success Calls Summary</h5><small>{{request()->from_date.' to '. request()->to_date}}</small>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="btn-group">
                                            <a href="{{route('success-calls.summary.export', array_merge(request()->all(), ['mime' => 'csv']))}}" data-toggle="tooltip" title="Export to csv"  class="btn btn-rounded btn-info"> <span class="fa fa-file-code"></span> Export CSV</a>
                                            <a href="{{route('success-calls.summary.export', array_merge(request()->all(), ['mime' => 'xlsx']))}}" data-toggle="tooltip" title="Export to excel"  class="btn btn-rounded btn-info"> <span class="far fa-file-excel"></span> Export Excel</a>
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
                                            <th scope="col">Duration</th>
                                            <th scope="col">IP Number</th>
                                            <th scope="col">Client</th>
                                            <th scope="col">Rate</th>
                                            <th scope="col">Destination</th>
                                            <th scope="col">Prefix</th>
                                            <th scope="col">Cost</th>
                                            <th scope="col">Dial Prefix</th>
                                            <th scope="col">Route</th>
                                            <th scope="col">Prefix</th>
                                            <th scope="col">Cost</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($collection['calls'] as $calls)
                                        @foreach ($calls as $call)
                                        <tr>
                                            <td scope="row">{{$call->called}}</td>
                                            <td>{{$call->call_start}}</td>
                                            <td>{{number_format($call['duration'] / 60, 2)}}</td>
                                            <td>{{$call->ip_number}}</td>
                                            <td>{{$call->client_name}}</td>
                                            <td>{{$call->call_rate}}</td>
                                            <td>{{$call->tariffdesc}}</td>
                                            <td>{{$call->tariff_prefix }}</td>
                                            <td>{{number_format($call->cost, 2)}}</td>
                                            <td>Dial Prefix</td>
                                            <td>{{$call->gateway_name}}</td>
                                            <td>{{$call->route_rate_prefix}}</td>
                                            <td>{{number_format($call->costD, 2)}}</td>
                                        </tr>
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <th scope="row">Total</th>
                                            <th>{{$collection['totalCalls']}}</th>
                                            <th>{{$collection['totalDuration']}}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>{{$collection['totalCost']}}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>{{$collection['totalCostD']}}</th>
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
                    { data: 'duration', name: 'duration' },
                    { data: 'call_rate', name: 'call_rate' },
                    { data: 'tariff_prefix', name: 'tariff_prefix' },
                    { data: 'tariffdesc', name: 'tariffdesc' },
                    { data: 'cost', name: 'cost' },
                    { data: '', name: 'Dialing Prefix' },
                    { data: 'route_rate_prefix', name: 'route_rate_prefix' },
                    { data: 'gateway_name', name: 'gateway_name' },
                    { data: 'costD', name: 'costD' },
                ],
    });
});
</script>
@endpush('scripts')
