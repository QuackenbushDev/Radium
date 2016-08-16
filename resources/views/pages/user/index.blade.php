@extends("master")

@section('pageTitle', 'User List')
@section('pageDescription', '')
@push('breadcrumbs')
    <li><a href="{{ route('user::index') }}"><i class="fa fa-group"></i> Users</a></li>
@endpush

@section("content")
    @include(
        'partials.table.crud-list',
        [
            'title'             => 'User List',
            'filterPlaceHolder' => 'Username',
            'headers'           => ['ID', 'Username', 'Password', 'Status', 'Primary Group'],
            'dataSet'           => $users,
            'dataPartial'       => 'pages.user.partials.index-table-data',
        ]
    )
@endsection
