@extends('layouts.admin.app', [
    'title' => trans('Publish Settings'),
    'back' => route('admin.stores.show', $store)
])

@section('content')
    <div class="row g-4">
        <div class="col-md-6">
            <form action="{{ route('admin.stores.reviews.publish-settings.update', $store) }}" method="POST" class="ajaxform">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="form-check mt-3">
                            <input type="checkbox" name="auto_publish_reviews" id="auto_publish_reviews" class="form-check-input" value="1" @checked($settings['publish.auto_publish_reviews'] ?? false)>
                            <label for="auto_publish_reviews" class="form-check-label">
                                {{ trans("Auto-publish reviews") }}
                            </label>
                            <div class="form-text">{{ trans("Automatically publish reviews to the Reviews Widget on your site. You can publish all reviews or only reviews with a minimum star rating.") }}</div>
                        </div>
                        <div class="mt-3">
                            <label for="min_rating">{{ trans("Minimum star rating required for auto-publish:") }}</label>
                            <select class="form-select" name="min_rating" id="min_rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" @selected(($settings['publish.min_rating'] ?? 5) == $i)></option>
                                @endfor
                            </select>

                            <div class="form-text alert alert-secondary" id="ratingPublishMessage">

                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="profane_words">{{ trans('Block profane words') }}</label>
                            <input id="profane_words" class="form-control" name="profane_words" value="{{ implode(',', $profaneWords) }}" />
                        </div>

                        <div class="form-check mt-3">
                            <input type="checkbox" name="profane_send_email" id="profane_send_email" class="form-check-input"  value="1" @checked($settings['publish.profane_send_email'] ?? false)>
                            <label for="profane_send_email" class="form-check-label">
                                {{ trans("Send me an email when a review containing profane language is submitted") }}
                            </label>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ti ti-device-floppy"></i>
                                {{ trans('Save changes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/tagify/tagify.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/tagify/tagify.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        let publishInDays = {{ Js::from(setting('review.auto_publish_after_days', 0)) }};
        let $minRating = $('#min_rating');

        $minRating.select2({
            minimumResultsForSearch: -1,
            templateResult: formatState,
            templateSelection: formatState,
        });

        function formatState (state) {
            if (!state.id) {
                return state.text;
            }

            let icons = "";
            for (let i = 0; i < state.id; i++) {
                icons += '<i class="ti ti-star-filled text-warning"></i>';
            }

            return $(
                '<span>' + icons + " " + state.text + '</span>'
            );
        }

        let $ratingPublishMessage = $('#ratingPublishMessage');

        if (publishInDays === 0) {
            $ratingPublishMessage.hide()
        }else{
            $ratingPublishMessage.text(trans("Reviews with a rating lower than :rating stars will be auto-published :days days after submission unless you publish or reject them.", {
                rating: $minRating.val(), days: publishInDays
            }))

            $minRating.on('change', function () {
                $ratingPublishMessage.text(trans("Reviews with a rating lower than :rating stars will be auto-published :days days after submission unless you publish or reject them.", {rating: $(this).val(), days: publishInDays}))
            })
        }

        new Tagify(document.getElementById('profane_words'));
    </script>
@endpush
