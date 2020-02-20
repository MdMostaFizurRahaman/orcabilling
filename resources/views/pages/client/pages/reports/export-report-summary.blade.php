@php
    // $collection = json_decode(json_encode($collection), true);
    // ksort($collection, 1);
@endphp

<table>
    <thead>
        <tr>
            <th scope="col"><strong>Group By</strong></th>
            <th scope="col"><strong>Total Call</strong></th>
            <th scope="col"><strong>Total Duration</strong></th>
            @if (request()->group_by == 'tariff_prefix' || request()->group_by == 'tariff_desc')
                <th scope="col"><strong>Prefix</strong> </th>
                <th scope="col"><strong>Rate</strong> </th>
                <th scope="col"><strong>Description</strong> </th>
            @endif
                <th scope="col"><strong>Cost</strong> </th>
            @if (request()->group_by == 'ip_number')
                <th scope="col"><strong>IP Address</strong></th>
            @endif
            <th scope="col"><strong>ACD</strong> </th>
            <th scope="col"><strong>PDD</strong> </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collection['groupedCallsSummary'] as $groupKey => $summary)
        <tr>
            <th scope="row">{{$groupKey}}</th>
            <td>{{number_format($summary->totalCalls)}}</td>
            <td>{{round($summary->totalDuration / 60, 2)}}</td>
            @if(request()->group_by == 'tariff_prefix' || request()->group_by == 'tariff_desc')
                <td>{{$client->tariff->rateByPrefix($groupKey)->prefix}}</td>
                <td>{{$client->tariff->rateByPrefix($groupKey)->voice_rate}}</td>
                <th scope="col">{{$client->tariff->rateByPrefix($groupKey)->tariff_desc}}</th>
            @endif
            <td>{{number_format($summary->totalCost, 3)}}</td>
            @if(request()->group_by == 'ip_number')
                <td>{{$groupKey}}</td>
            @endif
            <td>{{$summary->ACD}}</td>
            <td>{{$summary->avgPdd}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-light">
        <tr>
            <th scope="row"><strong>Total</strong></th>
            <th><strong>{{$collection['totalCalls']}}</strong></th>
            <th><strong>{{$collection['totalDuration']}}</strong></th>
            @if (request()->group_by == 'tariff_prefix' || request()->group_by == 'tariff_desc')
                <th></th>
                <th></th>
                <th></th>
            @endif
            <th><strong>{{$collection['totalCost']}}</strong></th>
            @if (request()->group_by == 'ip_number')
                <th></th>
            @endif
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

