@extends('layouts.admin.app', [
    'title' => $store->name,
    'back' => route('admin.stores.index'),
])

@section('content')
    <!-- Card Border Shadow -->
    <div class="row">
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ti ti-stars ti-md"></i>
                            </span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $overviewReviewRequestsSent }}</h4>
                    </div>
                    <p class="mb-1">{{ trans('Review Requests Sent') }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded bg-label-warning">
                              <i class="ti ti-box ti-md"></i
                              >
                          </span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $overviewReviewsCollected }}</h4>
                    </div>
                    <p class="mb-1">{{ trans('Reviews Collected') }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                          <span class="avatar-initial rounded bg-label-danger"
                          ><i class="ti ti-world-star ti-md"></i
                              ></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $overviewReviewsPublished }}</h4>
                    </div>
                    <p class="mb-1">{{ trans('Reviews Published') }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-info"><i class="ti ti-clock ti-md"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">
                            {{ Number::format($overviewAverageRating, 1) }}
                            <i class="ti ti-star-filled text-warning ms-1"></i>
                        </h4>
                    </div>
                    <p class="mb-1">{{ trans('Average Rating') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
