@extends("master")

@section('pageTitle', $group->groupname)

@push('breadcrumbs')
    <li><a href="{{ route('group::index') }}"><i class="fa fa-groups"></i> Group</a></li>
    <li>Edit</li>
    <li><a href="{{ route('group::show', $group->groupname) }}">{{ $group->groupname }}</a></li>
@endpush

@section('content')
    @if(isset($new) && $new)
        {!! BootForm::open()->action(route('group::save')) !!}
        {!! BootForm::text('Group name', 'groupName')->value($group->groupname) !!}
        {!! BootForm::text('Assigned User', 'username') !!}
    @else
        {!! BootForm::open()->action(route('group::update', $group->groupname)) !!}
        {!! BootForm::text('Group name', 'groupName')->value($group->groupname)->disabled() !!}
        <input type="hidden" name="_method" value="PUT">
    @endif

    <div class="box">
        <div class="box-header">
            <h4>Group Attributes</h4>
        </div>

        <div class="box-body" ng-app="attributeApp">
            @include(
                'widgets.attribute-editor',
                [
                    'title'     => 'Check',
                    'type'      => 'check',
                    'username'  => '',
                    'groupName' => $group->groupname
                ]
            )

            @include(
                'widgets.attribute-editor',
                [
                    'title'     => 'Reply',
                    'type'      => 'reply',
                    'username'  => '',
                    'groupName' => $group->groupname
                ]
            )
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-lg btn-danger" href="{{ route('group::show', $group->groupname) }}">Cancel</a>
            {!! BootForm::submit('Submit')->addClass('btn-primary btn-lg') !!}
        </div>
    </div>

    {!! BootForm::close() !!}
@endsection

@push('scripts')
    <script src="{{ asset('/js/angular.min.js') }}"></script>
    <script src="{{ asset('/js/widgets/attribute-editor.js') }}"></script>
@endpush
