@extends('email')

@section('content')
    <p>Hello {{ $name }},</p>
    <p>You or someone else has requested a password reset link for your account {{ $username }}.</p>
    <p>If you did not request this e-mail you can safely disregard this email to reset your password.</p>
    <p>If you would link to reset your password please click <a href="{{ $link }}">here</a>.</p>
@endsection