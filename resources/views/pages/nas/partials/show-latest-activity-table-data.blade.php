@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->username }}</td>
        <td>{{ $data->nasportid }}</td>
        <td>{{ $data->nasporttype }}</td>
        <td>{{ $data->acctstarttime }}</td>
        <td>{{ $data->acctstoptime }}</td>
        <td>{{ DataHelper::secondsToHumanReadableTime($data->acctsessiontime) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctinputoctets) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctoutputoctets) }}</td>
    </tr>
@endforeach