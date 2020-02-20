@extends('layouts.app')

@section('title')
    Company Settings
@endsection

@push('styles')
    {{-- <link rel="stylesheet" href="{{asset('css/app.css')}}"> --}}
    <link href="{{asset('theme')}}/assets/libs/dropify/css/dropify.css" rel="stylesheet">
@endpush


@section('content')

<div class="container-fluid" id="comapny-settings">

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
                        <h4 class="card-title">Company Settings</h4>
                    </div>
                    {{-- @if(!empty($company)) --}}
                    {!! Form::model($company, ['id' => 'company_update_form', 'route' => 'company.update', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                    {{-- @else
                    {!! Form::model($company, ['id' => 'company_form', 'route' => 'company.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                    @endif --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary card-outline mb-2" id="company_deails">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group @error('company_name'){{'is-invalid'}}@enderror">
                                                {{-- @csrf --}}
                                                {!! Form::hidden('id', null, ['class' => 'form-control']) !!}

                                                {!! Form::label('company_name', 'Company Name') !!}
                                                {!! Form::text('company_name', null, ['class' => 'form-control']) !!}
                                                @error('company_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group @error('invoice_prefix'){{'is-invalid'}}@enderror">
                                                {!! Form::label('invoice_prefix', 'Invoice Prefix') !!}
                                                {!! Form::text('invoice_prefix', null, ['class' => 'form-control']) !!}
                                                @error('invoice_prefix')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <img src="" alt="">
                                        <div class="col-sm-6" style="margin: auto">
                                            <div class="form-group @error('logo'){{'is-invalid'}}@enderror">
                                                <label for="">Logo Image</label>
                                                <input class="form-control dropify"  data-height="100" data-allowed-file-extensions="png jpg" type="file" name="logo" data-default-file="@if(!empty($company)){{asset($company->getFirstMediaUrl('avatar'))}}@endif">
                                                <small class="text-info">* Image height should be 200x50 px. </small>
                                                @error('logo')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group @error('phone'){{'is-invalid'}}@enderror">
                                                {!! Form::label('phone', 'Phone') !!}
                                                {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                                @error('phone')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group @error('city'){{'is-invalid'}}@enderror">
                                                {!! Form::label('city', 'City') !!}
                                                {!! Form::text('city', null, ['class' => 'form-control']) !!}
                                                @error('city')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group @error('zip_code'){{'is-invalid'}}@enderror">
                                                {!! Form::label('zip_code', 'Zip Code') !!}
                                                {!! Form::text('zip_code', null, ['class' => 'form-control']) !!}
                                                @error('zip_code')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group @error('country'){{'is-invalid'}}@enderror">
                                                {!! Form::label('country', 'Country') !!}
                                                {!! Form::text('country', null, ['class' => 'form-control']) !!}
                                                @error('country')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group @error('postal_address'){{'is-invalid'}}@enderror">
                                                {!! Form::label('postal_address', 'Postal Address') !!}
                                                {!! Form::text('postal_address', null, ['class' => 'form-control']) !!}
                                                @error('postal_address')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-primary card-outline mb-2" id="company_deails">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group @error('mail_from_email'){{'is-invalid'}}@enderror">
                                                {!! Form::label('mail_from_email', 'Mail from Email') !!}
                                                {!! Form::email('mail_from_email', null, ['class' => 'form-control']) !!}
                                                @error('mail_from_email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group @error('mail_from_name'){{'is-invalid'}}@enderror">
                                                {!! Form::label('mail_from_name', 'Mail from Name') !!}
                                                {!! Form::text('mail_from_name', null, ['class' => 'form-control']) !!}
                                                @error('mail_from_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group custom-control custome-checkbox @error('test_mail'){{'is-invalid'}}@enderror">
                                                {!! Form::label('test_mail', 'Send Test Mail', ['calss' => 'custom-control-label']) !!}
                                                {!! Form::checkbox('test_mail', '1', false, ['class' => 'form-control']) !!}
                                                @error('test_mail')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group @error('test_mail_address'){{'is-invalid'}}@enderror">
                                                {!! Form::label('test_mail_address', 'Test Mail Address') !!}
                                                {!! Form::email('test_mail_address', null, ['class' => 'form-control']) !!}
                                                @error('test_mail_address')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group @error('bank_details'){{'is-invalid'}}@enderror">
                                                {!! Form::label('bank_details', 'Bank Details') !!}
                                                {!! Form::textarea('bank_details', null, ['rows' => 5, 'class' => 'form-control']) !!}
                                                @error('bank_details')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit('Submit', ['class' => 'form-control btn btn-primary']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

    @include('sweetalert::alert')

    <script src="{{asset('theme')}}/assets/libs/dropify/js/dropify.js"></script>

    <script>

        // Dropify
        $('.dropify').dropify();
          //Override form submit
        function dropify(){

            $("form").on("submit", function (event) {
                event.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: $(this).attr('action'), // Get the action URL to send AJAX to
                    type: "post",
                    data: new FormData(this), // get all form variables
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response.status){
                            responseToast(response)
                        } else {
                            responseToast(response)
                        }
                    }
                });
            });
        }

        function responseToast(response){
            $.toast({
                heading: response.status,
                text: response.message,
                icon: response.status,
                loader: true,
                loaderBg: '#9EC600',  // To change the background
                position: 'bottom-right',
                hideAfter : 2000,
                showHideTransition: 'slide',
                // transition : 'slide',
            })
            setTimeout(function(){
                window.location.reload(true);
                // window.location.replace("http://logicbag.com.bd/backend/products");
            }, 2000)
        }

    </script>

@endpush
