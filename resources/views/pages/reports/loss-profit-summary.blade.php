@extends('layouts.app')

@section('title')
    Loss-Profit Summary
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid loaded" id="summary">
    <div class="scoped">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex no-block align-items-center m-b-0">
                            <div class="card-title">
                                <h5>Loss-Profit Summary</h5><small>{{request()->from_date.' to '. request()->to_date}}</small>
                            </div>
                            <div class="ml-auto">
                                <div class="btn-group">
                                    <a href="{{route('loss-profit.summary.export', array_merge(request()->all(), ['mime' => 'csv']))}}" data-toggle="tooltip" title="Export to csv"  class="btn btn-rounded btn-info"> <span class="fa fa-file-code"></span> Export CSV</a>
                                    <a href="{{route('loss-profit.summary.export', array_merge(request()->all(), ['mime' => 'xlsx']))}}" data-toggle="tooltip" title="Export to excel"  class="btn btn-rounded btn-info"> <span class="far fa-file-excel"></span> Export Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="summary_table" style="font-size: 13px;" class="table table-bordered nowrap display" v-bind="summary">
                            <thead class="bg-dark text-white" style="font-size: 12px;">
                                <tr>
                                    <th scope="col">Client</th>
                                    <th scope="col">Total Call</th>
                                    <th scope="col">Duration</th>
                                    @if (request()->group_by == 'tariff_prefix')
                                        <th scope="col">Client Rate</th>
                                    @endif
                                    <th scope="col">Cost</th>
                                    <th scope="col">Route Cost</th>
                                    <th scope="col">Margin</th>
                                    @if (request()->group_by == 'tariff_prefix')
                                        <th scope="col">Route Rate</th>
                                    @endif
                                    <th scope="col">Route</th>
                                    <th scope="col">ACD</th>
                                    <th scope="col">PDD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report['groupedCallsSummary'] as $groupKey => $group)
                                @foreach ($group as $clientAndGatewayId => $summary)
                                @php
                                    $idArray = explode(':', $clientAndGatewayId);
                                    $client = App\Client::find($idArray[0]);
                                    $gateway = App\Gateway::find($idArray[1]);
                                @endphp
                                <tr>
                                    <th scope="row">{{$client->username}}</th>
                                    <td>{{number_format($summary->totalCalls)}}</td>
                                    <td>{{number_format($summary->totalDuration / 60, 2)}}</td>
                                    @if(request()->group_by == 'tariff_prefix')
                                        <td>{{$client->tariff->rateByPrefix($groupKey)->voice_rate}}</td>
                                    @endif
                                    <td>{{number_format($summary->totalCost, 3)}}</td>
                                    <td>{{number_format($summary->totalCostD, 3)}}</td>
                                    <td>{{number_format($summary->totalMargin, 2)}}</td>
                                    @if(request()->group_by == 'tariff_prefix')
                                        <td>{{$gateway->tariff->rateByPrefix($groupKey)->voice_rate}}</td>
                                    @endif
                                    <td>{{$gateway->name}}</td>
                                    <td>{{$summary->ACD}}</td>
                                    <td>{{$summary->avgPdd}}</td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th scope="row"><strong>Total</strong></th>
                                    <th>{{$report['totalCalls']}}</th>
                                    <th>{{$report['totalDuration']}}</th>
                                    @if (request()->group_by == 'tariff_prefix')
                                        <th></th>
                                    @endif
                                    <th>{{$report['totalCost']}}</th>
                                    <th>{{$report['totalCostD']}}</th>
                                    <th>{{$report['totalMargin']}}</th>
                                    @if (request()->group_by == 'tariff_prefix')
                                        <th></th>
                                    @endif
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
                    { data: 'client_name', name: 'client_name' },
                    { data: 'totalCalls', name: 'totalCalls' },
                    { data: 'totalDuration', name: 'totalDuration' },
                    @if (request()->group_by == 'tariff_prefix')
                    { data: 'client_rate', name: 'client_rate' },
                    @endif
                    { data: 'totalCost', name: 'totalCost' },
                    { data: 'totalCostD', name: 'totalCostD' },
                    { data: 'totalMargin', name: 'totalMargin' },
                    @if (request()->group_by == 'tariff_prefix')
                    { data: 'route_rate', name: 'route_rate' },
                    @endif
                    { data: 'route_name', name: 'route_name' },
                    { data: 'ACD', name: 'ACD' },
                    { data: 'avgPdd', name: 'avgPdd' },
                ],
    });
});
</script>
@endpush('scripts')
