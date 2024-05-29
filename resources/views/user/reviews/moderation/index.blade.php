@extends('layouts.user.app', [
    'title' => $store->name,
])

@section('content')

    <div class="row mb-4 g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body row widget-separator g-0">
                    <div class="col-sm-5 border-shift border-end">
                        <h2 class="text-primary d-flex align-items-center gap-1 mb-2">
                            {{ number_format($averageRating, 2) }}
                            <i class="ti ti-star-filled"></i>
                        </h2>
                        <p class="h6 mb-1">{{ trans('Total :count reviews', ['count' => $totalReviews]) }}</p>
                        <p class="pe-2 mb-2">{{ trans('All reviews are from customers') }}</p>
                        <span class="badge bg-label-primary p-2 mb-sm-0">
                            {{ trans(':count This week', ['count' => $thisWeekReviews > 0 ? trans('+ :count', ['count' => $thisWeekReviews]) : 0]) }}
                        </span>
                        <hr class="d-sm-none" />
                    </div>

                    <div class="col-sm-7 gap-2 text-nowrap d-flex flex-column justify-content-between ps-sm-4 pt-2 py-sm-2">
                        @for($i = 5; $i >= 1; $i--)
                            <div class="d-flex align-items-center gap-3">
                                <small>{{ trans(':number Star', ['number' => $i]) }}</small>
                                <div class="progress w-100" style="height: 10px">
                                    <div
                                        class="progress-bar bg-primary"
                                        role="progressbar"
                                        style="width: {{ $percentageRatings[$i] }}%"
                                        aria-valuenow="{{ $percentageRatings[$i] }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="w-px-20 text-end">
                                    {{ $storeReviews[$i] ?? 0 }}
                                </small>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body row">
                    <div class="col-sm-5">
                        <div class="mb-7">
                            <h4 class="mb-2 text-nowrap">{{ trans('Reviews statistics') }}</h4>
                            <p class="mb-0 d-flex align-items-center">
                                <span class="me-2">{{ trans(':count New reviews', ['count' => $newReviews]) }}</span>
                                <span @class(['badge', $previousDayReviews <= $newReviews ? 'bg-label-success' : 'bg-label-danger']) title="{{ trans('Based on previous day') }}" data-bs-toggle="tooltip">
                                        {{ trans(':count%', [
                                            'count' => $previousDayReviews <= $newReviews
                                                ? trans('+ :count', ['count' => round($newReviewsPercentage, 2)])
                                                : round($newReviewsPercentage, 2)
                                        ]) }}

                                    @if($previousDayReviews <= $newReviews)
                                        <i class="ti ti-arrow-up text-success ti-xxs"></i>
                                    @else
                                        <i class="ti ti-arrow-down text-danger ti-xxs"></i>
                                    @endif
                                </span>
                            </p>
                        </div>

                        <div>
                            <h5 class="mb-2 fw-normal">
                                <span class="text-success me-1">
                                    {{ trans(':count%', ['count' => round($positiveReviewsPercentage, 2)]) }}
                                </span>
                                {{ trans('Positive reviews') }}
                            </h5>
                            <small class="text-muted">{{ trans('Weekly Report') }}</small>
                        </div>
                    </div>
                    <div class="col-sm-7 d-flex justify-content-sm-end align-items-end">
                        <div id="reviewsChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-4">
        <div class="col-md-3">
            <div class="accordion" id="accordionWithIcon">
                <div class="card accordion-item active">
                    <h2 class="accordion-header d-flex align-items-center">
                        <button
                            type="button"
                            class="accordion-button"
                            data-bs-toggle="collapse"
                            data-bs-target="#basicFiltersAccordion"
                            aria-expanded="true">
                            <i class="ti ti-filter ti-xs me-2"></i>
                            {{ trans('Filters') }}
                        </button>
                    </h2>

                    <div id="basicFiltersAccordion" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="basic-filter-accordion">
                                <div class="form-check custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content">
                                        <input name="basic_filter" class="form-check-input" type="radio" value="scheduled">
                                        <span class="custom-option-header">
                                        <span class="h6 mb-0">{{ trans('Pending') }}</span>
                                        <span class="text-muted" id="scheduledToPublish"></span>
                                    </span>
                                    </label>
                                </div>
                                <div class="form-check custom-option custom-option-basic checked">
                                    <label class="form-check-label custom-option-content">
                                        <input name="basic_filter" class="form-check-input" type="radio" value="all" checked>
                                        <span class="custom-option-header">
                                            <span class="h6 mb-0">{{ trans('Total Reviews') }}</span>
                                            <span class="badge bg-white text-primary">{{ $totalReviews }}</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item card">
                    <h2 class="accordion-header d-flex align-items-center">
                        <button
                            type="button"
                            class="accordion-button collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#advancedFiltersAccordion"
                            aria-expanded="true">
                            <i class="me-2 ti ti-filter-edit ti-xs"></i>
                            {{ trans('Advanced Filters') }}
                        </button>
                    </h2>
                    <div id="advancedFiltersAccordion" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="form-group">
                                <label for="filter_date_range" class="form-label">{{ trans('Date Range') }}</label>
                                <input type="text" id="filter_date_range" class="form-control " />
                            </div>

                            <div class="form-group">
                                <label for="filter_products" class="form-label">{{ trans('Products') }}</label>
                                <select id="filter_products" class="form-select"
                                        data-control="select2" data-placeholder="{{ trans('Select Products') }}"
                                        multiple>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <hr>
                            <label>{{ trans('Star Rating') }}</label>
                            <div class="filter-by-ratings">
                                <div class="form-check custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content">
                                        <input class="form-check-input" type="checkbox" name="filter_ratings" value="5">
                                        <span class="custom-option-header">
                                        <span class="h6 mb-0">
                                            @for($i =0; $i < 5; $i++)
                                                <i class="ti ti-star-filled text-warning"></i>
                                            @endfor
                                        </span>
                                        <span class="text-muted">
                                            {{ $storeReviews[5] ?? 0 }}
                                        </span>
                                    </span>
                                    </label>
                                </div>
                                <div class="form-check custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content">
                                        <input class="form-check-input" type="checkbox" name="filter_ratings" value="4">
                                        <span class="custom-option-header">
                                        <span class="h6 mb-0">
                                            @for($i =0; $i < 4; $i++)
                                                <i class="ti ti-star-filled text-warning"></i>
                                            @endfor
                                        </span>
                                        <span class="text-muted">
                                            {{ $storeReviews[4] ?? 0 }}
                                        </span>
                                    </span>
                                    </label>
                                </div>
                                <div class="form-check custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content">
                                        <input class="form-check-input" type="checkbox" name="filter_ratings" value="3">
                                        <span class="custom-option-header">
                                        <span class="h6 mb-0">
                                            @for($i =0; $i < 3; $i++)
                                                <i class="ti ti-star-filled text-warning"></i>
                                            @endfor
                                        </span>
                                        <span class="text-muted">
                                            {{ $storeReviews[3] ?? 0 }}
                                        </span>
                                    </span>
                                    </label>
                                </div>
                                <div class="form-check custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content">
                                        <input class="form-check-input" type="checkbox" name="filter_ratings" value="2">
                                        <span class="custom-option-header">
                                        <span class="h6 mb-0">
                                            @for($i =0; $i < 2; $i++)
                                                <i class="ti ti-star-filled text-warning"></i>
                                            @endfor
                                        </span>
                                        <span class="text-muted">
                                            {{ $storeReviews[2] ?? 0 }}
                                        </span>
                                    </span>
                                    </label>
                                </div>
                                <div class="form-check custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content">
                                        <input class="form-check-input" type="checkbox" name="filter_ratings" value="1">
                                        <span class="custom-option-header">
                                        <span class="h6 mb-0">
                                            @for($i =0; $i < 1; $i++)
                                                <i class="ti ti-star-filled text-warning"></i>
                                            @endfor
                                        </span>
                                        <span class="text-muted">
                                            {{ $storeReviews[1] ?? 0 }}
                                        </span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <x-data-table :data-table="$dataTable" />
        </div>
    </div>
@endsection

@push('modals')
    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">{{ trans('Comment') }}</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="{{ trans('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.reviews.moderation.comment') }}" method="POST" id="commentForm" class="ajaxform">
                        @csrf
                        <input type="hidden" name="review_id" id="reviewId">
                        <div class="row">
                            <div class="col mb-3">
                            <textarea
                                id="comment"
                                name="comment"
                                class="form-control"
                                placeholder="{{ trans('Write a comment') }}"
                                rows="5"
                                aria-label="{{ trans('Comment') }}"
                            ></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        {{ trans('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" form="commentForm">{{ trans('Publish') }}</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        window.days = @json($weeklyReviews->keys());
        window.reviews = @json($weeklyReviews->values());
    </script>
    <script src="{{ asset('assets/js/page/user/stores/show.js') }}"></script>
    <script>
        // on click of comment button
        $('#commentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reviewId = button.data('review-id');
            var modal = $(this);
            modal.find('#reviewId').val(reviewId);
        });

        $("#commentForm").on('ajaxFormSuccess', function () {
            $(this).resetForm();

            $('#commentModal').modal('hide');

            // reload the data table
            window.LaravelDataTables["review-table"].draw();
        })
    </script>
@endpush
