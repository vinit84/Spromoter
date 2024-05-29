@extends('layouts.user.app', [
    'title' => trans('Billing')
])

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('user.profile.partials.navbar')
            <div class="card mb-4">
                @if($subscription)
                    <!-- Current Plan -->
                    <h5 class="card-header">{{ trans('Current Plan') }}</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <div class="mb-3">
                                    <h6 class="mb-1">{{ trans('Your Current Plan is :name', ['name' => $subscription->name]) }}</h6>
                                    <p>A simple start for everyone</p>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-1">
                                        @if($subscription->onTrial())
                                            <span class="me-2">
                                            {{ trans('Active until :date', ['date' => dateFormat($subscription->trial_ends_at)]) }}
                                        </span>
                                            <span class="badge bg-label-primary">{{ trans('Trial') }}</span>
                                        @else
                                            {{ trans('Active until :date', ['date' => dateFormat($subscription->ends_at)]) }}
                                        @endif
                                    </h6>
                                    <p>We will send you a notification upon Subscription expiration</p>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-1">
                                        <span class="me-2">$199 Per Month</span>
                                        <span class="badge bg-label-primary">Popular</span>
                                    </h6>
                                    <p>Standard plan for small to medium businesses</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
{{--                                <div class="alert alert-warning mb-3" role="alert">--}}
{{--                                    <h5 class="alert-heading mb-1">We need your attention!</h5>--}}
{{--                                    <span>Your plan requires update</span>--}}
{{--                                </div>--}}
                                <div class="plan-statistics">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-2">Days</h6>
                                        <h6 class="mb-2">24 of 30 Days</h6>
                                    </div>
                                    <div class="progress">
                                        <div
                                            class="progress-bar w-75"
                                            role="progressbar"
                                            aria-valuenow="75"
                                            aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                    <p class="mt-1 mb-0">6 days remaining until your plan requires update</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('user.profile.billing.portal') }}" class="btn btn-primary me-2 mt-2">
                                    {{ trans('Manage') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /Current Plan -->
                @endif
            </div>

            <div class="card">
                <!-- Billing History -->
                <h5 class="card-header">Billing History</h5>
                <div class="card-datatable table-responsive">
                    <table class="invoice-list-table table border-top">
                        <thead>
                        <tr>
                            <th></th>
                            <th>#ID</th>
                            <th><i class="ti ti-trending-up"></i></th>
                            <th>Client</th>
                            <th>Total</th>
                            <th class="text-truncate">Issued Date</th>
                            <th>Balance</th>
                            <th>Invoice Status</th>
                            <th class="cell-fit">Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!--/ Billing History -->
            </div>
        </div>
    </div>
@endsection
