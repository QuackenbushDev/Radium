<body>
    <h1>{{ $title }}</h1>
    <img src="{{ $graph }}">

    <h3>Connections</h3>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Reply</th>
                <th>Auth Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($connections as $connection)
                <tr>
                    <td>{{ $connection->username }}<td>
                    <td>{{ $connection->reply }}</td>
                    <td>{{ $connection->authdate }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
