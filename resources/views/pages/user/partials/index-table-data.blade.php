@foreach($dataSet as $data)
    <tr>
        <td><a href="{{ route('user::show', ['id' => $data->id]) }}">{{ $data->id }}</a></td>
        <td><a href="{{ route('user::show', ['id' => $data->id]) }}">{{ $data->username }}</a></td>
        <td>{{ $data->password }}</td>
        <td>
            @if($data->disabled === 1)
                <span style="color: #FF0000; font-weight: bold;">DISABLED</span>
            @else
                <span style="color: #13a51a; font-weight: bold;">ENABLED</span>
            @endif
        </td>
        <td>{{ $data->groupname }}</td>
    </tr>
@endforeach
