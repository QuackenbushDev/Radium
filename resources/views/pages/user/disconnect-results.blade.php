@extends('iframe')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4>Disconnect Results ({{ $user->username }})</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h6>Command</h6>
            <p>{{ $command }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h6>Output</h6>
            <p>{{ $output }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <a href="{{ route('user::disconnect', $user->id) }}" class="btn btn-lg btn-success pull-right">Back</a>
        </div>
    </div>
@endsection