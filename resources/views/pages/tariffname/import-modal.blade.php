<!-- Modal -->
<div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="import-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Import Rates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="importRates()" enctype="multipart/form-data">
                    <div class="form-group">
                        <input class="form-control" name="import" type="file" id="file" ref="file" v-on:change="onChangeFileUpload()"/>
                    </div>
                    {{-- <div class="form-group">
                        <output class="form-control">
                                Column Order: tariffname_id, prefix, description, voice_rate, from_day, to_day, from_hour, to_hour, grace_period,
                                minimal_time, resolution, rate_multiplier, free_seconds, effective_date.
                        </output>
                    </div> --}}
                    <ul>
                        <li class="text-danger"><span >Only upload .xlsx file</span></li>
                        <li class="text-dark"><span >Download the instruction file</span> <a href="{{route('rate.download')}}">here.</a></li>
                    </ul>
                    <div class="form-group">
                        <button v-show="disabled" disabled type="submit" class="btn btn-success btn-block">Upload</button>
                        <button v-show="!disabled" type="submit" class="btn btn-success btn-block">Upload</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
