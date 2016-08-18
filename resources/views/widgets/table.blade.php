<div class="col-xs-{{ $colWidth }}">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ $title }}</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        @foreach($headers as $header)
                            <td>{{ $header }}</td>
                        @endforeach
                    </tr>

                    @if(isset($partial))
                        @include($partial, ['dataSet' => $dataSet])
                    @else
                        @foreach($dataSet as $data)
                            <tr>
                                @foreach($data as $column)
                                    <td>{{ $column }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
