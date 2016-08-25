<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title pull-left">{{ $title }}</h3>
                <div class="row-fluid">
                    <div class="col-sm-6">
                        @if (isset($createLink))
                            <a class="btn btn-sm btn-default text-black" href="{{ $createLink }}">{{ $createLinkName }}</a>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        @if(!isset($disableFilter))
                            {!! BootForm::open()->action($filterAction)->get() !!}
                            <div class="pull-right">
                                <label><input name="filter" type="search" class="form-control input-sm" placeholder="{{ $filterPlaceHolder }}" value="{{ $filterValue }}"></label>
                            </div>
                            {!! BootForm::close() !!}
                        @endif
                    </div>
                </div>
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