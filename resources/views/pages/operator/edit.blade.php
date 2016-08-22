@extends("master")

@section('pageTitle', 'Edit Operator')
@push('breadcrumbs')
    <li><a href="{{ route('operator::index') }}"><i class="fa fa-user"></i> Operator</a></li>
    <li>Edit</li>
    <li><a href="{{ route('operator::show', $operator->id) }}">{{ $operator->name }}</a></li>
@endpush

@section('content')
    <div class="box">
        <div class="box-body">
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

                    {!! BootForm::text('Company', 'company')->value($operator->company) !!}
                    {!! BootForm::text('Department', 'department')->value($operator->department) !!}
                    {!! BootForm::text('Home Phone', 'home_phone')->value($operator->home_phone) !!}
                    {!! BootForm::text('Work Phone', 'work_phone')->value($operator->work_phone) !!}
                    {!! BootForm::text('Mobile Phone', 'mobile_phone')->value($operator->mobile_phone) !!}
                    {!! BootForm::textArea('Address', 'address')->rows(2)->value($operator->address) !!}

                    @if ($operator->enable_daily_summary)
                        {!! BootForm::checkbox('Enable daily summary', 'enable_daily_summary')->checked() !!}
                    @else
                        {!! BootForm::checkbox('Enable daily summary', 'enable_daily_summary') !!}
                    @endif

                    @if ($operator->enable_monthly_summary)
                        {!! BootForm::checkbox('Enable monthly summary', 'enable_monthly_summary')->checked() !!}
                    @else
                        {!! BootForm::checkbox('Enable monthly summary', 'enable_monthly_summary') !!}
                    @endif

                    {!! BootForm::textArea('Notes', 'notes')->value($operator->notes) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    {!! BootForm::submit('Save Operator')->addClass('btn-success btn-lg pull-right') !!}
                </div>
            </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
