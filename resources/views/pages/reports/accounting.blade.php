@extends("master")

@section('pageTitle', 'Accounting Ledger')
@section('pageDescription', '')
@push('breadcrumbs')
<li>Reports</li>
<li><a href="{{ route('report::accounting') }}"><i class="fa fa-table"></i> Accounting</a></li>
@endpush

@section("content")
    <div class="box">
        <div class="box-header">
            <h4>Filters</h4>
        </div>
        <div class="box-body">
            {!! BootForm::open()->action(route('report::accounting'))->get()->addClass('form-inline') !!}
            {!! BootForm::text('Username', 'username')->value($filter['username']) !!}
            {!! BootForm::text('Client IP', 'framedipaddress')->value($filter['framedipaddress']) !!}
            {!! BootForm::text('NAS IP', 'nasipaddress')->value($filter['nasipaddress']) !!}
            {!! BootForm::text('Date Start', 'acctstarttime')->value($filter['acctstarttime']) !!}
            {!! BootForm::text('Date Stop', 'acctstoptime')->value($filter['acctstoptime']) !!}
            {!! BootForm::submit('Search')->addClass('btn-success') !!}
            {!! BootForm::close() !!}<br />
        </div>
    </div>
    @include(
        'partials.table.crud-list',
        [
            'title'             => '',
            'disableFilter'     => true,
            'headers'           => ['ID', 'Username', 'IP Address', 'Start Time', 'Stop Time', 'Session Time', 'IN', 'OUT', 'Termination', 'NAS IP Address'],
            'dataSet'           => $accountingList,
            'dataPartial'       => 'pages.reports.partials.accounting-table-data',
        ]
    )
@endsection
