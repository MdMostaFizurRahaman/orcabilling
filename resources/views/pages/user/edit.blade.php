@extends('layouts.app')


@section('title')
    Edit User
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush


@section('content')
    <div class="container-fluid" id="edit_user">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-body">
                    <h4 class="card-title"><i class="ti-marker-alt m-r-10"></i> Edit User</h4>
                        <form @submit.prevent="updateUser()" class="m-t-30">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="">Name</label>
                                    <input v-model="form.name"  type="text" name="name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" placeholder="Name">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                                <div class="form-group col-lg-6">
                                        <label for="">Username</label>
                                    <input v-model="form.username"  type="text" name="username" :class="{ 'is-invalid': form.errors.has('username') }" class="form-control" placeholder="Username">
                                        <has-error :form="form" field="username"></has-error>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                        <label for="">Email</label>
                                    <input v-model="form.email"   type="email" name="email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" placeholder="Email">
                                        <has-error :form="form" field="email"></has-error>
                                </div>
                                <div class="form-group col-lg-6">
                                        <label for="">Phone</label>
                                    <input v-model="form.phone"   type="phone" name="text" :class="{ 'is-invalid': form.errors.has('phone') }" class="form-control" placeholder="Phone">
                                    <has-error :form="form" field="phone"></has-error>
                                </div>
                            </div>
                                <div class="form-group">
                                        <label for="">Address</label>
                                    <textarea v-model="form.address" name="text" :class="{ 'is-invalid': form.errors.has('address') }" class="form-control" placeholder="Address">

                                    </textarea>
                                    <has-error :form="form" field="address"></has-error>
                                </div>
                            <div class="row">
                                <div class="form-group col-lg-4">
                                        <label for="">City</label>
                                    <input v-model="form.city"   type="city" name="text" :class="{ 'is-invalid': form.errors.has('city') }" class="form-control" placeholder="City">
                                    <has-error :form="form" field="city"></has-error>
                                </div>
                                <div class="form-group col-lg-4">
                                        <label for="">Zipcode</label>
                                    <input v-model="form.zipcode"  type="zipcode" name="text" :class="{ 'is-invalid': form.errors.has('zipcode') }" class="form-control" placeholder="Zipcode">
                                    <has-error :form="form" field="zipcode"></has-error>
                                </div>
                                <div class="form-group col-lg-4">
                                        <label for="">Country</label>
                                    <input v-model="form.country"  type="country" name="text" :class="{ 'is-invalid': form.errors.has('country') }" class="form-control" placeholder="Country">
                                    <has-error :form="form" field="country"></has-error>
                                </div>
                            </div>
                                <div class="m-t-30">
                                    <a href="{{url('/users')}}"  class="btn btn-danger" data-dismiss="modal"> <i class=" ti-back-left"></i> Back</a>
                                    <button type="submit" class="btn btn-success"><i class="ti-save"></i> Update</button>
                                </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>

        const app = new Vue({
            el: '#edit_user',
            data:{
                serial_no: 1,
                form: new Form({
                    id: '{{$user->id}}',
                    name: '{{$user->name}}',
                    username: '{{$user->username}}',
                    email: '{{$user->email}}',
                    password: '{{$user->password}}',
                    phone: '{{$user->phone}}',
                    address: '{{$user->address}}',
                    city: '{{$user->city}}',
                    zipcode: '{{$user->zipcode}}',
                    country: '{{$user->country}}',
                })
            },
            methods:{
                updateUser(){
                    this.form.put(`${this.form.id}`)
                        .then(response=>{

                            console.log(response.data);

                            Swal.fire(
                                'Success!',
                                'User updated successfully',
                                'success'
                            )

                        })
                        .catch(e=>{

                            console.log(e);

                        })
                }
            },
        });
    </script>
@endpush

