@extends('layouts.auth.cover')

@section('content')
    <form action="{{ route('password.email') }}" method="POST" class="ajaxform">
        <div class="row g-4">
            <div class="col-12">
                <input type="email" class="form-control" name="email" id="email" placeholder="{{ trans('Enter your email address') }}" aria-label="" autocomplete="email" required autofocus>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary align-items-center w-100 btn-lg">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>

            <div class="col-12">
                <a class=" btn-icon d-flex align-items-center w-100 justify-content-center" href="{{ route('login') }}">
                    {{ trans('Login') }}
                </a>
            </div>
        </div>
    </form>
@endsection
