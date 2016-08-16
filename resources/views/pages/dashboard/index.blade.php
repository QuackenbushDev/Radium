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
                'widget_value'       => $dailyStats['connections'],
                'widget_description' => 'Total connections on ' . date("m-d-Y"),
                'widget_link'        => '#',
                'widget_icon'        => 'fa-area-chart',
                'widget_class'       => 'bg-aqua'
            ]
        )

        @include(
            'widgets.3colpanel',
            [
                'widget_value'       => $monthlyStats['connections'],
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

    {{-- TODO: REPLACE THIS WITH AN API CALL! --}}
    <script type="text/javascript">
        var usage = {!! json_encode($monthlyBandwidthUsage) !!};
    </script>
    @include('pages.dashboard.graph')

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

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="/plugins/chartjs/Chart.min.js"></script>
    <script type="text/javascript" src="/js/pages/dashboard.js"></script>
@endsection
