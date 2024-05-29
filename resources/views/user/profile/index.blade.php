@extends('layouts.user.app')

@section('content')
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/img/profile-banner.png') }}" alt="Banner image" class="rounded-top w-100"/>
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img
                            src="{{ $user->avatar }}"
                            alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img"/>
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ $user->name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    @isset($user->position)
                                        <li class="list-inline-item d-flex gap-1">
                                            <i class="ti ti-color-swatch"></i> {{ $user->position }}
                                        </li>
                                    @endisset

                                    @isset($user->city)
                                        <li class="list-inline-item d-flex gap-1"><i
                                                class="ti ti-map-pin"></i> {{ $user->city }}</li>
                                    @endisset

                                    @isset($user->created_at)
                                        <li class="list-inline-item d-flex gap-1">
                                            <i class="ti ti-calendar"></i> {{ trans('Joined :date', ['date' => dateFormat($user->created_at)]) }}
                                        </li>
                                    @endisset
                                </ul>
                            </div>
                            <a href="{{ route('user.profile.edit') }}" class="btn btn-primary">
                                <i class="ti ti-user-edit me-1"></i>{{ trans('Edit Profile') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- About User -->
            <div class="card mb-4">
                <div class="card-body">
                    <small class="card-text text-uppercase">{{ trans('About') }}</small>
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-user text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">{{ trans('Full Name:') }}</span>
                            <span>{{ $user->name }}</span>
                        </li>

                        @isset($user->company)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-building text-heading"></i
                                ><span class="fw-medium mx-2 text-heading">{{ trans('Company:') }}</span>
                                <span>{{ $user->company }}</span>
                            </li>
                        @endisset

                        @isset($user->position)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-crown text-heading"></i
                                ><span class="fw-medium mx-2 text-heading">{{ trans('Position:') }}</span>
                                <span>{{ $user->position }}</span>
                            </li>
                        @endisset

                        @isset($user->country)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-flag text-heading"></i
                                ><span class="fw-medium mx-2 text-heading">{{ trans('Country:') }}</span>
                                <span>{{ $user->country }}</span>
                            </li>
                        @endisset
                    </ul>
                    <small class="card-text text-uppercase">{{ trans('Contacts') }}</small>
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-mail"></i><span
                                class="fw-medium mx-2 text-heading">{{ trans('Email:') }}</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-phone-call"></i><span
                                class="fw-medium mx-2 text-heading">{{ trans('Phone:') }}</span>
                            <span>{{ $user->phone }}</span>
                        </li>

                        @isset($user->social_skype)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-brand-skype"></i><span
                                    class="fw-medium mx-2 text-heading">{{ trans('Skype:') }}</span>
                                <span>{{ $user->social_skype }}</span>
                            </li>
                        @endisset

                        @isset($user->social_twitter)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-brand-twitter"></i><span
                                    class="fw-medium mx-2 text-heading">{{ trans('Twitter:') }}</span>
                                <span>{{ $user->social_twitter }}</span>
                            </li>
                        @endisset

                        @isset($user->social_facebook)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-brand-facebook"></i><span
                                    class="fw-medium mx-2 text-heading">{{ trans('Facebook:') }}</span>
                                <span>{{ $user->social_facebook }}</span>
                            </li>
                        @endisset

                        @isset($user->social_linkedin)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-brand-linkedin"></i><span
                                    class="fw-medium mx-2 text-heading">{{ trans('Linkedin:') }}</span>
                                <span>{{ $user->social_linkedin }}</span>
                            </li>
                        @endisset

                        @isset($user->social_instagram)
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-brand-instagram"></i><span
                                    class="fw-medium mx-2 text-heading">{{ trans('Instagram:') }}</span>
                                <span>{{ $user->social_instagram }}</span>
                            </li>
                        @endisset
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7 col-md-7">
            <!-- Activity Timeline -->
            <div class="card card-action mb-4">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0">{{ trans('Activity Timeline') }}</h5>
                </div>
                <div class="card-body pb-0">
                    <ul class="timeline ms-1 mb-0">
                        @foreach($logs as $log)
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-success"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header">
                                        <h6 class="mb-0">{{ $log->description }}</h6>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--/ User Profile Content -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/page-profile.css') }}"/>
@endpush
