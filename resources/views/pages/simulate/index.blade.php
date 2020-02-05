@extends('layouts.app')

@section('title')
    Bill Simulate
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid" id="simulate">

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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="align-items-center">
                            <h4 class="card-title">Simulation Page</h4>
                        </div>
                        <div class="row">
                            {{-- General Information --}}
                            <div class="col-md-6">
                                <form @submit.prevent="simulate()">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex no-block align-items-center">
                                            <h4 class="card-title">Bill Simulate</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="">Dialed Number</label>
                                            <input v-model="form.dialed_number"  type="text" name="dialed_number" :class="{ 'is-invalid': form.errors.has('dialed_number') }" class="form-control">
                                            <has-error :form="form" field="dialed_number"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Client IP</label>
                                            <select  v-model="form.client_ip" name="client_ip" class="form-control" :class="{ 'is-invalid': form.errors.has('client_ip') }">
                                                <option v-for='client in clientIps' v-bind:value='client.ip'> @{{client.ip}}</option>
                                            </select>
                                            <has-error :form="form" field="client_ip"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Gateway IP</label>
                                            <select v-model="form.gateway_ip" name="gateway_ip" class="form-control" :class="{ 'is-invalid': form.errors.has('gateway_ip') }">
                                                <option v-for='gateway in gatewayIps' v-bind:value='gateway.ip'> @{{gateway.name}}</option>
                                            </select>
                                            <has-error :form="form" field="gateway_ip"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Duration</label>
                                            <input v-model="form.duration"  type="text" name="duration" :class="{ 'is-invalid': form.errors.has('duration') }" class="form-control">
                                            <has-error :form="form" field="duration"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="">CLI</label>
                                            <input v-model="form.dialing_number"  type="text" name="dialing_number" :class="{ 'is-invalid': form.errors.has('dialing_number') }" class="form-control">
                                            <has-error :form="form" field="dialing_number"></has-error>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-md btn-rounded">Simulate</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->

        @include('pages.simulate.result-modal')

@endsection


@push('scripts')

{{-- DataTable --}}
<script>

    const app = new Vue({
        el: '#simulate',
        data:{
            clientIps: [],
            gatewayIps:[],
            result: {c_rate: [], g_rate: []},
            form: new Form({
                    dialed_number: '',
                    client_ip: '',
                    gateway_ip: '',
                    duration: '',
                    dialing_number: '',
            })
        },
        methods:{
            simulate(){
                this.form.post('{{route("bill.simulate")}}')
                    .then(response => {
                        if(response.data.status)
                        {
                            this.result = response.data;
                            this.result.c_rate = response.data.c_rate;
                            this.result.g_rate = response.data.g_rate;
                            this.showResult()
                            this.form.reset()
                        } else {
                            Swal.fire({
                                title: "Oops!",
                                text: response.data.msg,
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Retry"
                            })
                        }
                    })
                    .catch(e=>{
                        alert(e)
                        console.log(e);
                    })
            },
            getClients(){
                axios.get('{{route("clients.ips")}}')
                    .then(res=>{
                        this.clientIps = res.data
                    })
                    .catch(e=>alert(e))
            },
            getGateways(){
                axios.get('{{route("gateways.ips")}}')
                    .then(res=>{
                       this.gatewayIps = res.data;
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            showResult(){
                $('.result-modal').removeClass('fade')
                $('.result-modal').modal('show')
            },
            closeModal(){
                $(".result-modal").on('hide.bs.modal', function(){
                    $('.result-modal').addClass('fade')
                }).modal('hide');
            }
        },
        mounted() {
            this.getGateways();
            this.getClients();
        }
    });

</script>
@endpush
