@extends('layouts.auth.master')

@section('body')
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img
                        src="{{ asset('assets/img/illustrations/auth-login-illustration-light.png') }}"
                        alt="auth-login-cover"
                        class="img-fluid my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-login-illustration-light.png"
                        data-app-dark-img="illustrations/auth-login-illustration-dark.png"/>

                    <img
                        src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
                        alt="auth-login-cover"
                        class="platform-bg"
                        data-app-light-img="illustrations/bg-shape-image-light.png"
                        data-app-dark-img="illustrations/bg-shape-image-dark.png"/>
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="">
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h3 class="mb-1">{{ trans('Welcome to :name! 👋', ['name' => setting('site_title', config('app.name'))]) }}</h3>
                    @isset($description)<p class="mb-4">{{ $description }}</p>@endisset

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endsection
