@extends('layouts.basic')

@section('body')
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/home') }}"><b>{{ env('APP_TITLE') }}</b></a>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ ucfirst(trans('auth.reset-your-password')) }}</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="post" action="{{ url('/password/email') }}">
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

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">
                        <i class="fa fa-btn fa-envelope"></i> {{ ucfirst(trans('common.send')) }}
                    </button>
                </div>
            </div>

        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="/js/admin.js"></script>
</body>
@endsection
