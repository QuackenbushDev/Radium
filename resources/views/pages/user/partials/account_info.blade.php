<div class="row">
    <div class="col-md-6">
        <h3>User Information <a href="{{ route("user::edit", ['id' => $user->id]) }}">(edit)</a></h3>
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
            </tbody>
        </table>
    </div>
</div>
