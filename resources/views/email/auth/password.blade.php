@extends('email.layout')
@section('content')
    <p>
        You are receiving this email because we received a password reset request for your account.
    </p>
    <p>
        Visit the url below to complete your password reset.<br>
        <a href="{{ route('login.password.confirm', ['token' => $token], ['email' => $user->email], true) }}">
            {{ route('login.password.confirm', ['token' => $token], ['email' => $user->email], true) }}
        </a>
    </p>
    <p>
        If you did not request a password reset, no further action is required.
    </p>
@endsection
