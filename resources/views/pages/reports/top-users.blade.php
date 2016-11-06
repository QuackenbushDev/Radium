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
            {!! BootForm::text('NAS IP', 'nasName')->value($filter['nasName']) !!}
            {!! BootForm::text('Date Start', 'start')->value($filter['start']) !!}
            {!! BootForm::text('Date Stop', 'stop')->value($filter['stop']) !!}
            {!! BootForm::submit('Search')->addClass('btn-success') !!}
            {!! BootForm::close() !!}
        </div>
    </div>

    @include(
        'partials.table.crud-list',
        [
            'title'             => '',
            'disableFilter'     => true,
            'disablePagination' => true,
            'headers'           => ['Username', 'Date', 'Download', 'Upload', 'Total', 'NAS IP Address'],
            'dataSet'           => $userList,
            'dataPartial'       => 'pages.reports.partials.top-users-table-data',
        ]
    )
@endsection
