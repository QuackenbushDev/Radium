@extends("master")

@section('pageTitle', $nas->shortname)
@section('pageDescription')
    <a href="{{ route('nas::edit', $nas->id) }}">(Edit)</a>
@endsection

@push('breadcrumbs')
    <li><a href="{{ route('nas::index') }}"><i class="fa fa-server"></i> Nas</a></li>
    <li><a href="{{ route('nas::show', $nas->id) }}">$nas->shortname</a></li>
@endpush

@section('content')
    <div class="box">
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

    {{-- TODO: REPLACE THIS WITH AN API CALL! --}}
    <script type="text/javascript">
        var usage = {!! json_encode($bandwidthUsage) !!};
    </script>
    @include('pages.dashboard.graph')

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

@push('scripts')
    <script type="text/javascript" src="/plugins/chartjs/Chart.min.js"></script>
    <script type="text/javascript" src="/js/pages/dashboard.js"></script>
@endpush
