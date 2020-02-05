@extends('layouts.app')

@section('title')
    Call Summary
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
                                        <h6>Orig-Term Calls Summary</h6>
                                        <small>{{request()->from_date.' to '. request()->to_date}}</small>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="btn-group">
                                            <a href="{{route('client-calls.summary.export', array_merge(request()->all(), ['mime' => 'csv']))}}" data-toggle="tooltip" title="Export to csv"  class="btn btn-rounded btn-info"> <span class="fa fa-file-code"></span> CSV</a>
                                            <a href="{{route('client-calls.summary.export', array_merge(request()->all(), ['mime' => 'xlsx']))}}" data-toggle="tooltip" title="Export to excel"  class="btn btn-rounded btn-info"> <span class="far fa-file-excel"></span> Excel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive" style="font-size: 13px;">
                                <table id="origination_table" class="table table-bordered nowrap display">
                                    <thead class="bg-dark text-white" style="font-size: 12px;">
                                        <tr>
                                            <th scope="col">Client</th>
                                            <th scope="col">Total Call</th>
                                            <th scope="col">Success Calls</th>
                                            <th scope="col">Duration</th>
                                            @if (request()->prefix)
                                                <th scope="col">Rate</th>
                                            @endif
                                            <th scope="col">Total Cost</th>
                                            <th scope="col">ASR (%)</th>
                                            <th scope="col">ACD</th>
                                            <th scope="col">PDD</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($collection['client']['clients'] as $client)
                                        <tr>
                                            <th scope="row">{{$client->full_name}}</th>
                                            <td>{{number_format($client->totalCalls)}}</td>
                                            <td>{{number_format($client->calls_count)}}</td>
                                            <td>{{number_format($client->totalDuration / 60, 2)}}</td>
                                            @if (request()->prefix)
                                                <td>{{$client->tariff->rateByPrefix(request()->prefix)->voice_rate}}</td>
                                            @endif
                                            <td>{{number_format($client->totalCost, 2)}}</td>
                                            <td>{{$client->ASR . '%'}}</td>
                                            <td>{{$client->ACD}}</td>
                                            <td>{{$client->avgPdd}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <th scope="row"><strong>Total</strong></th>
                                            <th>{{$collection['client']['totalCalls']}}</th>
                                            <th>{{$collection['client']['totalSuccessCalls']}}</th>
                                            <th>{{$collection['client']['totalDuration']}}</th>
                                            @if (request()->prefix)
                                                <th></th>
                                            @endif
                                            <th>{{$collection['client']['totalCost']}}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr>
                                <div>
                                    <h6>Termination Report</h6>
                                    <table id="termination_table" class="table table-bordered nowrap display">
                                        <thead class="bg-dark text-white" style="font-size: 12px;">
                                            <tr>
                                                <th scope="col">Gateway</th>
                                                <th scope="col">Total Call</th>
                                                <th scope="col">Success Calls</th>
                                                <th scope="col">Duration</th>
                                                @if (request()->prefix)
                                                    <th scope="col">Rate</th>
                                                @endif
                                                <th scope="col">Total Cost</th>
                                                <th scope="col">ASR (%)</th>
                                                <th scope="col">ACD</th>
                                                <th scope="col">PDD</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($collection['gateway']['gateways'] as $gateway)
                                            <tr>
                                                <th scope="row">{{$gateway->name}}</th>
                                                <td>{{number_format($gateway->totalCalls)}}</td>
                                                <td>{{number_format($gateway->calls_count)}}</td>
                                                <td>{{ number_format($gateway->totalDuration / 60, 2)}}</td>
                                                @if (request()->prefix)
                                                    <td>{{$gateway->tariff->rateByPrefix(request()->prefix)->voice_rate}}</td>
                                                @endif
                                                <td>{{number_format($gateway->totalCost, 2)}}</td>
                                                <td>{{$gateway->ASR . '%'}}</td>
                                                <td>{{$gateway->ACD}}</td>
                                                <td>{{$gateway->avgPdd}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <th scope="row"><strong>Total</strong></th>
                                                <th>{{$collection['gateway']['totalCalls']}}</th>
                                                <th>{{$collection['gateway']['totalSuccessCalls']}}</th>
                                                <th>{{$collection['gateway']['totalDuration']}}</th>
                                                @if (request()->prefix)
                                                    <th></th>
                                                @endif
                                                <th>{{$collection['gateway']['totalCost']}}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
    $('#origination_table, #termination_table').DataTable({
        destroy: true,
        // "order": [[ 0, "asc"]],
        columns: [
                    { data: 'name', name: 'name' },
                    { data: 'totalCalls', name: 'totalCalls' },
                    { data: 'calls_count', name: 'calls_count' },
                    { data: 'totalDuration', name: 'totalDuration' },
                    @if (request()->prefix)
                    { data: 'Rate', name: 'Rate' },
                    @endif
                    { data: 'totalCost', name: 'totalCost' },
                    { data: 'ASR', name: 'ASR' },
                    { data: 'ACD', name: 'ACD' },
                    { data: 'avgPdd', name: 'avgPdd' },
                ],
    });
});
</script>
@endpush('scripts')
