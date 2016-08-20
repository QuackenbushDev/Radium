@extends("master")

@section('pageTitle', $operator->name)
@push('breadcrumbs')
    <li><a href="{{ route('operator::index') }}"><i class="fa fa-user"></i> Operator</a></li>
    <li>Edit</li>
    <li><a href="{{ route('operator::show', $operator->id) }}">{{ $operator->name }}</a></li>
@endpush

@section('content')
    @if(isset($new) && $new)
        {!! BootForm::open()->action(route('operator::save')) !!}
    @else
        {!! BootForm::open()->action(route('operator::update', ['id' => $operator->id])) !!}
        <input type="hidden" name="_method" value="PUT">
    @endif

    <div class="row">
        <div class="col-md-12">
            <h3>Operator Information</h3>
            {!! Bootform::text('Name', 'name')->value($operator->name) !!}
            {!! Bootform::text('E-Mail', 'email')->value($operator->email) !!}

            @if(isset($new) && $new)
                {!! Bootform::password('Password', 'password') !!}
            @else
                {!! Bootform::password('Change Password', 'password') !!}
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            {!! BootForm::submit('Save Operator')->addClass('btn-success btn-lg pull-right') !!}
        </div>
    </div>
    {!! BootForm::close() !!}
@endsection
