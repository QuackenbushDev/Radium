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
                {!! BootForm::text('Client IP', 'clientip')->value($filter['clientIP']) !!}
                {!! BootForm::text('NAS IP', 'nasip')->value($filter['nasIP']) !!}
                {!! BootForm::text('Date Start', 'datestart')->value($filter['timeStart']) !!}
                {!! BootForm::text('Date Stop', 'datestop')->value($filter['timeStop']) !!}
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
