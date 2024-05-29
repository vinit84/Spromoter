@extends('layouts.user.app', [
    'title' => trans('Support Tickets Replies'),
    'back' => route('user.support-tickets.index')
])

@section('content')
    <div class="row g-4 overflow-hidden">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="card-title">
                         {{ $supportTicket->subject }}
                    </h5>
                    <div class="card-header-elements ms-auto">
                        <div class="btn-group btn-group-sm">
                            <a
                                href="{{ route('user.support-tickets.change-status', $supportTicket) }}"
                                @class(['btn confirm-action', $supportTicket->status == 'open' ? 'btn-danger' : 'btn-success'])
                                title="{{ $supportTicket->status == 'open' ? trans('Mark as resolved') : trans('Mark as open') }}"
                                data-method="PUT"
                                id="markAsResolvedBtn"
                                data-bs-toggle="tooltip"
                            >
                                <i @class(['ti', $supportTicket->status == 'open' ? 'ti-circle-check' : 'ti-circle-x'])></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        {!! $supportTicket->message !!}
                    </p>
                </div>
            </div>
        </div>
        @if($supportTicket->status == 'open')
            <div class="col-6">
                <div class="card">
                    <div class="card-header header-elements">
                        <span class="me-2">{{ trans('Reply') }}</span>
                        <div class="card-header-elements ms-auto">
                            <div class="btn-group btn-group-sm">
                                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitReplyBtn" form="replyForm" disabled="disabled">
                                    <i class="ti ti-device-floppy"></i>
                                    {{ trans('Submit Reply') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.support-tickets.reply', $supportTicket) }}" method="POST" class="ajaxform" id="replyForm">
                            @csrf
                            <div id="replyEditor">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-12">
            <ul class="timeline timeline-center mt-5" id="replies">
                @foreach($replies as $reply)
                    <li @class(['timeline-item', $reply->is_customer ? 'timeline-item-right' : 'timeline-item-left'])>
                      <span
                          class="timeline-indicator timeline-indicator-primary"
                          data-aos="zoom-in"
                          data-aos-delay="200"
                      >
                          <div class="avatar avatar-lg">
                            <img class="rounded-circle" src="{{ $reply->user->avatar }}"
                                 alt="{{ $reply->user->name }}"/>
                        </div>
                      </span>
                        <div class="timeline-event card p-0" data-aos="fade-right">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="card-title mb-0">{{ $reply->user->name }}</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    {!! $reply->message !!}
                                </p>
                            </div>
                            <div class="timeline-event-time">
                                {{ $reply->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        @if($replies->hasMorePages())
            <div class="col-12 text-center">
                <button class="btn btn-sm btn-primary" id="loadMore">
                    <i class="ti ti-refresh ti-xxs me-1"></i>
                    {{ trans('Load More') }}
                </button>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/quill/katex.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/plugins/quill/editor.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/plugins/animate-on-scroll/animate-on-scroll.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/plugins/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/plugins/animate-on-scroll/animate-on-scroll.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        let supportTicket = @js($supportTicket->id);
        let last_reply = @js($replies->first()?->id);
    </script>
    <script>
        (function () {
            const exists = document.getElementById('replyEditor');

            if (exists) {
                const replyToolbar = [
                    ['bold', 'italic', 'underline', 'strike'],
                    [
                        {
                            color: []
                        },
                        {
                            background: []
                        }
                    ],
                    [
                        {
                            header: '1'
                        },
                        {
                            header: '2'
                        },
                        'blockquote',
                    ],
                    [
                        {
                            list: 'ordered'
                        },
                        {
                            list: 'bullet'
                        },
                        {
                            indent: '-1'
                        },
                        {
                            indent: '+1'
                        }
                    ],
                    [{direction: 'ltr'}],
                    ['link', 'image'],
                ];
                const replyEditor = new Quill('#replyEditor', {
                    bounds: '#replyEditor',
                    placeholder: 'Type Something...',
                    modules: {
                        formula: true,
                        toolbar: replyToolbar
                    },
                    theme: 'snow',
                });

                // Listen typing
                replyEditor.on('text-change', function () {
                    let message = replyEditor.root.innerHTML;

                    if (message.replace(/(<([^>]+)>)/gi, "") === '') {
                        $('#submitReplyBtn').attr('disabled', true);
                    } else {
                        $('#submitReplyBtn').attr('disabled', false);
                    }
                });

                $('#replyForm').on('ajaxFormSubmitBefore', function (e, formData) {
                    formData.push({
                        name: 'message',
                        value: replyEditor.root.innerHTML
                    });
                });
            }

            $(document).on('confirmActionSuccess', function (el, response) {
                storeLocalMessage(response.status, response.message, response.hasMessage);

                window.location.reload();
            });

            // Init Animation on scroll
            AOS.init({
                disable: function () {
                    const maxWidth = 1024;
                    return window.innerWidth < maxWidth;
                },
                once: true
            });
        })();
    </script>
@endpush
