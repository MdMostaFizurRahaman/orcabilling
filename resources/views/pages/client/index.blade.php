@extends('layouts.app')

@section('title')
    Clients
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid" id="client">

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
                            <h4 class="card-title">All Client List</h4>
                            <div class="ml-auto">
                                <div class="btn-group">
                                    <button type="button"  v-show="!disabled" v-on:click='create' class="btn btn-rounded btn-dark">Add New Client</button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" v-show="disabled" disabled  class="btn btn-rounded btn-dark">Add New Client</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered wrap display">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Capacity</th>
                                        <th>Balance</th>
                                        <th>Tariff</th>
                                        <th>IPs</th>
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
            @include('pages.client.fullscreen-modal')
            @include('pages.client.fullscreen-modal-view')
            @include('pages.client.payment-fullscreen-modal')
            @include('pages.client.ip-fullscreen-modal')
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->

@endsection


@push('scripts')
    @routes()

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
        $(function(){
            getClients();
            $('[data-toggle="tooltip"]').tooltip()
            $('#import').click(function(){
                $('#import-modal').modal('show');
            })
        })

        function getClients(){

        $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "order": [[ 0, "desc" ]],
                ajax:  "{{route('clients.datatable')}}",
                columns: [
                            { data: 'id', name: 'id' },
                            { data: 'username', name: 'username' },
                            { data: 'capacity', name: 'capacity' },
                            { data: 'account_state', name: 'account_state' },
                            // { data: 'tariff_id', name: 'tariff_id', render:function(data, type, row){
                            //         return "<a href='" + route('rate.index', row.tariff_id) + "' class='view' data-id='" + row.tariff_id +"'>" + row.tariff_id + "</a>"
                            //     }
                            // },
                            { data: 'tariff', name: 'tariff' },
                            { data: 'ip', name: 'ip' },
                            { data: 'payment', name: 'payment' },
                            { data: 'action', name: 'action' },
                        ],
                "drawCallback": function( settings ) {
                    $('.ip').click(function(){
                        var id = $(this).data("id");
                        app.clientIps(id);
                    });
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
        el: '#client',
        data:{
            file: '',
            disabled: false,
            editMode: false,
            disabled: false,
            tariffs: [],
            countries: [],
            paymentTypes: [],
            payments:[],
            ips:[],
            form: new Form({
                    id: '',
                    username: '',
                    password: '',
                    tariff_id: null,
                    credit: null,
                    capacity: null,
                    route_type: 0,
                    full_name: null,
                    email: null,
                    telephone: null,
                    mobile: null,
                    city: null,
                    country: null,
                    address: null,
                    zip: null,
                    status: false,
            }),
            form2: new Form({
                    client_id: '',
                    type: '',
                    description:'',
                    balance:'',

            }),
            form3: new Form({
                    id :'',
                    client_id: '',
                    ip: '',
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
                this.getCountries()
            },
            save(){
                this.disabled = true;
                this.form.post('{{route("client.store")}}')
                    .then(response => {
                        console.log(response.data)
                        this.disabled = false
                        this.form.reset()
                        $('#fullscreen-modal').modal('hide')
                        this.$toastr.s(
                            "Client created successfully"
                        )
                        this.loadClients()
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false
                    })
            },
            view(id){
                this.getClient(id)
                this.getTariffs();
                $('#fullscreen-modal-view').modal('show')
            },
            edit(id){
                this.form.reset();
                this.editMode = true;
                this.getClient(id)
                this.getTariffs();
                this.getCountries()
                $('#fullscreen-modal').modal('show')
            },
            update(){
                this.disabled = true;
                this.form.put(`clients/${this.form.id}`)
                    .then(response=>{
                        this.form.reset()
                        this.disabled = false;
                        this.editMode = false;
                        this.$toastr.s(
                            "Client updated successfully"
                        )
                        $('#fullscreen-modal').modal('hide')
                        this.loadClients()
                    })
                    .catch(e=>{
                        alert(e);
                        this.disabled = false;
                    })
            },
            delete(id){
                this.form.delete(`clients/${id}`)
                    .then(res=>{
                        console.log(res.data)
                        this.$toastr.s(
                            "Client deleted successfully"
                        );
                        this.loadClients()
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
            clientIps(id){
                this.getIPs(id)
                $('#ip-fullscreen-modal').modal('show')
            },
            savePayment(){
                this.disabled = true;
                this.form2.post('{{route("client.payment.store")}}')
                    .then(res=>{
                        console.log(res.data)
                        this.disabled = false
                        this.form2.reset()
                        $('#payment-fullscreen-modal').modal('hide')
                        this.$toastr.s(
                            "Payment saved successfully"
                        )
                        getClients();
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false;
                    })
            },
            saveIP(){
                this.disabled = true;
                this.form3.post('{{route("ip.store")}}')
                    .then(res=>{
                        console.log(res.data)
                        this.disabled = false
                        this.form3.reset()
                        $('#ip-fullscreen-modal').modal('hide')
                        this.$toastr.s(
                            "IP saved successfully"
                        )
                    })
                    .catch(e=>{
                        alert(e)
                        this.disabled = false;
                    })
            },
            deleteIP(id){
                this.form.delete(`clients/ips/${id}`)
                    .then(res=>{
                        console.log(res.data)
                        this.$toastr.s(
                            "Ip deleted successfully"
                        );
                        $('#ip-fullscreen-modal').modal('hide')
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            getPayments(id){
                axios.post('{{route("client.payments")}}', {id:id, type:2})
                    .then(res=>this.payments=res.data)
                    .catch(e=>alert(e))
            },
            getIPs(id){
                axios.post('{{route("ip.index")}}', {id:id})
                    .then(res=>this.ips=res.data)
                    .catch(e=>alert(e))
            },
            getPaymentTypes(){
                axios.get('{{route("payment-types")}}')
                    .then(res=>{
                        this.paymentTypes = res.data
                    })
                    .catch(e=>alert(e))
            },
            getClient(id){
                axios.post('{{route("client.show")}}', {id:id})
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
            loadClients(){
                setTimeout(function(){
                    getClients()
                }, 3000)
            },
            getCountries(){
                axios.get('{{route("countries")}}')
                        .then(res=>this.countries = res.data)
                        .catch(e=>alert(e));
            }
        }
    });

</script>
@endpush
