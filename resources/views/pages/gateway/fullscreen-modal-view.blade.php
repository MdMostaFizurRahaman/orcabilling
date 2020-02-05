<div class="scoped">
        <!-- Modal -->
        <div class="modal fade right" id="fullscreen-modal-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
            <div class="modal-dialog momodel modal-fluid" role="document">
                <div class="modal-content ">
                    <form @submit.prevent="save()" class="m-t-30">
                            <div class=" modal-header">
                                <h3 class="modal-title w-100" id="exampleModalPreviewLabel"><i class="ti-marker-alt m-r-10"></i>View Gateway Details</h3>
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
                                                    <h5>System Information</h5>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="">Gateway Name</label>
                                                    <input disabled v-model="form.name"  type="text" name="name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control">
                                                    <has-error :form="form" field="name"></has-error>
                                                </div>
                                                <div class="form-group">
                                                        <label for="">Username</label>
                                                    <input disabled v-model="form.username"  type="text" name="username" :class="{ 'is-invalid': form.errors.has('username') }" class="form-control">
                                                        <has-error :form="form" field="username"></has-error>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Password</label>
                                                    <input disabled v-model="form.password"  type="password" name="password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control">
                                                    <has-error :form="form" field="password"></has-error>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">IP Address</label>
                                                    <input disabled v-model="form.ip"  type="text" name="ip" :class="{ 'is-invalid': form.errors.has('ip') }" class="form-control">
                                                    <has-error :form="form" field="ip"></has-error>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Port</label>
                                                    <input disabled v-model="form.port"  type="text" name="port" :class="{ 'is-invalid': form.errors.has('port') }" class="form-control">
                                                    <has-error :form="form" field="port"></has-error>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input disabled type="checkbox" class="custom-control-input" id="status" v-model="form.status">
                                                    <label class="custom-control-label" for="status">  Active</label>
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
                                                    <label for="">Tariff Name</label>
                                                    <select disabled  v-model="form.tariff_id" name="tariff_id" class="form-control" :class="{ 'is-invalid': form.errors.has('tariff_id') }">
                                                        <option v-for='tariff in tariffs' :key='tariff.id' :value='tariff.id'>@{{tariff.name}}</option>
                                                    </select>
                                                    <has-error :form="form" field="tariff_id"></has-error>
                                                </div>                 
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h5>Routing Information</h5>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="">Call Limit</label>
                                                    <input disabled v-model="form.call_limit"  type="number" name="call_limit" :class="{ 'is-invalid': form.errors.has('call_limit') }" class="form-control">
                                                    <has-error :form="form" field="call_limit"></has-error>
                                                    <small>0 for block this gateway.</small>
                                                </div> 
                                                <label class="m-b-10">Media Proxy</label>
                                                <div class="custom-control custom-radio">
                                                    <input disabled v-model="form.media_proxy" type="radio" id="yes" name="media_proxy" class="custom-control-input" value=1>
                                                    <label class="custom-control-label" for="yes">Yes</label>
                                                </div>
                                                <div class="custom-control custom-radio m-b-20">
                                                    <input disabled v-model="form.media_proxy" type="radio" id="no" name="media_proxy" class="custom-control-input" value=0>
                                                    <label class="custom-control-label" for="no" >No</label>
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