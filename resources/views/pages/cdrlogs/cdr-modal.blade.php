<!-- Modal -->
<div class="modal animated slideInUp cdr-modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" v-bind="cdrlog">
            <div class="modal-header card-header">
                <h4 class="modal-title" id="createModalLabel"><i class="ti-marker-alt m-r-10"></i> CDR Report</h4>
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
                            <th scope="row">File Name</th>
                        <td>@{{cdrlog.file_name}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Total Rows</th>
                            <td>@{{cdrlog.rows_count}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td>@{{cdrlog.status}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Processed Time</th>
                            <td>@{{cdrlog.processed_time}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Report</th>
                            <td>
                                <div v-for='(line, report) in cdrlog.status_report'>
                                    Line <span>@{{line}}</span>: <p class="display-inline">@{{report}}</p>
                                </div>
                                {{-- Line: @{{status_report.line}}<br>
                                Message: @{{status_report.msg}} --}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer card-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" v-on:click="reparse(cdrlog.file_name)" :data-file_name="cdrlog.file_name" class="btn btn-success"><i class="ti-reparse"></i> Reparse</button>
            </div>
        </div>
    </div>
</div>
