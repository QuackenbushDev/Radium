<body>
    <h1>{{ $title }}</h1>
    <p>{{ $operator->name }},</p>
    <p>Bandwidth statistics</p>
    <img src="{{ $bandwidthGraph }}">

    <p>Connection statistics</p>
    <img src="{{ $connectionGraph }}">
</body>
