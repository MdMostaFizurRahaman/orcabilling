@php
    // $collection = json_decode(json_encode($collection), true);
    // ksort($collection, 1);
@endphp

<table>
    <thead>
        <tr>
            <th scope="row" colspan="5"><strong>Origination Report</strong></th>
            @if (request()->prefix || request()->group_by == 'tariff_prefix')
                <th></th>
            @endif
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th scope="row" colspan="5"><small>{{request()->from_date.' to '. request()->to_date}}</small></th>
            @if (request()->prefix || request()->group_by == 'tariff_prefix')
                <th></th>
            @endif
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th scope="col"><strong>Client</strong></th>
            <th scope="col"><strong>Total Call</strong></th>
            <th scope="col"><strong>Success Calls</strong></th>
            <th scope="col"><strong>Duration</strong></th>
            @if (request()->prefix || request()->group_by == 'tariff_prefix')
                <th scope="col"><strong>Rate</strong></th>
            @endif
            <th scope="col"><strong>Total Cost</strong></th>
            <th scope="col"><strong>ASR (%)</strong></th>
            <th scope="col"><strong>ACD</strong></th>
            <th scope="col"><strong>PDD</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collection['origination']['groupedOriginationSummary'] as $groupKey => $group)
        @foreach ($group as $client_id => $summary)
        @php
            $client = App\Client::find($client_id);
        @endphp
        <tr>
            <th scope="row">{{$client->username}}</th>
            <td>{{$summary->totalCalls}}</td>
            <td>{{$summary->totalSuccessCalls}}</td>
            <td>{{round($summary->totalDuration / 60, 2)}}</td>
            @if (request()->prefix)
                <td>{{$client->tariff->rateByPrefix(request()->prefix)->voice_rate}}</td>
            @elseif(request()->group_by == 'tariff_prefix')
                <td>{{$client->tariff->rateByPrefix($groupKey)->voice_rate}}</td>
            @endif
            <td>{{round($summary->totalCost, 2)}}</td>
            <td>{{$summary->ASR . '%'}}</td>
            <td>{{$summary->ACD}}</td>
            <td>{{$summary->avgPdd}}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
    <tfoot class="bg-light">
        <tr>
            <th scope="row"><strong>Total</strong></th>
            <th>{{$collection['origination']['totalCalls']}}</th>
            <th>{{$collection['origination']['totalSuccessCalls']}}</th>
            <th>{{$collection['origination']['totalDuration']}}</th>
            @if (request()->prefix || request()->group_by == 'tariff_prefix')
                <th></th>
            @endif
            <th>{{$collection['origination']['totalCost']}}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

<table>
    <thead>
        <tr>
            <th scope="row" colspan="5"><strong>Termination Report</strong></th>
            @if (request()->prefix || request()->group_by == 'tariff_prefix')
                <th></th>
            @endif
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th scope="col"><strong>Client</strong></th>
            <th scope="col"><strong>Total Call</strong></th>
            <th scope="col"><strong>Success Calls</strong></th>
            <th scope="col"><strong>Duration</strong></th>
            @if (request()->prefix || request()->group_by == 'tariff_prefix')
                <th scope="col"><strong>Rate</strong></th>
            @endif
            <th scope="col"><strong>Total Cost</strong></th>
            <th scope="col"><strong>ASR (%)</strong></th>
            <th scope="col"><strong>ACD</strong></th>
            <th scope="col"><strong>PDD</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collection['termination']['groupedTerminationSummary'] as $groupKey => $group)
        @foreach ($group as  $gateway_id => $summary)
        @php
            $gateway = App\Gateway::find($gateway_id);
        @endphp
        <tr>
            <th scope="row">{{$gateway->name}}</th>
            <td>{{$summary->totalCalls}}</td>
            <td>{{$summary->totalSuccessCalls}}</td>
            <td>{{ round($summary->totalDuration / 60, 2)}}</td>
            @if (request()->prefix)
                <td>{{$gateway->tariff->rateByPrefix(request()->prefix)->voice_rate}}</td>
            @elseif(request()->group_by == 'tariff_prefix')
                <td>{{$gateway->tariff->rateByPrefix($groupKey)->voice_rate}}</td>
            @endif
            <td>{{round($summary->totalCost, 2)}}</td>
            <td>{{$summary->ASR . '%'}}</td>
            <td>{{$summary->ACD}}</td>
            <td>{{$summary->avgPdd}}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
    <tfoot class="bg-light">
        <tr>
            <th scope="row"><strong>Total</strong></th>
            <th>{{$collection['termination']['totalCalls']}}</th>
            <th>{{$collection['termination']['totalSuccessCalls']}}</th>
            <th>{{$collection['termination']['totalDuration']}}</th>
            @if (request()->prefix || request()->group_by == 'tariff_prefix')
                <th></th>
            @endif
            <th>{{$collection['termination']['totalCost']}}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

