@foreach($dataSet as $data)
    <tr>
        <td>{{ $data->radacctid }}</td>
        <td><a href="{{ route('accounting::index', ['username' => $data->username]) }}">{{ $data->username }}</a></td>
        <td><a href="{{ route('accounting::index', ['framedipaddress' => $data->framedipaddress]) }}">{{ $data->framedipaddress }}</a></td>
        <td>{{ $data->acctstarttime }}</td>
        <td>{{ $data->acctstoptime }}</td>
        <td>{{ DataHelper::secondsToHumanReadableTime($data->acctsessiontime) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctinputoctets) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->acctoutputoctets) }}</td>
        <td>{{ $data->acctterminatecause }}</td>
        <td><a href="{{ route('accounting::index', ['nasipaddress' => $data->nasipaddress]) }}">{{ $data->nasipaddress }}</a></td>
    </tr>
@endforeach
