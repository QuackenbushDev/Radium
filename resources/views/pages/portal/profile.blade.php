@extends('master')
@section('pageTitle', $user->username)
@section('pageDescription', 'Details')

@push('breadcrumbs')
    <li><i class="fa fa-user"></i> Profile</li>
    <li><a href="{{ route('portal::profile', $user->username) }}">{{ $user->username }}</a></li>
@endpush

@section('content')
    @include('pages.user.partials.account-info')
@endsection
