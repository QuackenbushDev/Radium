@foreach($dataSet as $data)
    <tr>
        <td><a href="{{ route('operator::show', $data->id) }}">{{ $data->id }}</a></td>
        <td><a href="{{ route('operator::show', $data->id) }}">{{ $data->email }}</a></td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
    </tr>
@endforeach