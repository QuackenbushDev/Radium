@extends("master")

@section('pageTitle', 'Group List')
@section('pageDescription', '')
@push('breadcrumbs')
    <li><a href="{{ route('nas::index') }}"><i class="fa fa-groups"></i> Group</a></li>
@endpush

@section("content")
    @include(
        'partials.table.crud-list',
        [
            'title'             => '',
            'createLink'        => route('group::create'),
            'createLinkName'    => 'New Group',
            'filterPlaceHolder' => 'Group Name',
            'filterAction'      => route('group::index'),
            'filterValue'       => $filter,
            'headers'           => ['Group Name', 'User Count'],
            'dataSet'           => $groupList,
            'dataPartial'       => 'pages.group.partials.index-table-data',
        ]
    )
@endsection
