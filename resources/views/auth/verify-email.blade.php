@extends('layouts.auth.cover', [
    'title' => trans('Verify Email Address'),
])

@section('content')
    @if(session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            {{ trans('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <p>{{ trans('Before proceeding, please check your email for a verification link.') }}</p>

    <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-primary align-items-center w-100 btn-lg">{{ trans('Send New') }}</button>
    </form>

    <div class="mt-3 text-center">
        <a href="javascript:void(0)" onclick="document.getElementById('logout').submit()">{{ trans('Logout') }}</a>
        <form action="{{ route('logout') }}" method="post" id="logout">
            @csrf
        </form>
    </div>
@endsection
