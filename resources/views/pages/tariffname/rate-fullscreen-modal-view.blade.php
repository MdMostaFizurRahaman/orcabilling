<div class="scoped">
        <!-- Modal -->
        <div class="modal animated slideInRight right" id="rate-fullscreen-modal-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
            <div class="modal-dialog modal-fluid" role="document">
                <div class="modal-content ">
                    <form @submit.prevent="save()" class="m-t-30">
                        <div class=" modal-header">
                            <h3 class="modal-title w-100" id="exampleModalPreviewLabel"><i class="ti-marker-alt m-r-10"></i>View Rate Details</h3>
                            <button  type="button" class="close " data-dismiss="modal" aria-label="Close">
                                <span style="font-size: 1.3em;" aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{-- General Information --}}
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h5>General Information</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="">Prefix</label>
                                                <input disabled v-model="form.prefix"  type="text" name="prefix" :class="{ 'is-invalid': form.errors.has('prefix') }" class="form-control">
                                                <has-error :form="form" field="prefix"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <input disabled v-model="form.description"  type="text" name="description" :class="{ 'is-invalid': form.errors.has('description') }" class="form-control">
                                                <has-error :form="form" field="description"></has-error>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- TimeShift Information --}}
                                    <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h5>Timeshift Information</h5>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="">From Day</label>
                                                    <select disabled  v-model="form.from_day" name="from_day" class="form-control" :class="{ 'is-invalid': form.errors.has('from_day') }">
                                                        <option value=6 >Saturday</option>
                                                        <option value=0>Sunday</option>
                                                        <option value=1>Monday</option>
                                                        <option value=2>Tuesday</option>
                                                        <option value=3>Wednesday</option>
                                                        <option value=4>Thusday</option>
                                                        <option value=5>Friday</option>
                                                    </select>
                                                    <has-error :form="form" field="from_day"></has-error>
                                                </div>
                                                <div class="form-group">
                                                    <label class="m-r-5" for="">From Hour</label><small>Ex. 700 (07:00 AM)</small>
                                                    <input disabled v-model="form.from_hour"  type="text" name="from_hour" :class="{ 'is-invalid': form.errors.has('from_hour') }" class="form-control">
                                                    <has-error :form="form" field="from_hour"></has-error>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">To Day</label>
                                                    <select disabled  v-model="form.to_day" name="to_day" class="form-control" :class="{ 'is-invalid': form.errors.has('to_day') }">
                                                        <option value=6>Saturday</option>
                                                        <option value=0>Sunday</option>
                                                        <option value=1>Monday</option>
                                                        <option value=2>Tuesday</option>
                                                        <option value=3>Wednesday</option>
                                                        <option value=4>Thusday</option>
                                                        <option value=5>Friday</option>
                                                    </select>
                                                    <has-error :form="form" field="to_day"></has-error>
                                                </div>
                                                <div class="form-group">
                                                    <label class="m-r-5" for="">To Hour</label><small>Ex. 2100 (09:00 PM)</small>
                                                    <input disabled v-model="form.to_hour"  type="text" name="to_hour" :class="{ 'is-invalid': form.errors.has('to_hour') }" class="form-control">
                                                    <has-error :form="form" field="to_hour"></has-error>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                {{-- Billing Information --}}
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h5>Billing Information</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="">Voice Rate</label>
                                                <input disabled v-model="form.voice_rate"  type="text" name="voice_rate" :class="{ 'is-invalid': form.errors.has('voice_rate') }" class="form-control">
                                                <has-error :form="form" field="voice_rate"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Grace Period</label>
                                                <input disabled v-model="form.grace_period"  type="number" step="any" name="grace_period" :class="{ 'is-invalid': form.errors.has('grace_period') }" class="form-control">
                                                <has-error :form="form" field="grace_period"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Minimum</label>
                                                <input disabled v-model="form.minimal_time"  type="number" step="any" name="minimal_time" :class="{ 'is-invalid': form.errors.has('minimal_time') }" class="form-control">
                                                <has-error :form="form" field="minimal_time"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Resolution</label>
                                                <input disabled v-model="form.resolution"  type="number" step="any" name="resolution" :class="{ 'is-invalid': form.errors.has('resolution') }" class="form-control">
                                                <has-error :form="form" field="resolution"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Rate Multiplier</label>
                                                <input disabled v-model="form.rate_multiplier"  type="text" name="rate_multiplier" :class="{ 'is-invalid': form.errors.has('rate_multiplier') }" class="form-control">
                                                <has-error :form="form" field="rate_multiplier"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Minute Flexibility</label>
                                                <input disabled   type="text" name="minute_flexibility"  class="form-control" value="1">
                                                {{-- <has-error :form="form" field="grace_period"></has-error> --}}
                                            </div>
                                            <div class="form-group">
                                                <label for="">Effective From</label>
                                                <input disabled v-model="form.effective_date"  type="text" name="effective_date" :class="{ 'is-invalid': form.errors.has('effective_date') }" class="form-control">
                                                <has-error :form="form" field="effective_date"></has-error>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input disabled type="checkbox" class="custom-control-input" id="status" v-model="form.status">
                                                <label class="custom-control-label" for="status">  Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
</div>
