@extends('iframe')

@section('content')
    {!! BootForm::open()->action(route('user::doDisconnect', $user->id)) !!}

    <div class="row">
        <div class="col-sm-12">
            {!! BootForm::hidden('user_id')->value($user->id) !!}
            {!! BootForm::text('Username', 'username')->value($user->username)->disabled() !!}
            {!! BootForm::select('Packet Type', 'packet_type', ['disconnect' => 'PoD - Packet of Disconnect', 'coa' => 'CoA - Change of Authorization']) !!}
            {!! BootForm::select('Nas', 'nas_id', $nasList) !!}
            {!! BootForm::select('Nas Port', 'nas_port', ['Choose Port', '3799' => '3799', '1700' => '1700']) !!}
            {!! BootForm::textArea('Custom Attributes', 'attributes')->rows(3) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            {!! BootForm::submit('Disconnect User')->addClass('pull-right btn-lg btn-danger') !!}
        </div>
    </div>
    {!! BootForm::close() !!}
@endsection