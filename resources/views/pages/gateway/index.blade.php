@extends('layouts.app')

@section('title')
    Gateways
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid" id="gateway">

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
                            <h4 class="card-title">All Gateway List</h4>
                            <div class="ml-auto">
                                <div class="btn-group">
                                    <button type="button"  v-show="!disabled" v-on:click='create' class="btn btn-rounded btn-dark">Create New Gateway</button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" v-show="disabled" disabled  class="btn btn-rounded btn-dark">Create New Gateway</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered wrap display">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>IP</th>
                                        <th>Port</th>
                                        <th>Call Limit</th>
                                        <th>Media Proxy</th>
                                        <th>Tariff</th>
                                        <th>Balance</th>
                                        <th>Payment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            @include('pages.gateway.fullscreen-modal')
            @include('pages.gateway.fullscreen-modal-view')
            @include('pages.gateway.payment-fullscreen-modal')
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
            getGateways();
            $('[data-toggle="tooltip"]').tooltip()
            $('#import').click(function(){
                $('#import-modal').modal('show');
            })
        })

        function getGateways(){

        $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "order": [[ 0, "desc" ]],
                ajax:  "{{route('gateways.datatable')}}",
                columns: [
                            { data: 'id', name: 'id' },
                            { data: 'name', name: 'name' },
                            { data: 'ip', name: 'ip' },
                            { data: 'port', name: 'port' },
                            { data: 'call_limit', name: 'call_limit' },
                            { data: 'media_proxy', name: 'media_proxy' },
                            { data: 'tariff', name: 'tariff' },
                            { data: 'balance', name: 'balance' },
                            { data: 'payment', name: 'payment' },
                            { data: 'action', name: 'action' },
                        ],
                "drawCallback": function( settings ) {
                    $('.payment').click(function(){
                        var id = $(this).data("id");
                        app.payment(id);
                    });
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
        el: '#gateway',
        data:{
            file: '',
            disabled: false,
            editMode: false,
            tariffs: [],
            paymentTypes: [],
            payments:[],
            form: new Form({
                    id: '',
                    name: '',
                    username: '',
                    password: '',
                    ip: '',
                    port: '',
                    tariff_id: null,
                    call_limit: '',
                    media_proxy: 0,
                    status: false,
            }),
            form2: new Form({
                    client_id: '',
                    type: '',
                    description:'',
                    balance:'',

            })
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        methods:{
            create(){
                this.form.reset()
                $('#fullscreen-modal').modal('show')
                this.editMode = false;
                this.getTariffs();
            },
            save(){
                this.disabled = true;
                this.form.post('{{route("gateway.store")}}')
                    .then(response => {
                        console.log(response.data)
                        this.disabled = false
                        this.form.reset()
                        $('#fullscreen-modal').modal('hide')
                        this.$toastr.s(
                            "Gateway created successfully"
                        )
                        this.loadGateways()
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false
                    })
            },
            view(id){
                this.getGateway(id)
                this.getTariffs();
                $('#fullscreen-modal-view').modal('show')
            },
            edit(id){
                this.form.reset();
                this.editMode = true;
                this.getGateway(id)
                this.getTariffs();
                $('#fullscreen-modal').modal('show')
            },
            update(){
                this.disabled = true;
                this.form.put(`gateways/${this.form.id}`)
                    .then(response=>{
                        this.form.reset()
                        this.disabled = false;
                        this.editMode = false;
                        this.$toastr.s(
                            "Rate updated successfully"
                        )
                        $('#fullscreen-modal').modal('hide')
                        this.loadGateways()
                    })
                    .catch(e=>{
                        alert(e);
                        this.disabled = false;
                    })
            },
            delete(id){
                this.form.delete(`gateways/${id}`)
                    .then(res=>{
                        console.log(res.data)
                        this.$toastr.s(
                            "Gateway deleted successfully"
                        );
                        this.loadGateways()
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            payment(id){
                this.form2.reset();
                this.form2.client_id = id
                this.getPayments(id)
                this.getPaymentTypes()
                $('#payment-fullscreen-modal').modal('show')
            },
            savePayment(){
                this.disabled = true;
                this.form2.post('{{route("gateway.payment.store")}}')
                    .then(res=>{
                        console.log(res.data)
                        this.disabled = false
                        this.form2.reset()
                        $('#payment-fullscreen-modal').modal('hide')
                        this.$toastr.s(
                            "Payment saved successfully"
                        )
                        getGateways();
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false;
                    })
            },
            getPayments(id){
                axios.post('{{route("gateway.payments")}}', {id:id, type:100})
                    .then(res=>this.payments=res.data)
                    .catch(e=>alert(e))
            },
            getPaymentTypes(){
                axios.get('{{route("payment-types")}}')
                    .then(res=>{
                        this.paymentTypes = res.data
                    })
                    .catch(e=>alert(e))
            },
            getGateway(id){
                axios.post('{{route("gateway.show")}}', {id:id})
                    .then(res=>{
                        this.form.fill(res.data)
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            getTariffs(){
                axios.get('{{route("tariffnames")}}')
                    .then(res=>{
                       this.tariffs = res.data;
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            openModal(){
                    $('#fullscreen-modal').modal('show')
            },
            loadGateways(){
                setTimeout(function(){
                    getGateways()
                }, 3000)
            }
        }
    });

</script>
@endpush
