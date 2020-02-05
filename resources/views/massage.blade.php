@if(session()->has("error"))
<div class="alert alert-bordered alert-danger    alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <strong><i class="fas fa-times-circle"></i> Error!</strong> {{session()->get('error')}}
</div>
@endif



@if(session()->has("success"))
<div class="alert alert-bordered alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <strong><i class="fa fa-check-circle"></i> Success!</strong> {{session()->get('success')}}
</div>
@endif