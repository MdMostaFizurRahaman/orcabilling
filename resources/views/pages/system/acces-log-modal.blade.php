<!-- Modal -->
<div class="modal animated slideInDown access-log-modal" id="access-log-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" v-bind="log">
            <div class="modal-header card-header">
                <div class="d-flex no-block align-items-center">
                    <h4 class="card-title mb-0"> Activity Log</h4><br>
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
                            <th scope="col">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="row">@{{log.id_log}}</th>
                        </tr>
                        <tr>
                            <th scope="col">User Name</th>
                            <td>@{{log.user}}</td>
                        </tr>
                        <tr>
                            <th scope="col">Date</th>
                            <td>@{{log.log_dt}}</td>
                        </tr>
                        <tr>
                            <th scope="col">IP</th>
                            <td>@{{log.user_ip}}</td>
                        </tr>
                        <tr>
                            <th scope="col">URI</th>
                            <td>@{{log.link_uri}}</td>
                        </tr>
                        <tr>
                            <th scope="col">Action</th>
                            <td>@{{log.action}}</td>
                        </tr>
                        <tr>
                            <th scope="col">Post Data</th>
                            <td>
                                <table class="table table-striped mb-0">
                                    <tr v-for='(key, value) in log.post_data'>
                                        <th>@{{key}}</th>
                                        <td>@{{value}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">Status</th>
                            <td>@{{log.status}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer card-footer">
                <div class="">
                    {{-- <span><small><strong>Exec. time : </strong>@{{result.execution_time}}</small></span> --}}
                </div>
                <button type="button" v-on:click="closeModal" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
