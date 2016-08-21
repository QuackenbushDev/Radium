@extends("master")

@section('pageTitle', $nas->shortname)
@section('pageDescription', $nas->nasname)

@push('breadcrumbs')
    <li><a href="{{ route('nas::index') }}"><i class="fa fa-server"></i> Nas</a></li>
    <li><a href="{{ route('nas::show', $nas->id) }}">{{ $nas->shortname }}</a></li>
@endpush

@section('content')
    <div class="box">
        <div class="box-header">
            <h3>NAS Information <a href="{{ route("nas::edit", ['id' => $nas->id]) }}">(edit)</a></h3>
        </div>
        <div class="box-body">
            <table class="table table-responsive table-bordered">
                <tbody>
                    <tr>
                        <td>ID</td>
                        <td>{{ $nas->id }}</td>
                    </tr>
                    <tr>
                        <td>Nas Name (IP)</td>
                        <td>{{ $nas->nasname }}</td>
                    </tr>
                    <tr>
                        <td>Short Name</td>
                        <td>{{ $nas->shortname }}</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>{{ $nas->type }}</td>
                    </tr>
                    <tr>
                        <td>Ports</td>
                        <td>{{ $nas->ports }}</td>
                    </tr>
                    <tr>
                        <td>Secret</td>
                        <td>{{ $nas->secret }}</td>
                    </tr>
                    <tr>
                        <td>Server</td>
                        <td>{{ $nas->secret }}</td>
                    </tr>
                    <tr>
                        <td>Community</td>
                        <td>{{ $nas->community }}</td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td>{{ $nas->description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @include(
        'widgets.bandwidth-chat',
        [
            'id'        => 'dashboardBandwidthMonthlySummary',
            'title'     => date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'month',
            'timeValue' => 2016,
            'username'  => "",
            'nasIP'     => $nas->nasname,
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.bandwidth-chat',
        [
            'id'        => 'dashboardBandwidthDailySummary',
            'title'     => date('M') . ' ' . date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'day',
            'timeValue' => date('m'),
            'username'  => "",
            'nasIP'     => $nas->nasname,
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.connection-chart',
        [
            'id'        => 'nasConnectionSummary',
            'title'     => date('Y') . ' Connection Summary',
            'timeSpan'  => 'month',
            'timeValue' => '',
            'username'  => "",
            'nasIP'     => $nas->nasname,
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.connection-chart',
        [
            'id'        => 'nasDailyConnectionSummary',
            'title'     => date('M') . ' ' . date('Y') . ' Connection Summary',
            'timeSpan'  => 'day',
            'timeValue' => date('m'),
            'username'  => "",
            'nasIP'     => $nas->nasname,
            'height'    => '300px',
        ]
    )

    <div class="row">
        @include(
            'widgets.table',
            [
                'title'    => 'Recent Activity',
                'headers'  => ['Username', 'Port', 'Type', 'Start Time', 'Stop Time', 'Session Time', 'Download', 'Upload'],
                'dataSet'  => $latestActivity,
                'partial'  => 'pages.nas.partials.show-latest-activity-table-data',
                'colWidth' => 12
            ]
        )
    </div>
@endsection
