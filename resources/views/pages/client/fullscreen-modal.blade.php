<div class="scoped">
        <!-- Modal -->
        <div class="modal fade right" id="fullscreen-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
            <div class="modal-dialog momodel modal-fluid" role="document">
                <div class="modal-content ">
                    <form @submit.prevent="editMode ? update():save()" class="m-t-30">
                        <div class=" modal-header">
                            <h3 v-show="!editMode" class="modal-title w-100" id="exampleModalPreviewLabel"><i class="ti-marker-alt m-r-10"></i>Add New Client</h3>
                            <h3  v-show="editMode" class="modal-title w-100" id="exampleModalPreviewLabel"><i class="ti-marker-alt m-r-10"></i>Edit Client</h3>
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
                                                <label for="">Username</label>
                                                <input v-model="form.username"  type="text" name="username" :class="{ 'is-invalid': form.errors.has('username') }" class="form-control">
                                                <has-error :form="form" field="username"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Password</label>
                                                <input v-model="form.password"  type="password" name="password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control">
                                                <has-error :form="form" field="password"></has-error>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="status" v-model="form.status">
                                                <label class="custom-control-label" for="status">  Active</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h5>Billing Information</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="">Tariff Name</label>
                                                <select  v-model="form.tariff_id" name="tariff_id" class="form-control" :class="{ 'is-invalid': form.errors.has('tariff_id') }">
                                                    <option v-for='tariff in tariffs' :key='tariff.id' :value='tariff.id'>@{{tariff.name}}</option>
                                                </select>
                                                <has-error :form="form" field="tariff_id"></has-error>
                                            </div>   
                                            <div class="form-group">
                                                <label for="">Credit Limit</label>
                                                <input v-model="form.credit"  type="number" name="credit" :class="{ 'is-invalid': form.errors.has('credit') }" class="form-control">
                                                <has-error :form="form" field="credit"></has-error>
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
                                                <label for="">Route Type</label>
                                                <select  v-model="form.route_type" name="route_type" class="form-control" :class="{ 'is-invalid': form.errors.has('route_type') }">
                                                    <option value=0>General</option>
                                                    <option value=1>Partition</option>
                                                </select>
                                                <has-error :form="form" field="route_type"></has-error>
                                            </div>  
                                            <div class="form-group">
                                                <label for="">Capacity</label>
                                                <input v-model="form.capacity"  type="number" name="capacity" :class="{ 'is-invalid': form.errors.has('capacity') }" class="form-control">
                                                <has-error :form="form" field="capacity"></has-error>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                {{-- Personal Information --}}
                                <div class="col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h5>Personal Information</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="">Full Name</label>
                                                <input v-model="form.full_name"  type="text" name="full_name" :class="{ 'is-invalid': form.errors.has('full_name') }" class="form-control">
                                                <has-error :form="form" field="full_name"></has-error>   
                                            </div>  
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input v-model="form.email"  type="email" name="email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control">
                                                <has-error :form="form" field="email"></has-error>   
                                            </div> 
                                            <div class="form-group">
                                                <label for="">Telephone</label>
                                                <input v-model="form.telephone"  type="text" name="telephone" :class="{ 'is-invalid': form.errors.has('telephone') }" class="form-control">
                                                <has-error :form="form" field="telephone"></has-error>   
                                            </div> 
                                            <div class="form-group">
                                                <label for="">Mobile</label>
                                                <input v-model="form.mobile"  type="text" name="mobile" :class="{ 'is-invalid': form.errors.has('mobile') }" class="form-control">
                                                <has-error :form="form" field="mobile"></has-error>   
                                            </div> 
                                            <div class="form-group">
                                                <label for="">City</label>
                                                <input v-model="form.city"  type="text" name="city" :class="{ 'is-invalid': form.errors.has('city') }" class="form-control">
                                                <has-error :form="form" field="city"></has-error>   
                                            </div> 
                                            <div class="form-group">
                                                <label for="">Zip Code</label>
                                                <input v-model="form.zip"  type="text" name="zip" :class="{ 'is-invalid': form.errors.has('zip') }" class="form-control">
                                                <has-error :form="form" field="zip"></has-error>   
                                            </div> 
                                            <div class="form-group">
                                                <label for="">Country</label>
                                                <select  v-model="form.country" name="country" class="form-control" :class="{ 'is-invalid': form.errors.has('country') }">
                                                    <option v-for='country in countries' :key='country.id' :value='country.name'>@{{country.name}}</option>
                                                </select>
                                                <has-error :form="form" field="country"></has-error>   
                                            </div> 
                                            <div class="form-group">
                                                <label for="">Address</label>
                                                <textarea v-model="form.address"  type="text" name="address" :class="{ 'is-invalid': form.errors.has('address') }" class="form-control"></textarea>
                                                <has-error :form="form" field="address"></has-error>   
                                            </div>                
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-md btn-rounded" data-dismiss="modal">Close</button>
                            <button  v-show="!disabled" type="submit" class="btn btn-primary btn-md btn-rounded">Save</button>
                            <button v-show="disabled" disabled type="submit" class="btn btn-primary btn-md btn-rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
</div>