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
        <div class="box-body filter">
            {!! BootForm::open()->action(route('report::bandwidth'))->get()->addClass('form-inline') !!}
            {!! BootForm::text('Username', 'username')->value($filter['username']) !!}
            {!! BootForm::select('Nas', 'nasID')->options($nasList)->select($filter['nasID']) !!}
            {!! BootForm::submit('Search')->addClass('btn-success') !!}
            {!! BootForm::close() !!}
        </div>
    </div>

    @include(
        'widgets.bandwidth-chart',
        [
            'id'        => 'reportBandwidthMonthlySummary',
            'title'     => date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'month',
            'timeValue' => date('Y'),
            'username'  => $filter['username'],
            'nasID'     => $filter['nasID'],
            'height'    => '300px',
        ]
    )

    @include(
        'widgets.bandwidth-chart',
        [
            'id'        => 'reportBandwidthDailySummary',
            'title'     => date('M') . ' ' . date('Y') . ' Bandwidth Summary',
            'timeSpan'  => 'day',
            'timeValue' => date('m'),
            'username'  => $filter['username'],
            'nasID'     => $filter['nasID'],
            'height'    => '300px',
        ]
    )
@endsection
