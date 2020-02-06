@php
    $groupArray = json_decode(json_encode($collection['calls']), true);
    ksort($groupArray, 1);
@endphp

<table>
    <thead>
        <tr>
            <th colspan="4"><strong>General</strong></th>
            <th colspan="4"><strong>Originate</strong></th>
            <th colspan="4"><strong>Termination</strong></th>
        </tr>
        <tr>
            <th scope="col"><strong>Called</strong></th>
            <th scope="col"><strong>Calling</strong></th>
            <th scope="col"><strong>Start Time</strong></th>
            <th scope="col"><strong>PDD</strong></th>
            <th scope="col"><strong>IP Number</strong></th>
            <th scope="col"><strong>Client</strong></th>
            <th scope="col"><strong>Destination</strong></th>
            <th scope="col"><strong>Prefix</strong></th>
            <th scope="col"><strong>Dialing Prefix</strong></th>
            <th scope="col"><strong>Route</strong></th>
            <th scope="col"><strong>Discon. Reason</strong></th>
            <th scope="col"><strong>Madia Prox.</strong></th>
        </tr>
    </thead>

    <tbody>
        @foreach ($groupArray as $group)
        @foreach ($group as $call)
        <tr>
            <td scope="row">{{$call['called']}}</td>
            <td>{{$call['calling']}}</td>
            <td>{{$call['call_start']}}</td>
            <td>{{$call['pdd']}}</td>
            <td>{{$call['ip_number']}}</td>
            <td>{{$call['client_name']}}</td>
            <td>{{$call['calling']}}</td>
            <td>{{$call['tariff_prefix']}}</td>
            <td>{{'Dialing Prefix'}}</td>
            <td>{{$call['gateway_name']}}</td>
            <td>{{$call['release_reason']}}</td>
            <td>{{'False'}}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th scope="row" colspan="2"><strong>Total Failed Calls</strong></th>
            <th></th>
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
