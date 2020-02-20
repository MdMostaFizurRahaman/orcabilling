@php
    // $collection = json_decode(json_encode($collection), true);
    // ksort($collection, 1);
@endphp

<table>
    <thead>
        <tr>
            <th scope="col"><strong>Client</strong></th>
            <th scope="col"><strong>Total Call</strong></th>
            <th scope="col"><strong>Duration</strong></th>
            @if (request()->group_by == 'tariff_prefix')
                <th scope="col"><strong>Client Rate</strong></th>
            @endif
            <th scope="col"><strong>Cost</strong></th>
            <th scope="col"><strong>Route Cost</strong></th>
            <th scope="col"><strong>Margin</strong></th>
            @if (request()->group_by == 'tariff_prefix')
                <th scope="col"><strong>Route Rate</strong></th>
            @endif
            <th scope="col"><strong>Route</strong></th>
            <th scope="col"><strong>ACD</strong></th>
            <th scope="col"><strong>PDD</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collection['groupedCallsSummary'] as $groupKey => $group)
        @foreach ($group as $clientAndGatewayId => $summary)
        @php
            $idArray = explode(':', $clientAndGatewayId);
            $client = App\Client::find($idArray[0]);
            $gateway = App\Gateway::find($idArray[1]);
        @endphp
        <tr>
            <th scope="row"><strong>{{$client->username}}</strong></th>
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
            <th>{{$collection['totalCalls']}}</th>
            <th>{{$collection['totalDuration']}}</th>
            @if (request()->group_by == 'tariff_prefix')
                <th></th>
            @endif
            <th>{{$collection['totalCost']}}</th>
            <th>{{$collection['totalCostD']}}</th>
            <th>{{$collection['totalMargin']}}</th>
            @if (request()->group_by == 'tariff_prefix')
                <th></th>
            @endif
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

