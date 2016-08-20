@extends('blank')

@section('pageTitle', 'Portal Login')
@section('pageDescription', '')

@section("content")
    <div class="login-box">
        <div class="login-logo">
            <a href="https://github.com/QuackenbushDev/Radium"><b>Radium</b></a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>

            @if(session()->has('error'))
                <div class="alert alert-danger">
                    <p>{{ session()->get('error') }}</p>
                </div>
            @endif

            <form action="{{ $action }}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="Username" value="{{ $username }}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <a href="{{ $forgotPasswordLink }}">I forgot my password</a><br>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
