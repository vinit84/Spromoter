@extends('layouts.auth.cover', [
    'title' => trans('Register'),
    'description' => trans('Make your app management easy and fun!')
])

@section('content')
    <form class="mb-3 ajaxform" action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="first_name" class="form-label">{{ trans('First Name') }}</label>
            <input
                class="form-control"
                id="first_name"
                name="first_name"
                placeholder="{{ trans('Enter first name') }}"
                autofocus
                required/>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">{{ trans('Last Name') }}</label>
            <input
                class="form-control"
                id="last_name"
                name="last_name"
                placeholder="{{ trans('Enter first name') }}"
                required/>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">{{ trans('Email') }}</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="{{ trans('Enter your email') }}"
                required/>
        </div>
        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password">{{ trans('Password') }}</label>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password"
                    required
                    min="8"
                />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
        </div>
        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password_confirmation">{{ trans('Confirm Password') }}</label>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password_confirmation"
                    class="form-control"
                    name="password_confirmation"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password_confirmation"
                    required
                    min="8"
                />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="agreement" name="agreement" required/>
                <label class="form-check-label" for="agreement">
                    {!! trans('I agree to :privacy_policy & :terms.', [
                        'privacy_policy' => '<a href="'.route('home.page', 'privacy-policy').'" target="_blank">'.trans('privacy policy').'</a>',
                        'terms' => '<a href="'.route('home.page', 'terms-conditions').'" target="_blank">'.trans('terms').'</a>',
                    ]) !!}
                </label>
            </div>
        </div>
        <button class="btn btn-primary d-grid w-100">{{ trans('Sign up') }}</button>
    </form>

    <p class="text-center">
        <span>{{ trans('Already have an account?') }}</span>
        <a href="{{ route('login') }}">
            <span>{{ trans('Sign in instead') }}</span>
        </a>
    </p>

    @include('auth.social-provider')
@endsection
