@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->radacctid }}</td>
        <td>{{ $data->username }}</td>
        <td>{{ $data->framedipaddress }}</td>
        <td>{{ $data->nasipaddress }}</td>
        <td>{{ $data->connections }}</td>
        <td>{{ DataHelper::secondsToHumanReadableTime($data->acctsessiontime) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctoutputoctets) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctinputoctets) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->total) }}</td>
    </tr>
@endforeach
