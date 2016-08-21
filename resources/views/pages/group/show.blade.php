@extends("master")

@section('pageTitle', $group->groupname)

@push('breadcrumbs')
    <li><a href="{{ route('group::index') }}"><i class="fa fa-groups"></i> Group</a></li>
    <li><a href="{{ route('group::show', $group->groupname) }}">{{ $group->groupname }}</a></li>
@endpush

@section('content')
    <div class="box">
        <div class="box-header">
            <h3>Group Information <a href="{{ route("group::edit", $group->groupname) }}">(edit)</a></h3>
        </div>
    </div>

    <div class="row">
        @include(
            'widgets.table',
            [
                'title'    => 'Group Check',
                'headers'  => ['ID', 'GroupName', 'Attribute', 'OP', 'Value'],
                'dataSet'  => $check,
                'colWidth' => 6
            ]
        )

        @include(
            'widgets.table',
            [
                'title'    => 'Group Reply',
                'headers'  => ['ID', 'GroupName', 'Attribute', 'OP', 'Value'],
                'dataSet'  => $reply,
                'colWidth' => 6
            ]
        )
    </div>

    @include(
        'partials.table.crud-list',
        [
            'title'             => 'Group User List',
            'createLink'        => route('user::create'),
            'createLinkName'    => '',
            'disableFilter'     => true,
            'filterPlaceHolder' => '',
            'filterAction'      => '',
            'filterValue'       => '',
            'headers'           => ['ID', 'Username', 'Password', 'Status', 'Primary Group'],
            'dataSet'           => $userList,
            'dataPartial'       => 'pages.user.partials.index-table-data',
        ]
    )
@endsection
