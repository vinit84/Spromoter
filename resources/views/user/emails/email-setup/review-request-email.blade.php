@extends('layouts.user.app', [
    'title' => trans('Review Request Email'),
])

@section('content')
    <div class="row g-4">
        <div class="col-md-4">
            <form action="{{ route('user.emails.email-setup.review-request-email-update') }}" method="POST" class="ajaxform" id="reviewRequestForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="days">
                                {{ trans('Send Review Request Email After') }}
                                <i class="ti ti-info-circle cursor-pointer" data-bs-toggle="tooltip" title="{{ trans('Send a review request email to your customers after they have received their order.') }}"></i>
                            </label>
                            <div class="input-group">
                                <input type="number" min="1" name="days" id="days" class="form-control" value="{{ $settings['emails.review_request_email_days'] ?? null }}" required>
                                <span class="input-group-text">
                                    {{ trans('Days') }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject">{{ trans('Subject') }}</label>
                            <input type="text" name="subject" id="subject" class="form-control" value="{{ $settings['emails.review_request_email_subject'] ?? null }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="body">{{ trans('Body') }}</label>
                            <div id="body"></div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button class="btn btn-light btn-sm shortcode-btn" type="button" data-shortcode="{customer.name}">
                                {customer.name}
                            </button>
                            <button class="btn btn-light btn-sm shortcode-btn" type="button" data-shortcode="{store.name}">
                                {store.name}
                            </button>
                            <button class="btn btn-light btn-sm shortcode-btn" type="button" data-shortcode="{product.name}">
                                {product.name}
                            </button>
                        </div>
                        <div>
                            <small>Tips: Place the cursor where you want to insert the shortcode.</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary waves-effect mt-3">
                    <i class="ti ti-device-floppy me-0 me-sm-1 ti-xs"></i>
                    {{ trans('Save Changes') }}
                </button>
            </form>
        </div>

        <div class="col-md-8">
            <div class="card" id="previewCard">
                <div class="card-header header-elements">
                    <span class="me-2">{{ trans('Live Preview') }}</span>

                    <div class="card-header-elements ms-auto">
                        {{--<button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#test-email-modal">
                            <span class="tf-icon ti ti-send ti-xs me-1"></span>
                            {{ trans('Test') }}
                        </button>--}}

                        <div class="modal modal-transparent fade" id="test-email-modal" tabindex="-1" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{ route('user.emails.email-setup.send-test-email') }}" method="post" class="ajaxform" id="testEmailForm">
                                            @csrf
                                            <div class="input-group input-group-lg mb-3">
                                                <input type="text" name="email" class="form-control bg-white border-0" value="{{ auth()->user()->email }}" placeholder="{{ trans('Your email') }}" aria-describedby="subscribe" aria-label="Email">
                                                <button class="btn btn-primary waves-effect waves-light" type="submit" id="subscribe">
                                                    <span class="tf-icon ti ti-send ti-xs me-1"></span>
                                                    {{ trans('Send') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <iframe id="previewIframe" srcdoc="" frameborder="0" width="100%" style="height: 400px"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/spinkit/spinkit.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/quill/editor.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/plugins/quill/quill.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        let emailBody = '{{ strip_tags($settings['emails.review_request_email_body'] ?? null) }}'
    </script>
    <script src="{{ asset('assets/js/page/user/emails/review-request-email.js') }}"></script>
@endpush
