@extends('layouts.app')


@section('title')
    Users
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush
@section('content')
<div class="container-fluid" id='user'>
    @if(session()->has("success"))
    <div class="alert alert-bordered alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        <strong><i class="fa fa-check-circle"></i> Success!</strong> {{session()->get('success')}}
    </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                     <div class="d-flex no-block align-items-center m-b-10">
                        <h4 class="card-title">All Users</h4>
                        <div class="ml-auto">
                            <div class="btn-group">
                                <button type="button" v-on:click='openModal' class="btn btn-dark">Create New User</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered dataTable" role="grid" aria-describedby="zero_config_info" id='data_table'>
                                <thead>
                                    <th width='5%'>Id</th>
                                    <th width='15%'>Name</th>
                                    <th width="25%">Username</th>
                                    <th width="15%">Email</th>
                                    <th  class="text-center d-print-none" width="10%">Edit</th>
                                    <th  class="text-center d-print-none" width="10%">Delete</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    <div class="modal fade user-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                            <textarea v-model="form.address" name="text" :class="{ 'is-invalid': form.errors.has('address') }" class="form-control" placeholder="Address"></textarea>
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
</div>

@endsection


@push('scripts')

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(function(){
        getUser();
    })

    function getUser(){
    $('#data_table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "order": [[ 0, "desc" ]],
            ajax:  "{{route('users.datatable')}}",
            columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'username', name: 'username' },
                        { data: 'email', name: 'email' },
                        { data: 'edit', name: 'edit' },
                        { data: 'delete', name: 'delete' },
                    ],
            "drawCallback": function( settings ) {
                $(".delete").click(function() {
                    event.preventDefault();
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won\'t be able to revert this!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                        if (result.value) {
                            window.location = $(this).attr("href");
                        }
                    })
                });
            }
    });
}

</script>

<script>
    const app = new Vue({
        el: '#user',
        data:{
            serial_no: 1,
            loading:false,
            countries: [],
            currentIndex: '',
            form: new Form({
                id: '',
                name: '',
                username: '',
                email: '',
                password: '',
                phone: '',
                address: '',
                city: '',
                zipcode: '',
                country: '',
            })
        },
        methods:{
            saveUser(){
                this.loading = true;
                this.form.post('users').then(response=> {
                    this.loading=false
                    this.form.reset();
                    $('.user-modal').modal('hide');
                    this.$toastr.s(
                        "User created successfully"
                    )
                    getUser();
                })
                .catch(e=>{
                    console.log(e);
                    this.loading=false
                })
            },
            openModal(){
                $('.user-modal').modal('show')
            }
        }
    });
</script>

@endpush
