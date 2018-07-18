{{--<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin - Start Bootstrap Template</title>
    <!-- Bootstrap core CSS-->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Custom fonts for this template-->
    <script src="https://use.fontawesome.com/04597954ff.js"></script>
    <!-- Custom styles for this template-->
    <link href="/css/admin.min.css" rel="stylesheet">
</head>

<body class="bg-dark">
<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Login</div>
        <div class="card-body">
            {!! Form::open(['method' => 'POST', 'route' => 'login']) !!}
            <div class="form-group">
                {!! Form::label('email', 'Email Address:') !!}
                @if($errors->has('email') or $errors->has('password'))
                    {!! Form::email('email', null, ['class'=>'form-control has-error', 'required', 'autofocus']) !!}
                @else
                    {!! Form::email('email', null, ['class'=>'form-control', 'required', 'autofocus']) !!}
                @endif

            </div>
            <div class="form-group">
                {!! Form::label('password', 'Password:') !!}
                @if($errors->has('password'))
                    {!! Form::password('password', ['class'=>'form-control has-error', 'required']) !!}
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @else
                    {!! Form::password('password', ['class'=>'form-control', 'required']) !!}
                @endif
                @if($errors->has('email') or $errors->has('password'))
                    <div class="row">
                        <div class="alert alert-danger alert-dismissable fade show mt-4 mb-0">
                            <button class="close" data-dismiss="alert" type="button">
                                <span>&times;</span>
                            </button>
                            <strong style="font-size: 0.8em; padding-right: 3.75em">{{ $errors->first('email') }}</strong>
                        </div>
                    </div>
                @endif
            </div>
            <div class="form-group pt-2">
                {!! Form::submit('Login', ['class'=>'btn btn-primary btn-block']) !!}
            </div>
            {!! Form::close() !!}
            <div class="text-center">
                <a class="d-block small mt-3" href="register.html">Register an Account</a>
                <a class="d-block small" href="forgot-password.html">Forgot Password?</a>
            </div>
        </div>
    </div>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/jquery.easing.min.js"></script>
</body>

</html>--}}
        <!DOCTYPE html>
<html lang="es-MX" class="fa-events-icons-ready">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Illusion Touring Entertainment--Admin</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/public-layout.css">
    <script src="https://use.fontawesome.com/04597954ff.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans:700" rel="stylesheet">
    <link rel="icon" href="/assets/logos/logo.png" type="image/png">
    @yield('head')
    <style>
        @media screen and (min-width: 80em) {
            main{
                min-height: 1300px;
            }

            .dark-overlay{
                min-height: 1300px;
            }

        }
    </style>
</head>
<body>

@include('admin.navigation')

@yield('messages')

<div class="background-img">
    <img src="/assets/backgrounds/concert.jpg" alt="">
</div>
<div class="dark-overlay">
    <main id="main-section" class="pb-5" style="min-height: 670px">
        <div class="main-inner">


            <div class="container">
                <div class="col-lg-5 col-md-7 col-sm-9 col-10 mx-auto">
                    <div class="card bg-light card-form">
                        <div class="card-header text-dark font-weight-bold">
                            Log in to Illusion Touring Entertainment
                        </div>
                        <div class="card-body pt-0">
                            <div class="text-center">
                                <img class="img-fluid" src="/assets/logos/logo.png" width="146" height="105">
                            </div>

                            {!! Form::open(['method' => 'POST', 'route' => 'login']) !!}
                            <div class="form-group">
                                {!! Form::label('email', 'Email Address:') !!}
                                @if($errors->has('email') or $errors->has('password'))
                                    {!! Form::email('email', null, ['class'=>'form-control has-error', 'required', 'autofocus']) !!}
                                @else
                                    {!! Form::email('email', null, ['class'=>'form-control', 'required', 'autofocus']) !!}
                                @endif

                            </div>
                            <div class="form-group">
                                {!! Form::label('password', 'Password:') !!}
                                @if($errors->has('password'))
                                    {!! Form::password('password', ['class'=>'form-control has-error', 'required']) !!}
                                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                                @else
                                    {!! Form::password('password', ['class'=>'form-control', 'required']) !!}
                                @endif
                                @if($errors->has('email') or $errors->has('password'))
                                    <div class="row">
                                        <div class="alert alert-danger alert-dismissable fade show mt-4 mb-0">
                                            <button class="close" data-dismiss="alert" type="button">
                                                <span>&times;</span>
                                            </button>
                                            <strong style="font-size: 0.8em; padding-right: 3.75em">{{ $errors->first('email') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group pt-1" style="font-size:0.8rem">
                                {!! Form::checkbox('remember', 'yes') !!}
                                {!! Form::label('remember', 'Remember me?') !!}
                            </div>
                            <div class="form-group pt-2">
                                {!! Form::submit('Login', ['class'=>'btn btn-primary btn-block']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="card-footer">
                            <p class="m-0">
                                <a href="#" id="forgotPassword">Forgot Password?</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>




</div>
<footer id="main-footer" class="bg-dark fixed-bottom">
    <div class="container">
        <div class="row mt-1">
            <div class="col text-center text-white">
                <div class="py-2">
                    <img class="img-fluid" src="/assets/logos/logo.png" width="102" height="73">
                    <a class="h5" href="{{route('public.index')}}" style="position: relative; top:0.3em;">Illusion Touring Entertainment</a>
                </div>
            </div>
        </div>
    </div>
</footer>




<script src="/js/jquery.min.js"></script>
<script src="/js/tether.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script>
    $(window).resize(function(){
        if($(window).width() < 768){
            $(".drop_menu").addClass('hamburger_menu');
        }else if($(window).width() > 768){
            $(".drop_menu").removeClass('hamburger_menu');
        }
    });
</script>
@yield('scripts')
</body>
</html>

