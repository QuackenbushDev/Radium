@foreach($dataSet as $data)
    <tr>
        <td><a href="{{ route('nas::show', ['id' => $data->id]) }}">{{ $data->id }}</a></td>
        <td><a href="{{ route('nas::show', ['id' => $data->id]) }}">{{ $data->nasname }}</a></td>
        <td>{{ $data->shortname }}</td>
        <td>{{ $data->type }}</td>
        <td>{{ $data->ports }}</td>
        <td>{{ $data->server }}</td>
        <td>{{ $data->community }}</td>
        <td>{{ $data->description }}</td>
    </tr>
@endforeach
