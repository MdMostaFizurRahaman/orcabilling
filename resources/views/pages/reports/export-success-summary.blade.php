@php
    $groupArray = json_decode(json_encode($collection['calls']), true);
    ksort($groupArray, 1);
@endphp
<table>
    <thead>
        <tr>
            <th colspan="3"><strong>General</strong></th>
            <th colspan="6"><strong>Originate</strong></th>
            <th colspan="5"><strong>Termination</strong></th>
        </tr>
        <tr>
            <th scope="col"><strong>Start Time</strong></th>
            <th scope="col"><strong>Called</strong></th>
            <th scope="col"><strong>Duration</strong></th>
            <th scope="col"><strong>IP Number</strong></th>
            <th scope="col"><strong>Client</strong></th>
            <th scope="col"><strong>Rate</strong></th>
            <th scope="col"><strong>Destination</strong></th>
            <th scope="col"><strong>Prefix</strong></th>
            <th scope="col"><strong>Cost</strong></th>
            <th scope="col"><strong>Dial Prefix</strong></th>
            <th scope="col"><strong>Gateway IP</strong></th>
            <th scope="col"><strong>Prefix</strong></th>
            <th scope="col"><strong>Cost</strong></th>
        </tr>
    </thead>

    <tbody>
        @foreach ($groupArray as $calls)
        @foreach ($calls as $call)
        <tr>
            <td scope="row">{{$call['call_start']}}</td>
            <td><i>{{(string)$call['called']}}</i></td>
            <td>{{round($call['duration'] / 60, 2)}}</td>
            <td>{{$call['ip_number']}}</td>
            <td>{{$call['client_name']}}</td>
            <td>{{$call['call_rate']}}</td>
            <td>{{$call['tariffdesc']}}</td>
            <td>{{$call['tariff_prefix'] }}</td>
            <td>{{number_format($call['cost'], 2)}}</td>
            <td>Dial Prefix</td>
            <td>{{$call['gateway_name']}}</td>
            <td>{{$call['route_rate_prefix']}}</td>
            <td>{{number_format($call['costD'], 2)}}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
    <tfoot >
        <tr>
            <th scope="row"><strong>Total</strong></th>
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
