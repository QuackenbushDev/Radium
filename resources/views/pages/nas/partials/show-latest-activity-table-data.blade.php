@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->username }}</td>
        <td>{{ $data->date }}</td>
        <td>{{ App\Utils\DataHelper::convertToHumanReadableSize($data->download) }}</td>
        <td>{{ App\Utils\DataHelper::convertToHumanReadableSize($data->upload) }}</td>
        <td>{{ App\Utils\DataHelper::convertToHumanReadableSize($data->total) }}</td>
    </tr>
@endforeach