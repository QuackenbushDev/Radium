@extends("master")

@section('pageTitle', $nas->shortname)
@section('pageDescription', $nas->nasname)

@push('breadcrumbs')
    <li><a href="{{ route('nas::index') }}"><i class="fa fa-server"></i> Nas</a></li>
    <li>Edit</li>
    <li><a href="{{ route('nas::show', $nas->id) }}">$nas->shortname</a></li>
@endpush

@section('content')
    <div class="box">
        <div class="box-body">
            @if(isset($new) && $new)
                {!! BootForm::open()->action(route('nas::save')) !!}
            @else
                {!! BootForm::open()->action(route('nas::update', ['id' => $nas->id])) !!}
                <input type="hidden" name="_method" value="PUT">
            @endif

            <div class="row">
                <div class="col-md-12">
                    <h3>Nas Information</h3>
                    {!! BootForm::text('IP', 'nas_name')->value($nas->nasname) !!}
                    {!! BootForm::text('Short Name', 'short_name')->value($nas->shortname) !!}
                    {!! BootForm::select('Type', 'type')->options($types)->select($nas->type) !!}
                    {!! BootForm::text('Ports', 'ports')->value($nas->ports) !!}
                    {!! BootForm::text('Secret', 'secret')->value($nas->secret) !!}
                    {!! BootForm::text('Server', 'server')->value($nas->server) !!}
                    {!! BootForm::text('Community', 'community')->value($nas->community) !!}
                    {!! BootForm::text('Description', 'description')->value($nas->description) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    {!! BootForm::submit('Save NAS Changes')->addClass('btn-success btn-lg pull-right') !!}
                </div>
            </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
