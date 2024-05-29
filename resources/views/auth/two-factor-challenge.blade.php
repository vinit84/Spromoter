@extends('layouts.frontend.app', [
    'title' => trans('Two Factor Challenge'),
    'breadcrumbs' => [
        ['label' => trans('Home'), 'url' => route('frontend.home')],
        ['label' => trans('Two Factor Challenge')],
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
                            <div class="text mb-3">
                                <p class="mb-0">{{ trans('Please confirm your two factor challenge before continuing.') }}</p>
                            </div>

                            <form action="/two-factor-challenge" method="POST" class="ajaxform">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <ul class="nav nav-pills mb-3 justify-content-between" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="pills-code-tab" data-bs-toggle="pill" href="#pills-code"
                                                role="tab" aria-controls="pills-code" aria-selected="true">
                                                    {{ __('Use an authentication code') }}
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-recovery-tab" data-bs-toggle="pill" href="#pills-recovery"
                                                role="tab" aria-controls="pills-recovery" aria-selected="false">
                                                    {{ __('Use a recovery code') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-12">
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-code" role="tabpanel"
                                                aria-labelledby="pills-code-tab">
                                                <div class="mb-3">
                                                    <p>{{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" class="form-control" name="code" id="code" placeholder="{{ trans('Code') }}" aria-label="" required>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="pills-recovery" role="tabpanel"
                                                aria-labelledby="pills-recovery-tab">
                                                <div class="mb-3">
                                                    <p>{{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}</p>
                                                </div>

                                                <div class="col-12">
                                                    <input type="text" class="form-control" name="recovery_code" id="recovery_code" placeholder="{{ trans('Recovery Code') }}" aria-label="" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary align-items-center w-100 btn-lg">
                                            {{ trans('Login') }}
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
