<div>
    <h1>Monthly Summary for {{ $month }}</h1>
</div>

<h3>Users included in summary:</h3>
<ul>
    @foreach($userList as $user)
        <li>
            {{ $user->username }} ({{ $in[$user->username] }} / {{ $out[$user->username] }} | {{ $connections[$user->username] }})
        </li>
    @endforeach
</ul>

@foreach($userList as $user)
    <div>
        <h4>{{ $user->username }}</h4>
        <p>In: {{ $in[$user->username] }}</p>
        <p>Out: {{ $out[$user->username] }}</p>
        <p>Connections: {{ $connections[$user->username] }}</p>
        <p>Authentications:</p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reply</th>
                    <th>Auth Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logins[$user->username] as $login)
                    <tr>
                        <td>{{ $login->id }}</td>
                        <td>{{ $login->reply }}</td>
                        <td>{{ $login->authdate }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Accounting:</p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reply</th>
                    <th>Auth Date</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endforeach