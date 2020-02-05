<div class="scoped">
    <!-- Modal -->
    <div class="modal animated slideInRight result-modal" id="result-modl-fullscreen" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-fluid" role="document">
            <div class="modal-content" v-bind="result">
                <div class="modal-header card-header">
                    <div class="d-flex no-block align-items-center">
                        <h4 class="card-title mb-0">Simulation Result</h4><br>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Property</th>
                                <th scope="col">Client</th>
                                <th scope="col">Gateway</th>
                                <th scope="col">Property</th>
                                <th scope="col">Client</th>
                                <th scope="col">Gateway</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Cost</th>
                                <td>@{{result.c_cost + ' ' + result.c_symbol}}</td>
                                <td>@{{result.g_cost + ' ' + result.g_symbol}}</td>
                                <th>Number (Dialing - Dialed)</th>
                                <td>@{{result.dialing}}</td>
                                <td>@{{result.dialed}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Voice Rate</th>
                                <td>@{{result.c_rate.voice_rate + ' ' + result.c_symbol}}</td>
                                <td>@{{result.g_rate.voice_rate + ' ' + result.g_symbol}}</td>
                                <th>Rate Prefix</th>
                                <td>@{{result.c_rate.prefix}}</td>
                                <td>@{{result.g_rate.prefix}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Actual Duration</th>
                                <td>@{{result.duration + " Seconds"}}</td>
                                <td>@{{result.duration + " Seconds"}}</td>
                                <th>Tariff Description</th>
                                <td>@{{result.c_rate.prefix + " Seconds"}}</td>
                                <td>@{{result.g_effective_duration + " Seconds"}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Effective Duration</th>
                                <td>@{{result.c_effective_duration + " Seconds"}}</td>
                                <td>@{{result.g_effective_duration + " Seconds"}}</td>
                                <th>Time (Start - End)</th>
                                <td>@{{result.StartTime}}</td>
                                <td>@{{result.EndTime}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Grace Period</th>
                                <td>@{{result.c_rate.grace_period}}</td>
                                <td>@{{result.g_rate.grace_period}}</td>
                                <th>Minimal Time</th>
                                <td>@{{result.c_rate.minimal_time}}</td>
                                <td>@{{result.g_rate.minimal_time}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Rate Multiplier</th>
                                <td>@{{result.c_rate.rate_multiplier}}</td>
                                <td>@{{result.g_rate.rate_multiplier}}</td>
                                <th>Resolution</th>
                                <td>@{{result.c_rate.resolution}}</td>
                                <td>@{{result.g_rate.resolution}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Current Balance</th>
                                <td>@{{result.c_cur_balance}}</td>
                                <td>@{{result.g_cur_balance}}</td>

                                <th>IP Address</th>
                                <td>@{{result.c_ip}}</td>
                                <td>@{{result.g_ip}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Previous Balance</th>
                                <td>@{{result.c_pre_balance}}</td>
                                <td>@{{result.g_pre_balance}}</td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer card-footer">
                    <div class="">
                        <span><small><strong>Exec. time : </strong>@{{result.execution_time}}</small></span>
                    </div>
                    <button type="button" v-on:click="closeModal" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
