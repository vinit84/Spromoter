@extends('layouts.frontend.blank')

@section('body')

    <!-- Error -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h2 class="mb-1 mt-4">{{ trans('Thank You!') }}</h2>
            <p class="mb-4 mx-2">{{ trans('Your email address has been verified successfully.') }}</p>

            @if($review->product->url)
                <a href="{{ $review->product->url }}" class="btn btn-primary mb-4">{{ trans('Visit to product') }}</a>
            @elseif($review->store->url)
                <a href="{{ $review->store->url }}" class="btn btn-primary mb-4">{{ trans('Back to home') }}</a>
            @else
                <button class="btn btn-primary mb-4" onclick="window.close()">
                    {{ trans('Close window') }}
                </button>
            @endif
            <div class="mt-4">
                <img
                    src="{{ asset('assets/img/illustrations/auth-verify-email-illustration-light.png') }}"
                    alt="page-misc-error"
                    width="225"
                    class="img-fluid" />
            </div>
        </div>
    </div>
    <div class="container-fluid misc-bg-wrapper">
        <img
            src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
            alt="page-misc-error" />
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/page-misc.css') }}" />
@endpush
