@extends('master')

@section('pageTitle', 'Dashboard')
@section('pageDescription', '')
@push('breadcrumbs')
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
@endpush

@section("content")
    <div class="row">

    </div>

    @include(
        'widgets.bandwidth-chart',
        [
            'id'        => 'dashboardBandwidthMonthlySummary',
            'title'     => date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'month',
            'timeValue' => date('Y'),
            'username'  => "",
            'nasID'     => "",
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.bandwidth-chart',
        [
            'id'        => 'dashboardBandwidthDailySummary',
            'title'     => date('M') . ' ' . date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'day',
            'timeValue' => date('m'),
            'username'  => "",
            'nasID'     => "",
            'height'    => '300px',
        ]
    )

    <div class="row">
        @include(
            'widgets.table',
            [
                'title'    => 'Recent Logins',
                'headers'  => ['ID', 'Username', 'Reply', 'Auth Date'],
                'dataSet'  => $loginAttempts,
                'colWidth' => 12
            ]
        )
    </div>
@endsection
