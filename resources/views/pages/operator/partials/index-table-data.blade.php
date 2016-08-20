@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->id }}</td>
        <td>{{ $data->email }}</td>
        <td>{{ $data->created_at }}</td>
        <td>{{ $data->updated_at }}</td>
    </tr>
@endforeach