@extends('layouts.app')

@section('title')
    CDR Logs
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endpush

@section('content')
<div class="container-fluid loaded" id="cdrlog">
    <!--Preloader-->
    @include('layouts.modules.preloader')
<!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block align-items-center m-b-30">
                            <h4 class="card-title">CDR Logs</h4>
                        </div>
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered nowrap display">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Log Name</th>
                                        <th>Total Logs</th>
                                        <th>Status</th>
                                        <th>Processed time</th>
                                        <th>Show Report</th>
                                        <th>Reparse</th>
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

        @include('pages.cdrlogs.cdr-modal')


@endsection


@push('scripts')

{{-- DataTable --}}
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
@routes()
<script>

    $(function(){
        getCdrLogs();
    })

    function sweetAlert (title, msg, icon, confirmButton)
    {
        Swal.fire({
            title: title,
            text: msg,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: confirmButton
        }).then(response => {
            return response.value;
        })
    }

    function getCdrLogs(){
        $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "order": [[ 0, "desc"]],
                ajax:  "{{route('get.cdr.logs')}}",
                columns: [
                            { data: 'file_name', name: 'file_name' },
                            { data: 'rows_count', name: 'rows_count' },
                            { data: 'status', name: 'status' },
                            { data: 'processed_time', name: 'processed_time' },
                            { data: 'show', name: 'show' },
                            { data: 'reparse', name: 'reparse' },
                        ],
                "drawCallback": function( settings ) {
                    $('.show').click(function(){
                        var file_name = $(this).data("file_name");
                        app.show(file_name);
                    });
                    $(".reparse").click(function() {
                        event.preventDefault();
                        Swal.fire({
                            title: "Are you sure?",
                            text: "You won\'t be able to revert this!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, reparse!"
                            }).then((result) => {
                            if (result.value) {
                                var file_name = $(this).data("file_name");
                                $('#cdrlog').removeClass('loaded');
                                var response = app.reparse(file_name);
                            }
                        });
                    });
                }
        });
    }


// Vue Model
const app = new Vue({
        el: '#cdrlog',
        data:{
            disabled: false,
            cdrlogs: [],
            cdrlog: {status_report: ''},
            status_report: {line: '', msg: ''},
        },
        methods:{
            show(file_name){
                axios.get(route('cdr.log.show', file_name))
                    .then(res=>{
                        this.cdrlog = res.data
                        if(res.data.status_report)
                        {
                            this.cdrlog.status_report = JSON.parse(res.data.status_report);
                        }
                        $('.cdr-modal').modal('show')
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
            reparse(file_name){
                axios.get(route('cdr.log.reparse', file_name))
                    .then(response=>{
                        if(response.data.status)
                        {
                            $('#cdrlog').addClass('loaded');
                            var react = sweetAlert('Congrats!', response.data.msg, 'success', 'Great!');
                        } else {
                            $('#cdrlog').addClass('loaded');
                            var react = sweetAlert('Oops!', response.data.msg, 'error', 'OK');
                        }

                        getCdrLogs();
                        return react;
                    })
                    .catch(e=>{
                        alert(e);
                    })
            },
        },
        mounted(){
            getCdrLogs();
        }
    });

</script>
@endpush
