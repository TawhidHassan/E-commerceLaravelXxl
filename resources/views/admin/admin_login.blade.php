<!DOCTYPE html>
<html lang="en">

<head>
    <title>Matrix Admin</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="{{asset('css/backend_css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/backend_css/bootstrap-responsive.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/backend_css/matrix-login.css')}}"/>
    <link href="{{asset('fonts/backend_fonts/css/font-awesome.css" rel="stylesheet')}}"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

</head>
<body>
<div id="loginbox">
    @if(Session::has('flash_message_error'))
       <div class="alert alert-error alert-block">
           <button type="button" class="close" data-dismiss="alert">x</button>
           <strong>{!!session('flash_message_error')!!}</strong>
       </div>
    @endif()
        @if(Session::has('flash_message_success'))
       <div class="alert alert-success alert-block">
           <button type="button" class="close" data-dismiss="alert">x</button>
           <strong>{!!session('flash_message_success')!!}</strong>
       </div>
    @endif()

    <form id="loginform" class="form-vertical" method="POST" action="{{ route('login') }}"
          aria-label="{{ __('Login') }}">
        @csrf
        <div class="control-group normal_text"><h3><img src="{{asset('images/backend_images/logo.png')}}" alt="Logo"/></h3></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"> </i></span><input type="email" placeholder="Email"
                                                                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                                       name="email"
                                                                                       value="{{ old('email') }}"
                                                                                       required autofocus/>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span><input type="password"
                                                                                      class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                                                      name="password" required/>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-left"><a href="{{ route('password.request') }}" class="flip-link btn btn-info"
                                       id="to-recover">Lost password?</a></span>
            <span class="pull-right"><button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                   </button>
</span>
        </div>
    </form>
    <form id="recoverform" action="#" class="form-vertical">
        <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a
            password.</p>

        <div class="controls">
            <div class="main_input_box">
                <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text"
                                                                                      placeholder="E-mail address"/>
            </div>
        </div>

        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-success"
                                       id="to-login">&laquo; Back to login</a></span>
            <span class="pull-right">
                <a class="btn btn-info"/>Reecover</a>
            </span>
        </div>
    </form>
</div>

<script src="{{asset('js/backend_js/jquery.min.js')}}"></script>
<script src="{{asset('js/backend_js/matrix.login.js')}}"></script>
</body>

</html>
