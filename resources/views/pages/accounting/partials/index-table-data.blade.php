@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->radacctid }}</td>
        <td>{{ $data->username }}</td>
        <td>{{ $data->framedipaddress }}</td>
        <td>{{ $data->acctstarttime }}</td>
        <td>{{ $data->acctstoptime }}</td>
        <td>{{ DataHelper::secondsToHumanReadableTime($data->acctsessiontime) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctinputoctets) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctoutputoctets) }}</td>
        <td>{{ $data->acctterminatecause }}</td>
        <td>{{ $data->nasipaddress }}</td>
    </tr>
@endforeach
