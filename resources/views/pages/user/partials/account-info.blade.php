<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                @if(!session()->has('portal_username'))
                    <h3>User Information <a href="{{ route("user::edit", $user->id) }}">(edit)</a></h3>
                @else
                    <h3>User Information <a href="{{ route("portal::editProfile", $user->username) }}">(edit)</a></h3>
                @endif

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
                    </tbody>
                </table>
                @if (!session()->has('portal_username'))
                    <a id="testUserConnectivity" href="#" class="btn btn-lg btn-success">Test Connectivity</a>
                    <a id="disconnectUser" href="#" class="btn btn-lg btn-danger">Disconnect User</a>
                    @if(in_array($disabledGroupName, $groups))
                        <a href="{{ route('user::enable', $user->id) }}" class="btn btn-lg btn-success">Enable User</a>
                    @else
                        <a href="{{ route("user::disable", $user->id) }}" class="btn btn-lg btn-danger">Disable User</a>
                    @endif
                @endif
            </div>

            <div class="col-md-6">
                <h3>Contact Information</h3>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{ $userInfo->name }}</td>
                        </tr>
                        <tr>
                            <td>E-Mail</td>
                            <td>{{ $userInfo->email }}</td>
                        </tr>
                        <tr>
                            <td>Company</td>
                            <td>{{ $userInfo->company }}</td>
                        </tr>
                        <tr>
                            <td>Home Phone</td>
                            <td>{{ $userInfo->home_phone }}</td>
                        </tr>
                        <tr>
                            <td>Mobile Phone</td>
                            <td>{{ $userInfo->mobile_phone }}</td>
                        </tr>
                        <tr>
                            <td>Office Phone</td>
                            <td>{{ $userInfo->office_phone }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>{{ $userInfo->address }}</td>
                        </tr>
                        @if(!session()->has('portal_username'))
                            <tr>
                                <td>Portal Access</td>
                                <td>
                                    @if ($userInfo->enable_portal)
                                        ENABLED
                                    @else
                                        DISABLED
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Allow password resets</td>
                                <td>
                                    @if ($userInfo->enable_password_resets)
                                        ENABLED
                                    @else
                                        DISABLED
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Receive weekly usage summary</td>
                            <td>
                                @if($userInfo->enable_weekly_summary)
                                    ENABLED
                                @else
                                    DISABLED
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Receive monthly usage summary</td>
                            <td>
                                @if($userInfo->enable_monthly_summary)
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
    </div>
</div>
