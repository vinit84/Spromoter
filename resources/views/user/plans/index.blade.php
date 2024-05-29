@php use App\Models\Plan; @endphp
@extends('layouts.user.app')

@section('content')
    <form action="{{ route('user.subscriptions.checkout') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12 col-md-9">
                <div class="row g-3">
                    @foreach($plans as $plan)
                        <div class="col-12 col-md-4">
                            <div class="card card-border-shadow-primary h-100">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="plan"
                                            id="{{ $plan->id }}"
                                            value="{{ $plan->id }}"
                                            @checked($loop->first)
                                            data-monthly-price="{{ $plan->monthly_price }}"
                                            data-yearly-price="{{ $plan->yearly_price }}"
                                            data-trial-days="{{ $plan->trial_days }}"
                                        >
                                        <label class="form-check-label h6 mb-0" for="{{ $plan->id }}">
                                            {{ $plan->title }}
                                        </label>
                                    </div>
                                    <hr>
                                    <ul class="list-unstyled mb-0">
                                        @if($plan->trial_days > 0)
                                            <li class="mb-2">
                                            <span
                                                class="badge me-2 badge-center rounded-pill w-px-20 h-px-20 bg-label-primary">
                                                <i class="ti ti-check"></i>
                                            </span>
                                                {{ trans(':days-days free trial', ['days' => $plan->trial_days]) }}
                                            </li>
                                        @endif

                                        @foreach($plan->card_features ?? [] as $feature => $value)
                                            <li class="mb-2">
                                                <span
                                                    class="badge me-2 badge-center rounded-pill w-px-20 h-px-20 bg-label-primary">
                                                    <i class="ti ti-check"></i>
                                                </span>
                                                {{ Plan::getFeatureBySlug($feature)['title'] }}
                                            </li>
                                        @endforeach
                                    </ul>

                                    <p class="mb-0">{{ $plan->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-4"></div>

                <div class="table-responsive border rounded">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th scope="col">
                                <p class="mb-0 fw-bold">{{ trans('Features') }}</p>
                            </th>

                            @foreach($plans as $plan)
                                <th class="text-center" scope="col">
                                    @if($plan->is_recomended)
                                        <p class="mb-0 fw-bold position-relative">
                                            {{ $plan->title }}
                                            <span
                                                class="badge badge-center rounded-pill bg-warning position-absolute mt-n1 ms-1">
                                                    <i class="ti ti-star"></i>
                                                </span>
                                        </p>
                                    @else
                                        <p class="mb-0 fw-bold">{{ $plan->title }}</p>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(Plan::FEATURES as $feature)
                            <tr>
                                <td class="fw-bold text-primary">{{ $feature['title'] }}</td>
                                <td colspan="{{ $plans->count() }}"></td>
                            </tr>
                            @foreach($feature['features'] as $item)
                                <tr>
                                    <td class="text-small">{{ $item['title'] }}</td>
                                    @foreach($plans as $plan)
                                        <td class="text-center">
                                            @if($plan->features->has($item['slug']))
                                                <span
                                                    class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary">
                                                    <i class="ti ti-check"></i>
                                                </span>
                                            @else
                                                <span
                                                    class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-danger">
                                                    <i class="ti ti-x"></i>
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card" id="pricePlanCard">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            {{ trans('Selected Plan') }}
                        </div>

                        <div class="check-box">
                            <div class="form-check mb-2 custom-option custom-option-basic checked">
                                <label class="form-check-label custom-option-content" for="billedYearly">
                                    <input name="interval" class="form-check-input" type="radio" value="yearly"
                                           id="billedYearly" checked>
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">{{ trans('Billed Yearly') }}</span>
                                            <span class="text-muted" id="yearlyDiscount"></span>
                                        </span>
                                    <span class="custom-option-body">
                                        <small id="yearlyPrice"></small>
                                    </span>
                                </label>
                            </div>

                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="billedMonthly">
                                    <input name="interval" class="form-check-input" type="radio" value="monthly"
                                           id="billedMonthly">
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">{{ trans('Billed Monthly') }}</span>
                                            <span class="text-muted" id="monthlyDiscount"></span>
                                        </span>
                                    <span class="custom-option-body">
                                        <small id="monthlyPrice"></small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <button type="submit" class="btn mt-4 btn-primary w-100">
                                <i class="ti ti-brand-stripe ti-sm"></i>
                                {{ trans('Checkout') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('pageScripts')
    <script src="{{ asset('assets/js/sticky.js') }}"></script>

    <script>        
        $('input[name="plan"]').on('change', function () {
            updateSummary();
        })

        $(document).ready(function () {
            updateSummary();

            $("#pricePlanCard").sticky({
                topSpacing: 94
            });
        });

        function updateSummary() {
            let yearlyDiscount = $('#yearlyDiscount');
            let monthlyDiscount = $('#monthlyDiscount');
            let yearlyPrice = $('#yearlyPrice');
            let monthlyPrice = $('#monthlyPrice');

            let plan = $('input[name="plan"]:checked');
            let interval = $('input[name="interval"]:checked').val();

            yearlyPrice.text(trans(':price /:interval', {
                price: '$' + plan.data('yearly-price'),
                interval: trans('year')
            }));
            monthlyPrice.text(trans(':price /:interval', {
                price: '$' + plan.data('monthly-price'),
                interval: trans('month')
            }));

            // Convert text to numeric values
            let yearlyPriceValue = parseFloat(yearlyPrice.text().replace(/[^\d.-]/g, ''));
            let monthlyPriceValue = parseFloat(monthlyPrice.text().replace(/[^\d.-]/g, ''));

            // Show discount
            let yearlyDiscountValue = 1 - (yearlyPriceValue / (monthlyPriceValue * 12));
            let monthlyDiscountValue = 1 - (monthlyPriceValue * 12 / yearlyPriceValue);


            if (yearlyDiscountValue > 0){
                yearlyDiscount.text(trans('Save :discount%', {discount: (yearlyDiscountValue * 100).toFixed(0)}));
            }else{
                yearlyDiscount.text('');
            }


            if (monthlyDiscountValue > 0){
                monthlyDiscount.text(trans('Save :discount%', {discount: (monthlyDiscountValue * 100).toFixed(0)}));
            }else{
                monthlyDiscount.text('');
            }

        }

    </script>
@endpush
