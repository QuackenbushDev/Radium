@extends('blank')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="https://github.com/QuackenbushDev/Radium"><b>Radium</b></a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Please select a new password below.</p>

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="col-md-12 has-feedback">
                        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="col-md-12 has-feedback">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <div class="col-md-12 has-feedback">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Password Confirmation">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
