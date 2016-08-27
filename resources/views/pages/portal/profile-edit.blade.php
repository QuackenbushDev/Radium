@extends('master')

@section('pageTitle', $user->username)
@section('pageDescription', 'Details')

@push('breadcrumbs')
    <li><i class="fa fa-user"></i> Profile</li>
    <li>Edit</li>
    <li><a href="{{ route('portal::editProfile', $user->username) }}">{{ $user->username }}</a></li>
@endpush

@section('content')
    <div class="box">
        <div class="box-header">
            <h3>Contact Information</h3>
        </div>
        <div class="box-body">
            {!! BootForm::open()->action(route('portal::saveProfile')) !!}
            <input type="hidden" name="_method" value="PUT">
            <div class="col-md-6">
                {!! BootForm::text('Name', 'userinfo_name')->value($userInfo->name) !!}
                {!! BootForm::email('E-Mail', 'userinfo_email')->value($userInfo->email) !!}
                {!! BootForm::text('Company', 'userinfo_company')->value($userInfo->company) !!}
                {!! BootForm::text('Home Phone', 'userinfo_home_phone')->value($userInfo->home_phone) !!}
                {!! BootForm::text('Mobile Phone', 'userinfo_mobile_phone')->value($userInfo->mobile_phone) !!}
                {!! BootForm::text('Office Phone', 'userinfo_office_phone')->value($userInfo->office_phone) !!}
                {!! BootForm::textArea('Address', 'userinfo_address')->rows(3)->value($userInfo->address) !!}
                {!! BootForm::text('Password', 'user_password') !!}

                @if ($userInfo->enable_daily_summary)
                    {!! BootForm::checkbox('Enable weekly usage summary', 'userinfo_enable_weekly_summary')->checked() !!}
                @else
                    {!! BootForm::checkbox('Enable weekly usage summary', 'userinfo_enable_weekly_summary') !!}
                @endif

                @if ($userInfo->enable_monthly_summary)
                    {!! BootForm::checkbox('Enable monthly summary', 'userinfo_enable_monthly_summary')->checked() !!}
                @else
                    {!! BootForm::checkbox('Enable monthly summary', 'userinfo_enable_monthly_summary') !!}
                @endif

                <div class="col-md-12 text-right">
                    <a class="btn btn-lg btn-danger" href="{{ route('portal::profile', $user->username) }}">Cancel</a>
                    {!! BootForm::submit('Submit')->addClass('btn-primary btn-lg') !!}
                </div>
            </div>
            {!! BootForm::close() !!}

        </div>
    </div>
@endsection
