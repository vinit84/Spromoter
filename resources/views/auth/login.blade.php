@extends('layouts.auth.cover', [
    'title' => trans('Login'),
    'description' => trans('Please sign-in to your account and start the adventure')
])

@section('content')
    <form class="mb-3 ajaxform" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">{{ trans('Email') }}</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="{{ trans('Enter your email') }}" autofocus required/>
        </div>

        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">{{ trans('Password') }}</label>
                <a href="{{ route('password.request') }}">
                    <small>{{ trans('Forgot Password?') }}</small>
                </a>
            </div>
            <div class="input-group input-group-merge">
                <input type="password" name="password" id="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required/>
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"/>
                <label class="form-check-label" for="remember"> {{ trans('Remember Me') }} </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary d-grid w-100">{{ trans('Sign in') }}</button>
    </form>

    <p class="text-center">
        <span>{{ trans('New on our platform?') }}</span>
        <a href="{{ route('register') }}">
            <span>{{ trans('Create an account') }}</span>
        </a>
    </p>

    @include('auth.social-provider')
@endsection
