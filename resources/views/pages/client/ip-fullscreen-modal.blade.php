<div class="scoped">
    <!-- Modal -->
    <div class="modal fade right" id="ip-fullscreen-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class=" modal-header">
                    <h3 v-show="!editMode"  class="modal-title w-100" id="exampleModalPreviewLabel"><i class="ti-marker-alt m-r-10"></i>Add New IP</h3>
                    <button  type="button" class="close " data-dismiss="modal" aria-label="Close">
                        <span style="font-size: 1.3em;" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <form @submit.prevent="editMode ? updateIP():saveIP()">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h5>Enter Details</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="">IP Address</label>
                                            <input v-model="form3.ip"  type="text"  name="ip" :class="{ 'is-invalid': form3.errors.has('ip') }" class="form-control" placeholder="0.0.0.0">
                                            <has-error :form="form3" field="ip"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger btn-md btn-rounded" data-dismiss="modal">Close</button>
                                            <button  v-show="!disabled" type="submit" class="btn btn-primary btn-md btn-rounded">Save</button>
                                            <button v-show="disabled" disabled type="submit" class="btn btn-primary btn-md btn-rounded">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h5>All IP List</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>Id</th>
                                            <th>IP</th>
                                            <th>Delete</th>
                                        </thead>
                                        <tbody>
                                            <tr v-for="ip in ips" :key="ip.id">
                                                <td>@{{ip.id}}</td>
                                                <td>@{{ip.ip}}</td>
                                                <td><button v-on:click="deleteIP(ip.id)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
</div>
