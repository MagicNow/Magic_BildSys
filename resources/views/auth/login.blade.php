@extends('layouts.basic')

@section('body')
    <body class="hold-transition login-page">
        <div class="login-box">


            <!-- /.login-logo -->
            <div class="login-box-body">
                <div class="login-logo">
                    <a href="{{ url('/') }}"> <img src="{{ asset('img/logo_bild_sys.png') }}" style="margin: auto" class="img-responsive"></a>
                </div>
                <p class="login-box-msg">{{ ucfirst( trans('common.sign-in-to-start-your-session') )}}</p>

                <form method="post" action="{{ url('/login') }}">
                    {!! csrf_field() !!}

                    <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ ucfirst(trans('common.email')) }}">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if ($errors->has('email'))
                            <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" placeholder="{{ ucfirst(trans('common.password')) }}" name="password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @if ($errors->has('password'))
                            <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                        @endif

                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" name="remember"> {{ ucfirst(trans('auth.remember-me')) }}
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">{{ ucfirst(trans('common.sign-in')) }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <a href="{{ url('/password/reset') }}">{{ ucfirst(trans('auth.forgot-password')) }}</a><br>
                @if(env('APP_ALLOW_REGISTER'))
                    <a href="{{ url('/register') }}" class="text-center">{{ ucfirst(trans('auth.register')) }}</a>
                @endif

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <script src="{{ asset('js/admin.js') }}"></script>
    </body>
@endsection
