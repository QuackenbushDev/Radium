@extends('master')

@section('pageTitle', 'Portal Dashboard')
@section('pageDescription', '')
@push('breadcrumbs')
    <li><a href="#"><i class="fa fa-dashboard"></i> Portal Dashboard</a></li>
@endpush

@section("content")
    <div class="row">
        <div class="col-md-4">
            @include(
                'pages.user.partials.bandwidth-summary',
                [
                    'title' => 'Daily Summary (' . date('M-d') . ')',
                    'data' => $bandwidthStats['day']
                ]
            )
        </div>

        <div class="col-md-4">
            @include(
                'pages.user.partials.bandwidth-summary',
                [
                    'title' => 'Monthly (' . date('M Y') . ')',
                    'data' => $bandwidthStats['month']
                ]
            )
        </div>

        <div class="col-md-4">
            @include(
                'pages.user.partials.bandwidth-summary',
                [
                    'title' => 'Yearly (' . date('Y') . ')',
                    'data' => $bandwidthStats['year']
                ]
            )
        </div>
    </div>

    @include(
        'widgets.bandwidth-chart',
        [
            'id'        => 'dashboardBandwidthMonthlySummary',
            'title'     => date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'month',
            'timeValue' => 2016,
            'username'  => session()->get('portal_username'),
            'nasIP'     => "",
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
            'username'  => session()->get('portal_username'),
            'nasIP'     => "",
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.connection-chart',
        [
            'id'        => 'dashboardConnectionSummary',
            'title'     => date('M') . ' ' . date('Y') . ' Connection Summary',
            'timeSpan'  => 'month',
            'timeValue' => date('m'),
            'username'  => session()->get('portal_username'),
            'nasIP'     => "",
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