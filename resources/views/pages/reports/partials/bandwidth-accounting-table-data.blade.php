@foreach($dataSet as $data)
    <tr>
        <td><a href="{{ route('report::bandwidthAccounting', ['username' => $data->username]) }}">{{ $data->username }}</a></td>
        <td>{{ $data->date }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->download) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->upload) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->total) }}</td>
        <td><a href="{{ route('report::bandwidthAccounting', ['nasID' => $data->nas_id]) }}">{{ $data->nasname }}</a></td>
    </tr>
@endforeach
