@extends('master')

@section('pageTitle', $user->username)
@section('pageDescription', 'Details')

@push('breadcrumbs')
    <li><a href="{{ route('user::index') }}"><i class="fa fa-group"></i> Users</a></li>
    <li><a href="#">Edit</a></li>
    <li><a href="{{ route('user::edit', $user->id)  }}">{{ $user->username }}</a></li>
@endpush

@section('content')
    {!! BootForm::open()->action(route('user::update', ['id' => $user->id])) !!}
    <input type="hidden" name="_method" value="PUT">
    @include('pages.user.partials.account_info_edit')

    <div class="row">
        <div class="col-md-12">
            <h3>Attributes</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-lg btn-danger" href="{{ route('user::show', $user->id) }}">Cancel</a>
            {!! BootForm::submit('Submit')->addClass('btn-primary btn-lg') !!}
        </div>
    </div>
    {!! BootForm::close() !!}
@endsection
