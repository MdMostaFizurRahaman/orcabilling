@extends('layouts.app')

@section('title')
    Create User
@endsection

@section('content')
  
@endsection <!-- Create Modal -->
<div class="modal fade" id="createmodel" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form @submit.prevent="saveUser()">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel"><i class="ti-marker-alt m-r-10"></i> Create New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input v-model="form.name" type="text" name="name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" placeholder="Name">
                            <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.username" type="text" name="username" :class="{ 'is-invalid': form.errors.has('username') }" class="form-control" placeholder="Username">
                            <has-error :form="form" field="username"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.email" type="email" name="email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" placeholder="Email">
                            <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.password" type="password" name="password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" placeholder="Password">
                            <has-error :form="form" field="password"></has-error>
                    </div>  
                    <div class="form-group">
                        <input v-model="form.phone" type="phone" name="text" :class="{ 'is-invalid': form.errors.has('phone') }" class="form-control" placeholder="Phone">
                        <has-error :form="form" field="phone"></has-error>
                    </div> 
                    <div class="form-group">
                        <textarea v-model="form.address"  name="text" :class="{ 'is-invalid': form.errors.has('address') }" class="form-control" placeholder="address"></textarea>
                        <has-error :form="form" field="address"></has-error>
                    </div> 
                    <div class="form-group">
                        <input v-model="form.city" type="city" name="text" :class="{ 'is-invalid': form.errors.has('city') }" class="form-control" placeholder="City">
                        <has-error :form="form" field="city"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.zipcode" type="zipcode" name="text" :class="{ 'is-invalid': form.errors.has('zipcode') }" class="form-control" placeholder="Zipcode">
                        <has-error :form="form" field="zipcode"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.country" type="country" name="text" :class="{ 'is-invalid': form.errors.has('country') }" class="form-control" placeholder="Country">
                        <has-error :form="form" field="country"></has-error>
                    </div>                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="ti-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            This is a test modal
        </div>
    </div>
</div>