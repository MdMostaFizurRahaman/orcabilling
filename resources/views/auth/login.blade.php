<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('theme')}}/assets/images/favicon.png">
    <title>{{env('APP_NAME')}} | Login</title>
    <!-- Custom CSS -->
    <link href="{{asset('theme')}}/dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper bg-dark d-flex no-block justify-content-center align-items-center">
            <div class="auth-box">
                <div id="loginform">
                    <div class="logo">
                        {{-- <span class="db"><img src="{{asset('theme')}}/assets/images/logo-icon.png" alt="logo" /></span> --}}
                    <h2 class="font-medium text-uppercase"><span>[</span><span class="text-info">{{env('APP_NAME')}}</span><span>]</span></h2>
                    <h4 class="font-medium m-b-20 ">Welcome, Back</h4>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="{{ Request::is('client/login') ? route('client.login') : route('login') }}">
                                @csrf
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input id="username" name="username" type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" placeholder="Username">
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                     @enderror
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input id="password" name="password" type="password" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Password" >
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                            @if (Route::has('password.request'))
                                                {{-- <a class="btn btn-link text-dark float-right" href="{{ route('password.request') }}">{{ __('Forgot pwd?') }}</a> --}}
                                                <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button class="btn btn-block btn-lg btn-info" type="submit">Log In</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="recoverform">
                    <div class="logo">
                        <h2 class="font-medium text-uppercase"><span>[</span><span class="text-info">{{env('APP_NAME')}}</span><span>]</span></h2>
                        <h4 class="font-medium m-b-20 ">Welcome, Back</h4>
                        <span id="message">Enter your Email and instructions will be sent to you!</span>
                    </div>
                    <div class="row m-t-20">
                        <!-- Form -->
                        <form id="reset_pass" class="col-12" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <!-- email -->
                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control form-control-lg" type="email" name="email" required="" placeholder="youremail@domain.com">
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row m-t-20">
                                <div class="col-12">
                                    <button class="btn btn-block btn-lg btn-danger" type="submit" name="">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{asset('theme')}}/assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('theme')}}/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{asset('theme')}}/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    // ==============================================================
    // Login and Recover Password
    // ==============================================================
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });

    $('#reset_pass').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'), // Get the action URL to send AJAX to
            type: "post",
            data: new FormData(this), // get all form variables
            cache:false,
            contentType: false,
            processData: false,
            success: function(response){
                console.log(response.message)
                $('#message').addClass('alert alert-success').html(response.message)
            }
        });
    });

    </script>
</body>

</html>

