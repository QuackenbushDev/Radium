@foreach($dataSet as $data)
    <tr>
        <td><a href="{{ route('group::show', $data->groupname) }}">{{ $data->groupname }}</a></td>
        <td><a href="{{ route('group::show', $data->groupname) }}">{{ $data->count }}</a></td>
    </tr>
@endforeach
