<div>
    <p>{{ dateFormat($review->created_at) }}</p>

    @for($i = 0; $i < $review->rating; $i++)
        <i class="ti ti-star-filled text-warning"></i>
    @endfor

    <h6 class="mb-0 mt-2 fw-bold">
        {{ $review->title }}
    </h6>

    <p>
        {{ $review->comment }}
    </p>

    <a
        class="text-muted"
        data-bs-toggle="collapse"
        href="#orderDetails{{ $review->id }}"
        role="button"
        aria-expanded="false"
        aria-controls="orderDetails{{ $review->id }}">
        {{ trans('More details') }}
    </a>
    <div class="collapse mt-2" id="orderDetails{{ $review->id }}">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <tbody>
                {{--<tr>
                    <th>{{ trans('Order ID') }}</th>
                    <td>{{ "NA" }}</td>
                </tr>--}}
                <tr>
                    <th>{{ trans('Unique Specs') }}</th>
                    <td>
                        @isset($review->product->specs['ean'])
                            {{ trans(':spec : :value', ['spec' => 'ean', 'value' => $review->product->specs['ean']]) }}
                        @elseif(isset($review->product->specs['mpn']))
                            {{ trans(':spec : :value', ['spec' => 'mpn', 'value' => $review->product->specs['mpn']]) }}
                        @elseif(isset($review->product->specs['sku']))
                            {{ trans(':spec : :value', ['spec' => 'sku', 'value' => $review->product->specs['sku']]) }}
                        @elseif(isset($review->product->specs['upc']))
                            {{ trans(':spec : :value', ['spec' => 'upc', 'value' => $review->product->specs['upc']]) }}
                        @elseif(isset($review->product->specs['asin']))
                            {{ trans(':spec : :value', ['spec' => 'asin', 'value' => $review->product->specs['asin']]) }}
                        @elseif(isset($review->product->specs['gtin']))
                            {{ trans(':spec : :value', ['spec' => 'gtin', 'value' => $review->product->specs['gtin']]) }}
                        @elseif(isset($review->product->specs['isbn']))
                            {{ trans(':spec : :value', ['spec' => 'isbn', 'value' => $review->product->specs['isbn']]) }}
                        @elseif(isset($review->product->specs['brand']))
                            {{ trans(':spec : :value', ['spec' => 'brand', 'value' => $review->product->specs['brand']]) }}
                        @elseif(isset($review->product->unique_id))
                            {{ trans(':spec : :value', ['spec' => 'Unique ID', 'value' => $review->product->unique_id]) }}
                        @else
                            {{ "N/A" }}
                        @endisset
                    </td>
                </tr>
                <tr>
                    <th>{{ trans('Review Source') }}</th>
                    <td>{{ str($review->source)->title() }}</td>
                </tr>
                <tr>
                    <th>{{ trans('Product URL') }}</th>
                    <td>
                        @isset($review->product->url)
                            <a href="{{ $review->product->url }}" target="_blank">
                                {{ $review->product->url }}
                            </a>
                        @else
                            {{ "N/A" }}
                        @endisset
                    </td>
                </tr>
                @if(isset($review->reply))
                <tr>
                    <th>{{ trans('Comment') }}</th>
                    <td>
                        <div class="row align-items-center justify-content-between">
                            <div class="col">
                                <div>
                                    {{ $review->reply->comment }}
                                </div>
                            </div>
                            <div class="col col-auto">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input reply-status" type="checkbox" value="{{ $review->reply->id }}" id="reply_status_{{ $review->reply->id }}" @checked($review->reply->is_public)>
                                    <label class="form-check-label" for="reply_status_{{ $review->reply->id }}">{{ trans('Is Public?') }}</label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
<div class="d-flex justify-content-between mt-2">
    <div class="btn-group ">
        <button
            type="button"
            class="btn btn-outline-dark btn-sm dropdown-toggle"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <i
                @class([
                    'ti',
                    'ti-circle-arrow-up text-primary' => $review->status == 'published',
                    'ti-hourglass-low text-warning' => $review->status == 'pending',
                    'ti-trash text-danger' => $review->status == 'rejected',
                    'ti-bug text-info' => $review->status == 'spam',
                    'me-0 me-sm-1 ti-xs'
                ])
            ></i>
            {{ str($review->status)->title() }}
        </button>
        <ul class="dropdown-menu">
            @if($review->status == 'pending')
                <li>
                    <a class="dropdown-item confirm-action" href="{{ route('admin.stores.reviews.moderation.change-status', [$store, $review, 'published']) }}" data-method="PUT">
                        {{ trans('Publish') }}
                    </a>
                </li>
            @elseif($review->status == 'published')
                <li>
                    <a class="dropdown-item confirm-action" href="{{ route('admin.stores.reviews.moderation.change-status', [$store, $review, 'rejected']) }}" data-method="PUT">
                        {{ trans('Reject') }}
                    </a>
                </li>
            @elseif($review->status == 'rejected')
                <li>
                    <a class="dropdown-item confirm-action" href="{{ route('admin.stores.reviews.moderation.change-status', [$store, $review, 'published']) }}" data-method="PUT">
                        {{ trans('Publish') }}
                    </a>
                </li>
            @elseif($review->status == 'spam')
                <li>
                    <a class="dropdown-item confirm-action" href="{{ route('admin.stores.reviews.moderation.change-status', [$store, $review, 'published']) }}" data-method="PUT">
                        {{ trans('Publish') }}
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <div class="d-flex gap-3 align-items-center">
        @if(!$review->comments()->exists())
            <button type="button"
                    class="btn btn-outline-dark btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#commentModal"
                    data-review-id="{{ $review->id }}"
            >
                <i class="ti ti-messages me-0 me-sm-1 ti-xs"></i>
                {{ trans('Comment') }}
            </button>
        @endif

        {{--<div class="btn-group mt-0">
            <button
                type="button"
                class="btn btn-outline-dark btn-icon btn-sm dropdown-toggle hide-arrow"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
            </ul>
        </div>--}}
    </div>
</div>
