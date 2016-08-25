@extends("master")

@section('pageTitle', 'Top Users')
@section('pageDescription', '')

@push('breadcrumbs')
    <li>Reports</li>
    <li><a href="{{ route('report::topUsers') }}"><i class="fa fa-group"></i> Top Users</a></li>
@endpush

@section("content")
    <div class="box">
        <div class="box-header">
            <h4>Filters</h4>
        </div>
        <div class="box-body filter">
            {!! BootForm::open()->action(route('report::topUsers'))->get()->addClass('form-inline') !!}
            {!! BootForm::text('NAS IP', 'nasipaddress')->value($filter['nasipaddress']) !!}
            {!! BootForm::text('Date Start', 'acctstarttime')->value($filter['acctstarttime']) !!}
            {!! BootForm::text('Date Stop', 'acctstoptime')->value($filter['acctstoptime']) !!}
            {!! BootForm::submit('Search')->addClass('btn-success') !!}
            {!! BootForm::close() !!}
        </div>
    </div>

    @include(
        'partials.table.crud-list',
        [
            'title'             => '',
            'disableFilter'     => true,
            'headers'           => ['Username', 'Start Time', 'Stop Time', 'Session Time', 'Connections', 'Download', 'Upload', 'Total', 'NAS IP Address'],
            'dataSet'           => $userList,
            'dataPartial'       => 'pages.reports.partials.top-users-table-data',
        ]
    )
@endsection
