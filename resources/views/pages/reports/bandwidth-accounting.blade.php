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
        <div class="box-body filter">
            {!! BootForm::open()->action(route('report::bandwidthAccounting'))->get()->addClass('form-inline') !!}
            {!! BootForm::text('Username', 'username')->value($filter['username']) !!}
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
            'headers'           => ['Username', 'Date', 'Download', 'Upload', 'Total', 'Nas Name'],
            'dataSet'           => $bandwidthAccountingList,
            'dataPartial'       => 'pages.reports.partials.bandwidth-accounting-table-data',
            'disablePagination' => true,
        ]
    )
@endsection
