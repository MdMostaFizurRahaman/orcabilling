@extends('layouts.app')

@section('title')
    Tariffs
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid" id="tariff">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center m-b-30">
                            <h4 class="card-title">All Tariffs</h4>
                            <div class="ml-auto">
                                <div class="btn-group">
                                    <button type="button"  v-show="!disabled" v-on:click='create' class="btn btn-rounded btn-dark">Create New Tariff</button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" v-show="disabled" disabled  class="btn btn-rounded btn-dark">Create New Tariff</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered nowrap display">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Currency</th>
                                        <th>Created By</th>
                                        <th>Tariffs</th>
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

        @include('pages.tariffname.tariff-modal')


@endsection


@push('scripts')

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function(){
        getTariffnames();
    })

    function getTariffnames(){
        $('#data_table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "order": [[ 0, "desc" ]],
            ajax:  "{{route('tariffnames.datatable')}}",
            columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'currency_id', name: 'currency_id' },
                        { data: 'created_by', name: 'created_by' },
                        { data: 'tariff', name: 'tariff' },
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
                        icon: "warning",
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
        el: '#tariff',
        data:{
            disabled: false,
            editMode: false,
            currencies: [],
            form: new Form({
                    id: '',
                    name: '',
                    currency_id: '',
            })
        },
        methods:{
            create(){
                $('.tariff-modal').modal('show')
                this.editMode = false;
                this.form.reset()
            },
            save(){
                this.disabled = true;
                this.form.post('tariffnames')
                    .then(response => {
                        console.log(response.data)
                        this.disabled = false
                        this.form.reset()
                        this.$toastr.s(
                            "Tariffname created successfully"
                        )
                        $('.tariff-modal').modal('hide')
                        getTariffnames()
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false
                    })
            },
            edit(id){
                this.form.reset();
                this.editMode = true;
                this.getTariffname(id)
                this.openModal()
            },
            update(){
                this.disabled = true;
                this.form.put(`tariffnames/${this.form.id}`)
                    .then(response=>{
                        this.form.reset()
                        this.disabled = false;
                        this.editMode = false;
                        this.$toastr.s(
                            "Tariffname updated successfully"
                        )
                        getTariffnames()
                        $('.tariff-modal').modal('hide')
                    })
                    .catch(e=>{
                        alert(e);
                        this.disabled = false;
                    })
            },
            delete(id){
                this.form.delete(`tariffnames/${id}`)
                    .then(res=>{
                        if(res.data.status){
                            console.log(res.data)
                            this.$toastr.s(res.data.msg);
                            getTariffnames();
                        } else {
                            Swal.fire({
                                title: "Oops!",
                                text: res.data.msg,
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Fine"
                            })
                        }

                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            getCurrenciesName(id){
                axios.get('{{route("currencies")}}')
                    .then(res=>{
                        this.currencies = res.data
                    })
                    .catch(e=>{
                        alert(e)
                    })
            },
            getTariffname(id){
                axios.post('{{route("tariffname.show")}}', {id:id})
                    .then(res=>{
                        this.form.fill(res.data)
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            openModal(){
                    $('.tariff-modal').modal('show')
            }
        },
        mounted(){
            this.getCurrenciesName();
        }
    });

</script>
@endpush
