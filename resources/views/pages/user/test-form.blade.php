@extends('blank')

@section('content')
    {!! BootForm::open()->action(route('user::doTest', $user->id)) !!}

    <div class="row">
        <div class="col-sm-12">
            {!! BootForm::hidden('user_id')->value($user->id) !!}
            {!! BootForm::text('Username', 'username')->value($user->username)->disabled() !!}
            {!! BootForm::text('Password', 'password')->value($user->value)->disabled() !!}
            {!! BootForm::text('Radius Server', 'radius_server')->value($radiusServer) !!}
            {!! BootForm::text('Radius Port', 'radius_port')->value($radiusPort) !!}
            {!! BootForm::text('Nas Ports', 'nas_ports')->value($nasPort) !!}
            {!! BootForm::text('Nas Secret', 'radius_secret')->value($radiusSecret) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            {!! BootForm::submit('Test User Connectivity')->addClass('pull-right btn-lg btn-success') !!}
        </div>
    </div>
    {!! BootForm::close() !!}
@endsection