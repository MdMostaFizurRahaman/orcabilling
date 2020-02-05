@php
    // $collection = json_decode(json_encode($collection), true);
    // ksort($collection, 1);
@endphp

<table>
    <thead>
        <tr>
            <th scope="row" colspan="5"><strong>Origination Report</strong></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th scope="row" colspan="5"><small>{{request()->from_date.' to '. request()->to_date}}</small></th>
            <th></th>
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
            <th scope="col"><strong>Rate</strong></th>
            <th scope="col"><strong>Total Cost</strong></th>
            <th scope="col"><strong>ASR (%)</strong></th>
            <th scope="col"><strong>ACD</strong></th>
            <th scope="col"><strong>PDD</strong></th>
        </tr>
    </thead>

    <tbody>
        @foreach ($collection['client']['clients'] as $client)
        {{-- @foreach ($group as $call) --}}
        <tr>
            <td scope="row">{{$client->full_name}}</td>
            <td>{{$client->totalCalls}}</td>
            <td>{{$client->calls_count}}</td>
            <td>{{round($client->totalDuration / 60, 2)}}</td>
            <td></td>
            <td>{{round($client->totalCost, 2)}}</td>
            <td>{{$client->ASR}}</td>
            <td>{{$client->ACD}}</td>
            <td>{{$client->avgPdd}}</td>
        </tr>
        {{-- @endforeach --}}
        @endforeach
    </tbody>
    <tfoot class="bg-light">
        <tr>
            <th scope="row"><strong>Total</strong></th>
            <th>{{$collection['client']['totalCalls']}}</th>
            <th>{{$collection['client']['totalSuccessCalls']}}</th>
            <th>{{$collection['client']['totalDuration']}}</th>
            <th></th>
            <th>{{$collection['client']['totalCost']}}</th>
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
            <th scope="col"><strong>Rate</strong></th>
            <th scope="col"><strong>Total Cost</strong></th>
            <th scope="col"><strong>ASR (%)</strong></th>
            <th scope="col"><strong>ACD</strong></th>
            <th scope="col"><strong>PDD</strong></th>
        </tr>
    </thead>

    <tbody>
        @foreach ($collection['gateway']['gateways'] as $gateway)
        {{-- @foreach ($group as $call) --}}
        <tr>
            <td scope="row">{{$gateway->name}}</td>
            <td>{{$gateway->totalCalls}}</td>
            <td>{{$gateway->calls_count }}</td>
            <td>{{round($gateway->totalDuration / 60, 2)}}</td>
            <td></td>
            <td>{{round($gateway->totalCost, 2)}}</td>
            <td>{{$gateway->ASR}}</td>
            <td>{{$gateway->ACD}}</td>
            <td>{{$gateway->avgPdd}}</td>
        </tr>
        {{-- @endforeach --}}
        @endforeach
    </tbody>
    <tfoot class="bg-light">
        <tr>
            <th scope="row"><strong>Total</strong></th>
            <th>{{$collection['gateway']['totalCalls']}}</th>
            <th>{{$collection['gateway']['totalSuccessCalls']}}</th>
            <th>{{$collection['gateway']['totalDuration']}}</th>
            <th></th>
            <th>{{$collection['gateway']['totalCost']}}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

