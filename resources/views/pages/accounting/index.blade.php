@extends("master")

@section('pageTitle', 'Accounting Ledger')
@section('pageDescription', '')
@push('breadcrumbs')
    <li>Reports</li>
    <li><a href="{{ route('accounting::index') }}"><i class="fa fa-table"></i> Accounting</a></li>
@endpush

@section("content")
    <div class="row">
        <div class="col-md-12">
            {!! BootForm::open()->action(route('accounting::index'))->get()->addClass('form-inline') !!}
                {!! BootForm::text('Username', 'username')->value($filter['username']) !!}
                {!! BootForm::text('Client IP', 'framedipaddress')->value($filter['framedipaddress']) !!}
                {!! BootForm::text('NAS IP', 'nasipaddress')->value($filter['nasipaddress']) !!}
                {!! BootForm::text('Date Start', 'acctstarttime')->value($filter['acctstarttime']) !!}
                {!! BootForm::text('Date Stop', 'acctstoptime')->value($filter['acctstoptime']) !!}
                {!! BootForm::submit('Search') !!}
            {!! BootForm::close() !!}<br />
        </div>
    </div>
    @include(
        'partials.table.crud-list',
        [
            'title'             => '',
            'filterPlaceHolder' => '',
            'headers'           => ['ID', 'Username', 'IP Address', 'Start Time', 'Stop Time', 'Session Time', 'IN', 'OUT', 'Termination', 'NAS IP Address'],
            'dataSet'           => $accountingList,
            'dataPartial'       => 'pages.accounting.partials.index-table-data',
        ]
    )
@endsection
