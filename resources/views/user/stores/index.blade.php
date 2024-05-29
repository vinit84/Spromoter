@php use Illuminate\Support\Number; @endphp
@extends('layouts.user.app', [
    'title' => trans('Stores'),
    'actions' => [
        [
            'text' => trans('Add Store'),
            'icon' => 'ti ti-plus',
            'link' => route('user.stores.create'),
        ]
    ]
])

@section('content')
    <div class="app-academy">
        <div class="card mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between gap-3">
                <div class="card-title mb-0 me-1">
                    <h5 class="mb-1">{{ trans('My Stores') }}</h5>
                    <p class="text-muted mb-0">{{ trans('Total :count stores your have added', ['count' => $stores->total()]) }}</p>
                </div>
                <div class="d-flex justify-content-md-end align-items-center gap-3 flex-wrap">
                    <select id="select2_course_select" class="form-select" data-control="select2"
                            data-placeholder="{{ trans('Filter Category') }}" aria-label>
                        <option value=""></option>
                        <option value="0">{{ trans('All Categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-4 mb-4">
                    @foreach($stores as $store)
                        <div class="col-sm-6 col-lg-4">
                            <div class="card p-2 h-100 shadow-none border">
                                <div @class(['rounded-2 text-center mb-3', 'store-preview' => !$store->preview_image])>
                                    @if($store->preview_image)
                                        <a href="{{ route('user.stores.show', $store) }}">
                                            <img
                                                class="img-fluid"
                                                src="{{ $store->preview_image }}"
                                                alt="tutor image 1"
                                            />
                                        </a>
                                    @else
                                        <a href="{{ route('user.stores.show', $store) }}">
                                            <img
                                                class="img-fluid"
                                                src="{{ asset('assets/img/bg-transparant.png') }}"
                                                alt="{{ $store->name }}"
                                            />
                                        </a>
                                    @endif
                                </div>
                                <div class="card-body p-3 pt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-label-primary">{{ $store->category?->name }}</span>
                                        <h6 class="d-flex align-items-center justify-content-center gap-1 mb-0">
                                            {{ number_format($store->reviews_avg_rating, 2) }}
                                            <span class="text-warning">
                                                @for($i = 0; $i < 5; $i++)
                                                    @if(fmod($store->reviews_avg_rating, 1) > 0 && $i + 1 > $store->reviews_avg_rating && $i < $store->reviews_avg_rating)
                                                        <i class="ti ti-star-half-filled"></i>
                                                    @elseif($i < $store->reviews_avg_rating)
                                                        <i class="ti ti-star-filled"></i>
                                                    @else
                                                        <i class="ti ti-star"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                            <span
                                                class="text-muted">({{ Number::forHumans($store->reviews_count) }})</span>
                                        </h6>
                                    </div>
                                    <a href="{{ route('user.stores.show', $store) }}" class="h5">
                                        {{ $store->name }}
                                    </a>
                                    <p class="d-flex align-items-center"><i class="ti ti-clock me-2 mt-n1"></i>
                                        {{ dateFormat($store->created_at) }}
                                    </p>

                                    <div class="d-flex flex-column flex-md-row gap-2 text-nowrap">
                                        <a class="app-academy-md-50 btn btn-label-primary d-flex align-items-center"
                                           href="app-academy-course-details.html">
                                            <span class="me-2">{{ trans('Select') }}</span>
                                            <i class="ti ti-chevron-right scaleX-n1-rtl ti-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $stores->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/spinkit/spinkit.css') }}"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/block-ui/block-ui.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        $(function () {
            $('.store-preview').each(function () {
                $(this).block({
                    message: '<div class="spinner-border text-white" role="status"></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0',
                    },
                    overlayCSS: {
                        opacity: 0.5
                    }
                });
            });
        });
    </script>
@endpush
