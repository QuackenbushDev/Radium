@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->radacctid }}</td>
        <td><a href="{{ route('report::accounting', ['username' => $data->username]) }}">{{ $data->username }}</a></td>
        <td><a href="{{ route('report::accounting', ['framedipaddress' => $data->framedipaddress]) }}">{{ $data->framedipaddress }}</a></td>
        <td>{{ $data->acctstarttime }}</td>
        <td>{{ $data->acctstoptime }}</td>
        <td>{{ DataHelper::secondsToHumanReadableTime($data->acctsessiontime) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctinputoctets) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctoutputoctets) }}</td>
        <td>{{ $data->acctterminatecause }}</td>
        <td><a href="{{ route('report::accounting', ['nasipaddress' => $data->nasipaddress]) }}">{{ $data->nasipaddress }}</a></td>
    </tr>
@endforeach
