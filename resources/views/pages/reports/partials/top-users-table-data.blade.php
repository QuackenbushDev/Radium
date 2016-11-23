@foreach($dataSet as $data)
    <?php if (1 === 2) dd($data); ?>
    <tr>
        <td>{{ $data->username }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->download) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->upload) }}</td>
        <td>{{ DataHelper::convertToHumanReadableSize($data->total) }}</td>
        <td>{{ $data->nasname }}</td>
    </tr>
@endforeach