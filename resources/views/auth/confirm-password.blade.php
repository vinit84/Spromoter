@extends('layouts.frontend.app', [
    'title' => trans('Confirm Password'),
    'breadcrumbs' => [
        ['label' => trans('Home'), 'url' => route('frontend.home')],
        ['label' => trans('Confirm Password')],
    ]
])

@section('content')
    <div class="login-section">
        <div class="divider"></div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7 col-xxl-6">
                    <div class="card login-card">
                        <div class="card-body">

                            <p>{{ trans('Please confirm your password before continuing.') }}</p>

                            <form action="{{ route('password.confirm') }}" method="POST" class="ajaxform">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="{{ trans('Enter your password') }}" aria-label="" autocomplete="password" required>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary align-items-center w-100 btn-lg">
                                            {{ trans('Confirm Password') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>
    </div>

    @include('frontend.components.call-to-action')
@endsection
