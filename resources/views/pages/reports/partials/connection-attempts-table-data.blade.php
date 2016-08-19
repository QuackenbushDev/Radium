@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->id }}</td>
        <td>{{ $data->username }}</td>
        <td>{{ $data->pass }}</td>
        <td>{{ $data->reply }}</td>
        <td>{{ $data->authdate }}</td>
    </tr>
@endforeach
