@extends('master')

@section('pageTitle', $user->username)
@section('pageDescription', 'Details')

@push('breadcrumbs')
    <li><a href="{{ route('user::index') }}"><i class="fa fa-group"></i> Users</a></li>
    <li><a href="{{ route('user::show', ['id' => $user->id])  }}">{{ $user->username }}</a></li>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3>User Information</h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Username</td>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td>{{ $user->value }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>ENABLED</td>
                    </tr>
                    <tr>
                        <td>Online Status</td>
                        <td>
                            @if($onlineStatus)
                                ONLINE
                            @else
                                OFFLINE
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Groups</td>
                        <td>{{ implode(', ', $groups) }}</td>
                    </tr>
                    <tr></tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h3>Contact Information</h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>{{ $user->company }}</td>
                    </tr>
                    <tr>
                        <td>Home Phone</td>
                        <td>{{ $user->home_phone }}</td>
                    </tr>
                    <tr>
                        <td>Mobile Phone</td>
                        <td>{{ $user->mobile_phone }}</td>
                    </tr>
                    <tr>
                        <td>Office Phone</td>
                        <td>{{ $user->office_phone }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>{{ $user->address }}</td>
                    </tr>
                    <tr>
                        <td>Portal Access</td>
                        <td>
                            @if ($user->enable_portal)
                                ENABLED
                            @else
                                DISABLED
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Allow password resets</td>
                        <td>
                            @if ($user->enable_password_resets)
                                ENABLED
                            @else
                                DISABLED
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

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
                'pages.user.partials.bandwidthSummary',
                [
                    'title' => 'Daily Summary (' . date('M-d') . ')',
                    'data' => $bandwidthStats['day']
                ]
            )
        </div>

        <div class="col-md-4">
            @include(
                'pages.user.partials.bandwidthSummary',
                [
                    'title' => 'Monthly (' . date('M Y') . ')',
                    'data' => $bandwidthStats['month']
                ]
            )
        </div>

        <div class="col-md-4">
            @include(
                'pages.user.partials.bandwidthSummary',
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

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="/plugins/chartjs/Chart.min.js"></script>
    <script type="text/javascript" src="/js/pages/dashboard.js"></script>
@endsection
