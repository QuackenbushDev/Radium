@extends('blank')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4>Connectivity Test Results ({{ $user->username }})</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3>Command</h3>
            <p>{{ $command }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3>Output</h3>
            <p>{{ $output }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <a href="{{ route('user::test', $user->id) }}" class="btn btn-lg btn-success pull-right">Back</a>
        </div>
    </div>
@endsection