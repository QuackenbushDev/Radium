@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->username }}</td>
        <td>{{ $data->acctstarttime }}</td>
        <td>{{ $data->acctstoptime }}</td>
        <td>{{ DataHelper::secondsToHumanReadableTime($data->acctsessiontime) }}</td>
        <td>{{ $data->connections }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->download) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->upload) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->total) }}</td>
        <td>{{ $data->nasipaddress }}</td>
    </tr>
@endforeach