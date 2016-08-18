<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title pull-left">{{ $title }}</h3>
                @if (isset($createLink))
                    <a href="{{ $createLink }}">{{ $createLinkName }}</a>
                @endif

                @if ($filterPlaceHolder !== '')
                    <div class="pull-right">
                        <label><input type="search" class="form-control input-sm" placeholder="{{ $filterPlaceHolder }}"></label>
                    </div>
                @endif
            </div>
            <div class="box-body">
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover dataTable" role="grid">
                                <thead>
                                    <tr role="row">
                                        @foreach($headers as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @include($dataPartial, ['dataSet' => $dataSet])
                                </tbody>
                                <tfoot>
                                    <tr>
                                        @foreach($headers as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    @include('partials.pagination-nav', ['data' => $dataSet])
                </div>
            </div>
        </div>
    </div>
</div>