@extends('layouts.app')

@section('title')
    Currencies
@endsection 

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid" id="currency">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <!-- Column -->
            <div class="col-lg-4 col-xl-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center m-b-30">
                            <h4 v-show="!editMode" class="card-title">Add Currency</h4>
                            <h4 v-show="editMode" class="card-title">Edit Currency</h4>
                        </div>
                        <form @submit.prevent="editMode ? update():save()">
                            @csrf
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                </div>
                                <input v-model="form.name" type="text" name="name" :class="{ 'is-invalid': form.errors.has('name') }" style="text-transform:uppercase" class="form-control" placeholder="Name">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-flag-checkered"></i></span>
                                </div>
                                <input v-model="form.symbol" type="text" name="symbol" :class="{ 'is-invalid': form.errors.has('symbol') }" class="form-control" placeholder="Symbol">
                                <has-error :form="form" field="symbol"></has-error>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                </div>
                                <input v-model="form.ratio" type="number" name="ratio" step="any" :class="{ 'is-invalid': form.errors.has('ratio') }" class="form-control" placeholder="Ratio">
                                <has-error :form="form" field="ratio"></has-error>
                            </div>
                            <button v-show="disabled"  disabled type="submit" class="btn btn-success btn-block"><i class="ti-save m-r-5"></i> Save</button>
                            <button v-show="!disabled" type="submit" class="btn btn-success btn-block"><i class="ti-save m-r-5"></i> Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-8 col-xl-8 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center m-b-30">
                            <h4 class="card-title">All Currencies</h4>
                        </div>
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered nowrap display">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Currency Name</th>
                                        <th>Symbol</th>
                                        <th>Ratio</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
    </div>
@endsection


@push('scripts')

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>    
<script>
        $(function(){
            getCurrencies();
        })

        function getCurrencies(){
        $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "order": [[ 0, "desc" ]],
                ajax:  "{{route('getCurrencies')}}",
                columns: [
                            { data: 'id', name: 'id' },
                            { data: 'name', name: 'name' },
                            { data: 'symbol', name: 'symbol' },
                            { data: 'ratio', name: 'ratio' },
                            { data: 'edit', name: 'edit' },
                            { data: 'delete', name: 'delete' },
                        ],
                "drawCallback": function( settings ) {
                    $('.edit').click(function(){
                        var id = $(this).data("id");
                        app.edit(id);
                    });
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
                                var id = $(this).data("id");
                                app.delete(id)
                            }
                        })
                    });
                }
        });
    }

   
const app = new Vue({
        el: '#currency',

        data:{
            disabled: false,
            editMode: false,
            disabled: false,
            form: new Form({
                    id: '',
                    name: '',
                    symbol: '',
                    ratio: '',
            })
        },
        methods:{
            save(){
                this.disabled = true;
                this.form.post('currencies')
                    .then(response => {
                        console.log(response.data)
                        this.disabled = false
                        this.form.reset()
                        this.$toastr.s(
                            "Currency created successfully"
                        )
                        getCurrencies()
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false
                    })
            },
            edit(id){
                this.editMode = true;
                this.getCurrency(id)
            },
            update(){
                this.disabled = true;
                this.form.put(`currencies/${this.form.id}`)
                    .then(response=>{
                        this.form.reset()
                        this.disabled = false;
                        this.editMode = false;
                        this.$toastr.s(
                            "Currency updated successfully"
                        )
                        getCurrencies()
                    })
                    .catch(e=>{
                        alert(e);
                        this.disabled = false;
                        // this.editMode = false;
                    })
            },
            delete(id){
                this.form.delete(`currencies/${id}`)
                    .then(res=>{
                        console.log(res.data)
                        this.$toastr.s(
                            "Currency deleted successfully"
                        );
                        getCurrencies();
                    })
                    .catch(e=>{
                        alert(e);
                    })
            }, 
            getCurrency(id){
                axios.post('{{route("getCurrency")}}', {id:id})
                    .then(res=>{
                        console.log(res.data);
                        this.form.fill(res.data)
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            }  
        }, 
        mounted(){
         
        }
    });

</script>
@endpush