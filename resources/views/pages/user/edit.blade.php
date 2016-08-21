@extends('master')

@section('pageTitle', $user->username)
@section('pageDescription', 'Details')

@push('breadcrumbs')
    <li><a href="{{ route('user::index') }}"><i class="fa fa-group"></i> Users</a></li>
    <li><a href="#">Edit</a></li>
    <li><a href="{{ route('user::edit', $user->id)  }}">{{ $user->username }}</a></li>
@endpush

@section('content')
    @if (isset($new) && $new)
        {!! BootForm::open()->action(route('user::save')) !!}
    @else
        {!! BootForm::open()->action(route('user::update', ['id' => $user->id])) !!}
        <input type="hidden" name="_method" value="PUT">
    @endif

    @include('pages.user.partials.account-info-edit')

    <div class="box">
        <div class="box-header">
            <h4>User Attributes</h4>
        </div>

        <div class="box-body" ng-app="attributeApp">
            @include(
                'widgets.attribute-editor',
                [
                    'title'     => 'Check',
                    'type'      => 'check',
                    'username'  => $user->username,
                    'groupName' => ''
                ]
            )

            @include(
                'widgets.attribute-editor',
                [
                    'title'     => 'Reply',
                    'type'      => 'reply',
                    'username'  => $user->username,
                    'groupName' => ''
                ]
            )
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-lg btn-danger" href="{{ route('user::show', $user->id) }}">Cancel</a>
            {!! BootForm::submit('Submit')->addClass('btn-primary btn-lg') !!}
        </div>
    </div>
    {!! BootForm::close() !!}

    @push('scripts')
        <script src="{{ asset('/js/angular.min.js') }}"></script>
        <script src="{{ asset('/js/widgets/attribute-editor.js') }}"></script>
        <script src="{{ asset('/js/pages/user.js') }}"></script>
    @endpush
@endsection
