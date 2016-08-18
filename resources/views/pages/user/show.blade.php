@extends('master')

@section('pageTitle', $user->username)
@section('pageDescription', 'Details')

@push('breadcrumbs')
    <li><a href="{{ route('user::index') }}"><i class="fa fa-group"></i> Users</a></li>
    <li><a href="{{ route('user::show', ['id' => $user->id])  }}">{{ $user->username }}</a></li>
@endpush

@section('content')
    @include('pages.user.partials.account-info')
    <input id="userID" type="hidden" value="{{ $user->id }}" />

    <div class="row">
        <div class="col-md-12">
            <h3>Attributes</h3>
        </div>
    </div>

    <div class="row">
        @include(
            'widgets.table',
            [
                'title'    => 'User Check',
                'headers'  => ['ID', 'GroupName', 'Attribute', 'OP', 'Value'],
                'dataSet'  => $userCheck,
                'colWidth' => 6
            ]
        )

        @include(
            'widgets.table',
            [
                'title'    => 'User Reply',
                'headers'  => ['ID', 'GroupName', 'Attribute', 'OP', 'Value'],
                'dataSet'  => $userReply,
                'colWidth' => 6
            ]
        )
    </div>

    <div class="row">
        @include(
            'widgets.table',
            [
                'title'    => 'Group Check',
                'headers'  => ['ID', 'GroupName', 'Attribute', 'OP', 'Value'],
                'dataSet'  => $groupCheck,
                'colWidth' => 6
            ]
        )

        @include(
            'widgets.table',
            [
                'title'    => 'Group Reply',
                'headers'  => ['ID', 'GroupName', 'Attribute', 'OP', 'Value'],
                'dataSet'  => $groupReply,
                'colWidth' => 6
            ]
        )
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Bandwidth Usage</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            @include(
                'pages.user.partials.bandwidth-summary',
                [
                    'title' => 'Daily Summary (' . date('M-d') . ')',
                    'data' => $bandwidthStats['day']
                ]
            )
        </div>

        <div class="col-md-4">
            @include(
                'pages.user.partials.bandwidth-summary',
                [
                    'title' => 'Monthly (' . date('M Y') . ')',
                    'data' => $bandwidthStats['month']
                ]
            )
        </div>

        <div class="col-md-4">
            @include(
                'pages.user.partials.bandwidth-summary',
                [
                    'title' => 'Yearly (' . date('Y') . ')',
                    'data' => $bandwidthStats['year']
                ]
            )
        </div>
    </div>

    {{-- TODO: REPLACE THIS WITH AN API CALL! --}}
    <script type="text/javascript">
        var usage = {!! json_encode($bandwidthMonthlyUsage) !!};
    </script>
    @include('pages.dashboard.graph')

    <div class="row">
        <div class="col-md-12">
            <h3>Latest Logins</h3>
        </div>
    </div>

    <div class="row">
        @include(
            'widgets.table',
            [
                'title'    => 'Recent Logins',
                'headers'  => ['ID', 'Username', 'Reply', 'Auth Date'],
                'dataSet'  => $loginAttempts,
                'colWidth' => 12
            ]
        )
    </div>

    <div id="disconnectUserModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Disconnect User</h4>
                </div>
                <div class="modal-body">
                    <iframe id="disconnectiFrame" src="{{ route("user::disconnect", $user->id) }}"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div id="testConnectivityModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Test User Connectivity</h4>
                </div>
                <div class="modal-body">
                    <iframe id="testConnectivityiFrame" src="{{ route("user::test", $user->id) }}"></iframe>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript" src="/plugins/chartjs/Chart.min.js"></script>
        <script type="text/javascript" src="/js/pages/dashboard.js"></script>
        <script type="text/javascript" src="/js/pages/user.js"></script>
    @endpush
@endsection
