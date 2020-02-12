@extends('layouts.app')

@section('title')
    Activity Log
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid" id="access_log">

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
                    <div class="d-flex no-block align-items-center m-b-10">
                        <h4 class="card-title">Activity Log</h4>
                        <div class="ml-auto">
                            {{-- Something can go here. --}}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="data_table" class="table table-bordered table-striped wrap display">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User Name</th>
                                    <th>Date</th>
                                    <th>IP</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        @include('pages.system.acces-log-modal')
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
        getUsersLog();
        $('[data-toggle="tooltip"]').tooltip()
    })

    function getUsersLog(){

        $('#data_table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "order": [[ 0, "desc" ]],
            ajax:  "{{route('system.access-log.datatable')}}",
            columns: [
                        { data: 'id_log', name: 'id_log' },
                        { data: 'user', name: 'user_id' },
                        { data: 'log_dt', name: 'log_dt' },
                        { data: 'user_ip', name: 'user_ip' },
                        { data: 'action', name: 'action' },
                        { data: 'status', name: 'status' },
                        { data: 'view', name: 'view' },
                    ],
            "drawCallback": function( settings ) {
                $('.view').click(function(){
                    var id = $(this).data("id");
                    app.view(id);
                });

            }
        });
    }



const app = new Vue({
        el: '#access_log',
        data:{
            log: [],
        },
        methods:{
            view(id){
                this.getActivityLog(id)
                $('#access-log-modal').modal('show')
            },
            getActivityLog(id){
                axios.get(route("system.access-log.show", id))
                    .then(res=>{
                        this.log = res.data
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            closeModal(){
                $(".result-modal").on('hide.bs.modal', function(){
                    $('.result-modal').addClass('fade')
                }).modal('hide');
            },
        }
    });

</script>
@endpush
