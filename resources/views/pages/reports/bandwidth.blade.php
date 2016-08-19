@extends("master")

@section('pageTitle', 'Bandwidth Report')
@section('pageDescription', '')
@push('breadcrumbs')
    <li>Reports</li>
    <li><a href="{{ route('report::bandwidth') }}"><i class="fa fa-area-chart"></i> Bandwidth</a></li>
@endpush

@section("content")
    <div class="box">
        <div class="box-header">
            <h4>Filters</h4>
        </div>
        <div class="box-body">
            {!! BootForm::open()->action(route('report::bandwidth'))->get()->addClass('form-inline') !!}
            {!! BootForm::text('Username', 'username')->value($filter['username']) !!}
            {!! BootForm::text('NAS IP', 'nasipaddress')->value($filter['nasipaddress']) !!}
            {!! BootForm::submit('Search')->addClass('btn-success') !!}
            {!! BootForm::close() !!}<br />
        </div>
        <div class="box-footer">
            <p>Showing report for: username: {{ $filter['username'] }}, nas: {{ $filter['nasipaddress'] }}</p>
        </div>
    </div>

    @include(
        'widgets.bandwidth-chat',
        [
            'id'        => 'reportBandwidthMonthlySummary',
            'title'     => date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'month',
            'timeValue' => date('Y'),
            'username'  => $filter['username'],
            'nasIP'     => $filter['nasipaddress'],
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.bandwidth-chat',
        [
            'id'        => 'reportBandwidthDailySummary',
            'title'     => date('M') . ' ' . date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'day',
            'timeValue' => date('m'),
            'username'  => $filter['username'],
            'nasIP'     => $filter['nasipaddress'],
            'height'    => '300px',
        ]
    )
@endsection
