<div class="mt-3">
    <h5 class="mb-0 fw-light">{{ trans(":percentage%", ['percentage' => $percentageRatings[$count]]) }}</h5>
    <div class="d-flex justify-content-between align-items-center">
        <small>
            {{ trans(':count Reviews', ['count' => $storeReviews[$count] ?? 0]) }}
        </small>

        <div>
            @for($i = 0; $i < $count; $i++)
                <i class="ti ti-star-filled text-warning"></i>
            @endfor
        </div>
    </div>
    <div class="progress w-100" style="height: 10px">
        <div
            class="progress-bar bg-primary"
            role="progressbar"
            style="width: {{ $percentageRatings[$count] }}%"
            aria-valuenow="{{ $percentageRatings[$count] }}"
            aria-valuemin="0"
            aria-valuemax="100"></div>
    </div>
</div>
