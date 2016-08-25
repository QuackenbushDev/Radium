@extends("master")

@section('pageTitle', 'Online User Report')
@section('pageDescription', '')
@push('breadcrumbs')
    <li>Reports</li>
    <li><a href="{{ route('report::onlineUsers') }}"><i class="fa fa-user"></i> Online Users</a></li>
@endpush

@section("content")
    @include(
        'partials.table.crud-list',
        [
            'title'             => '',
            'disableFilter'     => true,
            'headers'           => ['ID', 'Username', 'IP Address', 'NAS IP Address', 'Connections', 'Session Time', 'Download', 'Upload', 'Total'],
            'dataSet'           => $onlineUserList,
            'dataPartial'       => 'pages.reports.partials.online-user-table-data',
        ]
    )
@endsection