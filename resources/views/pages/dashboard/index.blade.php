@extends('master')

@section('pageTitle', 'Dashboard')
@section('pageDescription', '')
@push('breadcrumbs')
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
@endpush

@section("content")
    <div class="row">
        @include(
            'widgets.3colpanel',
            [
                'widget_value'       => ($dailyStats !== null) ? $dailyStats['connections'] : 0,
                'widget_description' => 'Total connections on ' . date("m-d-Y"),
                'widget_link'        => '#',
                'widget_icon'        => 'fa-area-chart',
                'widget_class'       => 'bg-aqua'
            ]
        )

        @include(
            'widgets.3colpanel',
            [
                'widget_value'       => ($monthlyStats !== null) ? $monthlyStats['connections'] : 0,
                'widget_description' => 'Total connections for ' . date('M Y'),
                'widget_link'        => '#',
                'widget_icon'        => 'fa-area-chart',
                'widget_class'       => 'bg-maroon'
            ]
        )

        @include(
            'widgets.3colpanel',
            [
                'widget_value'       => $dailyTop['upload'] . "GB / " . $dailyTop['download'] . 'GB',
                'widget_description' => 'Top daily user: ' . $dailyTop['username'],
                'widget_link'        => '#',
                'widget_icon'        => 'fa-area-chart',
                'widget_class'       => 'bg-green'
            ]
        )

        @include(
            'widgets.3colpanel',
            [
                'widget_value'       => $monthlyTop['upload'] . "GB / " . $monthlyTop['download'] . 'GB',
                'widget_description' => 'Top monthly user: ' . $monthlyTop['username'],
                'widget_link'        => '#',
                'widget_icon'        => 'fa-area-chart',
                'widget_class'       => 'bg-olive'
            ]
        )
    </div>

    @include(
        'widgets.bandwidth-chart',
        [
            'id'        => 'dashboardBandwidthMonthlySummary',
            'title'     => date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'month',
            'timeValue' => 2016,
            'username'  => "",
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
            'username'  => "",
            'nasIP'     => "",
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.connection-chart',
        [
            'id'        => 'dashboardConnectionMonthlySummary',
            'title'     => date('M') . ' ' . date('Y') . ' Connection Summary',
            'timeSpan'  => 'month',
            'timeValue' => '',
            'username'  => "",
            'nasIP'     => "",
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.connection-chart',
        [
            'id'        => 'dashboardConnectionDailySummary',
            'title'     => date('M') . ' ' . date('Y') . ' Connection Summary',
            'timeSpan'  => 'day',
            'timeValue' => date('m'),
            'username'  => "",
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
