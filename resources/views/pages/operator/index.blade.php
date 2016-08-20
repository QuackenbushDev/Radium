@extends("master")

@section('pageTitle', 'Operator List')
@section('pageDescription', '')
@push('breadcrumbs')
    <li><a href="{{ route('user::index') }}"><i class="fa fa-group"></i> Operators</a></li>
@endpush

@section("content")
    @include(
        'partials.table.crud-list',
        [
            'title'             => '',
            'createLink'        => route('operator::create'),
            'createLinkName'    => 'New Operator',
            'filterPlaceHolder' => 'Name',
            'filterAction'      => route('operator::index'),
            'filterValue'       => $filterValue,
            'headers'           => ['ID', 'Name', 'E-Mail', 'Created At', 'Updated At'],
            'dataSet'           => $operatorList,
            'dataPartial'       => 'pages.operator.partials.index-table-data',
        ]
    )
@endsection
