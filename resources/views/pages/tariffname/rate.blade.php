@extends('layouts.app')

@section('title')
    Tariff Rates
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid" id="rate">

        @if(session()->has("success"))
        <div class="alert alert-bordered alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <strong><i class="fa fa-check-circle"></i> Success!</strong> {{session()->get('success')}}
        </div>
        @endif

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center m-b-30">
                            <h4 class="card-title">All Rate List</h4>
                            <div class="ml-auto">
                                <div class="btn-group">
                                    <button id="import" type="button" data-toggle="tooltip" title="Import rates"  class="btn btn-rounded btn-info"> <i class="mdi mdi-import m-r-5"></i>Import</button>
                                    <a href="{{route('rate.export', Request::segment(2))}}" data-toggle="tooltip" title="Export rates"  class="btn btn-rounded btn-info"> <i class="mdi mdi-export m-r-5"></i>Export</a>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  v-show="!disabled" v-on:click='create' class="btn btn-rounded btn-dark">Create New Rate</button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" v-show="disabled" disabled  class="btn btn-rounded btn-dark">Create New Rate</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered nowrap display">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Prefix</th>
                                        <th>Description</th>
                                        <th>Voice Rate</th>
                                        <th>Resolution</th>
                                        <th>Effective Date</th>
                                        <th>View</th>
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
            @include('pages.tariffname.import-modal')
            @include('pages.tariffname.rate-fullscreen-modal-create')
            @include('pages.tariffname.rate-fullscreen-modal-view')
            @include('pages.tariffname.rate-fullscreen-modal-edit')
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->

@endsection


@push('scripts')

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
        $(function(){
            getTariffRates();
            $('[data-toggle="tooltip"]').tooltip()
            $('#import').click(function(){
                $('#import-modal').modal('show');
            })
        })

        function getTariffRates(){

        $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "order": [[ 0, "desc" ]],
                ajax:  "{{route('getTariffRates',$tariffname_id)}}",
                columns: [
                            { data: 'id', name: 'id' },
                            { data: 'prefix', name: 'prefix' },
                            { data: 'description', name: 'description' },
                            { data: 'voice_rate', name: 'voice_rate' },
                            { data: 'resolution', name: 'resolution' },
                            { data: 'effective_date', name: 'effective_date' },
                            { data: 'view', name: 'view' },
                            { data: 'edit', name: 'edit' },
                            { data: 'delete', name: 'delete' },
                        ],
                "drawCallback": function( settings ) {
                    $('.view').click(function(){
                        var id = $(this).data("id");
                        app.view(id);
                    });
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
        el: '#rate',
        data:{
            file: '',
            disabled: false,
            editMode: false,
            disabled: false,
            currencies: [],
            form: new Form({
                    tariffname_id: '{{$tariffname_id}}',
                    id: '',
                    prefix: '',
                    description: '',
                    from_day: '',
                    to_day: '',
                    from_hour: '',
                    to_hour: '',
                    voice_rate: '',
                    grace_period: '',
                    minimal_time: '',
                    resolution: '',
                    rate_multiplier: '',
                    effective_date: '{{ date("Y-m-d H:i:s")}}',
                    status: false,
            })
        },
        methods:{
            create(){
                this.form.reset()
                $('#rate-fullscreen-modal-create').modal('show')
                this.editMode = false;
            },
            save(){
                this.disabled = true;
                this.form.post('{{route("rate.store")}}')
                    .then(response => {
                        console.log(response.data)
                        this.disabled = false
                        this.form.reset()
                        $('#rate-fullscreen-modal-create').modal('hide')
                        this.$toastr.s(
                            "Rate created successfully"
                        )
                        this.loadRates()
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false
                    })
            },
            view(id){
                this.getRate(id)
                $('#rate-fullscreen-modal-view').modal('show')
            },
            edit(id){
                this.form.reset();
                this.editMode = true;
                this.getRate(id)
                $('#rate-fullscreen-modal-edit').modal('show')
            },
            update(){
                this.disabled = true;
                this.form.put(`${this.form.id}`)
                    .then(response=>{
                        this.form.reset()
                        this.disabled = false;
                        this.editMode = false;
                        this.$toastr.s(
                            "Rate updated successfully"
                        )
                        $('#rate-fullscreen-modal-edit').modal('hide')
                        this.loadRates()
                    })
                    .catch(e=>{
                        alert(e);
                        this.disabled = false;
                    })
            },
            delete(id){
                this.form.delete(`${id}`)
                    .then(res=>{
                        console.log(res.data)
                        this.$toastr.s(
                            "Rate deleted successfully"
                        );
                        this.loadRates()
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            getRate(id){
                axios.post('{{route("getRateDetails")}}', {id:id})
                    .then(res=>{
                        this.form.fill(res.data)
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            openModal(){
                    $('#rate-fullscreen-modal-create').modal('show')
            },
            submitForm(){
                    this.disabled = true;
                    let formData = new FormData();
                    formData.append('file', this.file);

                    axios.post('{{route("rate.import")}}',
                        formData,
                        {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                    ).then(res=>{
                        this.disabled = false;
                        this.$toastr.s(
                            "File imported successfully"
                        );
                        $('#import-modal').modal('hide')
                        this.loadRates()
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false;
                    });
            },
            onChangeFileUpload(){
                this.file = this.$refs.file.files[0];
            },
            loadRates(){
                setTimeout(function(){
                    getTariffRates()
                }, 3000)
            }
        },
        mounted(){

        }
    });

</script>
@endpush
