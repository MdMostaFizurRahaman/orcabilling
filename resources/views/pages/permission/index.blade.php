@extends('layouts.app')


@section('title')
    Permissions
@endsection


@section('content')
    <div class="container-fluid">
        <div class="row" id="permission">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center m-b-30">
                            <h4 class="card-title">Assign Permissions</h4>
                        </div>
                        <form @submit.prevent = "assignPermisson()">
                           <div class="row">
                                <div class="col-lg-4">
                                        <label for="">Select User</label>
                                        <div class="input-group mb-3">
                                            <select  v-model="form.user" v-on:change="getUserPermissions()" name="user" class="form-control" :class="{ 'is-invalid': form.errors.has('user') }">
                                                <option v-for='user in users' :key='user.id' :value='user.id'>@{{user.username}}</option>
                                            </select>
                                            <has-error :form="form" field="user"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">Select Role</label>
                                        <div class="input-group mb-3">
                                            <select  v-model="form.role" v-on:change="getRolePermissions()" name="role" class="form-control" :class="{ 'is-invalid': form.errors.has('role') }">
                                                <option v-for='role in roles' :key='role.name' :value='role.name'>@{{role.name}}</option>
                                            </select>
                                            <has-error :form="form" field="role"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="invisible">Save</label>
                                        <button :disabled="form.busy" type="submit" class="btn btn-success btn-block"><i class="ti-save m-r-5"></i> Save</button>
                                    </div>
                           </div>
                        </form>
                        <div v-show="showAlert" class="alert alert-warning alert-rounded"> <i class="ti-alert"></i> If you select both user & role permissions will be merged.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                        </div>

                        <div class="row m-b-10 m-t-10">
                            <div class="col-lg-12">
                                <input class="form-control" type="text" v-model="search" placeholder="Search Permissions..." />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-6 m-t-10" v-show="!search">
                                <div  class="list-group-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkAll" v-model="allChecked" v-on:change="checkAll()">
                                        <label class="custom-control-label" for="checkAll">Check All</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 m-t-10" v-for="permission in filteredPermissions" :key="permission.id">
                                <div  class="list-group-item">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" :id="permission.name" :value="permission.name" v-model="form.checkedPermissions">
                                            <label class="custom-control-label" :for="permission.name">  @{{permission.name}}</label>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
<script>

const app = new Vue({
        el: '#permission',

        data:{
            getUserUrl : "{{route('users')}}",
            getRoleUrl : "{{route('roles')}}",
            users: [],
            roles: [],
            permissions : [],
            search:'',
            allChecked: false,
            form: new Form({
                    user: '',
                    role: '',
                    checkedPermissions: [],
            })
        },
        computed: {
            showAlert() {
                if(this.form.user !== '' & this.form.role !== ''){
                    return true;
                }else{
                    return false;
                }
            },

            filteredPermissions: function (){
                return this.permissions.filter(permission => {
                    return permission.name.match(this.search);
                });
            }
        },
        methods:{
            checkAll(){
                if(this.allChecked){
                    this.permissions.forEach(permission => {
                        this.form.checkedPermissions.push(permission.name);
                    });
                } else {
                    this.form.checkedPermissions = []
                }
            },
            getUsers(){
                axios.get(this.getUserUrl)
                    .then(res=>{
                        console.log(res.data)
                        this.users = res.data
                    })
                    .catch(e=>{
                        alert(e)
                    })
            },
            getRolesName(){
                axios.get(this.getRoleUrl)
                    .then(res=>{
                       this.roles = res.data
                    })
                    .catch(e=>{
                        alert(e)
                    })
            },
            getPermissions(){
                axios.get('{{route("permissions")}}')
                    .then(res=>{
                        this.permissions = res.data;
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            assignPermisson(){
                this.form.post('assignPermissions')
                    .then(res=>{
                        this.$toastr.s(
                            "Your changes has been saved successfully"
                        )
                    })
                    .catch(e=>{
                        alert(e)
                    })
            },
            getUserPermissions(){
                var id = this.form.user;
                this.form.allChecked = false;
                this.form.checkedPermissions = [];
                this.form.role='';
                axios.post("{{route('getUserPermissions')}}",{id: id})
                    .then(res=>{
                        this.form.role = '';
                        this.form.checkedPermissions = res.data;
                    })
                    .catch(e=>{
                       alert(e)
                    })
            },
            getRolePermissions(){
                var role = this.form.role;
                this.form.allChecked = false;
                this.form.checkedPermissions = [];
                this.form.user='';
                axios.post("{{route('getRolePermissions')}}",{role: role})
                    .then(res=>{
                        this.form.user = '';
                        this.form.checkedPermissions = res.data;
                    })
                    .catch(e=>{
                       alert(e)
                    })
            }
        },
        mounted(){
            this.getUsers()
            this.getRolesName()
            this.getPermissions()
        }
    });

</script>
@endpush
