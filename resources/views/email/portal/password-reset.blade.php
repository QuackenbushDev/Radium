@extends('email')

@section('content')
    Click here to reset your password: <a href="{{ $link }}"> {{ $link }} </a>
@endsection