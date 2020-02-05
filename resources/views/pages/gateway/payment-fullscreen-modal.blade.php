<div class="scoped">
        <!-- Modal -->
        <div class="modal fade right" id="payment-fullscreen-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
            <div class="modal-dialog momodel modal-fluid" role="document">
                <div class="modal-content ">
                    <div class=" modal-header">
                        <h3  class="modal-title w-100" id="exampleModalPreviewLabel"><i class="ti-marker-alt m-r-10"></i>Add New Payment</h3>
                        <button  type="button" class="close " data-dismiss="modal" aria-label="Close">
                            <span style="font-size: 1.3em;" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- General Information --}}
                            <div class="col-lg-6 col-md-12">
                                <form @submit.prevent="savePayment()">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h5>Add Payment</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="">Payment Type</label>
                                                <select  v-model="form2.type" name="type" class="form-control" :class="{ 'is-invalid': form2.errors.has('type') }">
                                                    <option v-for='type in paymentTypes' :key='type.name' :value='type.name'>@{{type.name | capitalize}}</option>
                                                </select>
                                                <has-error :form="form2" field="type"></has-error>
                                            </div>
                                            <div class="form-group">
                                                    <label for="">Description</label>
                                                    <textarea v-model="form2.description"  name="description" :class="{ 'is-invalid': form2.errors.has('description') }" class="form-control">
                                                    </textarea>
                                                    <has-error :form="form2" field="description"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Balance</label>
                                                <input v-model="form2.balance"  type="number" step="any" name="balance" :class="{ 'is-invalid': form2.errors.has('balance') }" class="form-control">
                                                <has-error :form="form2" field="balance"></has-error>
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
                            {{-- Billing Information --}}
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h5>Payment History (Last 10 Entry)</h5>
                                        </div>
                                    </div>
                                    <div class="card-body"> 
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>Id</th>    
                                                <th>Date</th>    
                                                <th>Amount</th>    
                                                <th>Type</th>    
                                                <th>Description</th>    
                                                <th>Actual Balance</th>    
                                            </thead>    
                                            <tbody>
                                                <tr v-for="payment in payments" :key="payment.id">
                                                    <td>@{{payment.id}}</td>
                                                    <td>@{{payment.date}}</td>
                                                    <td>@{{payment.balance}}</td>
                                                    <td>@{{payment.type |capitalize}}</td>
                                                    <td>@{{payment.description}}</td>
                                                    <td>@{{payment.actual_value}}</td>
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