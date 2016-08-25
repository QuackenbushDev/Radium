@extends("master")

@section('pageTitle', 'Connection Attempts')
@section('pageDescription', '')
@push('breadcrumbs')
    <li>Reports</li>
    <li><a href="{{ route('report::onlineUsers') }}"><i class="fa fa-lock"></i> Connection Attempts</a></li>
@endpush

@section("content")
    <div class="box">
        <div class="box-header">
            <h4>Filters</h4>
        </div>
        <div class="box-body filter">
            {!! BootForm::open()->action(route('report::connectionAttempts'))->get()->addClass('form-inline') !!}
            {!! BootForm::text('Username', 'username')->value($filter['username']) !!}
            {!! BootForm::select('Reply', 'reply', ['', 'Access-Accept', 'Access-Reject'])->select($filter['reply']) !!}
            {!! BootForm::text('Date Start', 'datestart')->value($filter['datestart']) !!}
            {!! BootForm::text('Date Stop', 'datestop')->value($filter['datestop']) !!}
            {!! BootForm::submit('Search')->addClass('btn-success') !!}
            {!! BootForm::close() !!}
        </div>
    </div>

    @include(
        'partials.table.crud-list',
        [
            'title'             => '',
            'disableFilter'     => true,
            'headers'           => ['ID', 'Username', 'Password', 'Reply', 'Date'],
            'dataSet'           => $authList,
            'dataPartial'       => 'pages.reports.partials.connection-attempts-table-data',
        ]
    )
@endsection