@extends('blank')
@section('pageTitle', 'Reset Password')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="https://github.com/QuackenbushDev/Radium"><b>Radium</b></a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Reset password</p>

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/portal/forgotPassword') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    <div class="col-md-12 has-feedback">
                        <input type="email" class="form-control" name="username" placeholder="username" value="{{ old('username') }}">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>

                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Send Password Reset Link</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
