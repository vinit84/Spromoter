@extends('layouts.user.app', [
    'title' => trans('Email Setup'),
])

@section('content')
    <div>
        <h4>{{ trans('Set up your automatic review requests') }}</h4>
        <p>{{ trans('Select and customize the review requests customers will receive after making a purchase.') }}</p>


        <div class="row g-4">
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('assets/img/placeholder.png') }}" class="card-img-top" alt="">
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ trans('Review request email') }}
                        </h5>
                        <p class="card-text">
                            {{ trans('Create a single review request email for all your products and customers.') }}
                        </p>

                        <a href="{{ route('user.emails.email-setup.review-request-email') }}" class="btn btn-outline-primary waves-effect float-end">
                            <i class="ti ti-edit me-0 me-sm-1 ti-xs"></i>
                            {{ trans('Edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
