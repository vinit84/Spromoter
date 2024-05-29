@extends('layouts.auth.cover', [
    'title' => trans('Account Status'),
    'description' => trans('Please sign-in to your account and start the adventure')
])

@section('content')
    @if(Cache::get('impersonate'))
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>
            {!! trans('You are currently logged in as :name, :link to login as yourself again.', [
                'name' => '<strong>' . auth()->user()->name . '</strong>',
                'link' => '<a href="' . route('user.impersonate.leave') . '">Click here</a>',
            ]) !!}
        </div>
    @endif

    <div class="text-center">
        <p>{{ trans('Your account is not active. Please contact the administrator.') }}</p>

        <a class="btn btn-primary ajax-link" href="{{ route('logout') }}">
            {{ trans('Logout') }}
        </a>
    </div>
@endsection
