@extends('layouts.admin.app', [
    'title' => trans('Support Tickets Replies'),
    'back' => [
        'can' => 'support-ticket-read',
        'url' => route('admin.support-tickets.index')
    ],
])

@section('content')
    <div class="row g-4 overflow-hidden">
        @if($supportTicket->status == 'open')
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-elements">
                        <span class="me-2">{{ trans('Reply') }}</span>
                        <div class="card-header-elements ms-auto">
                            <div class="btn-group btn-group-sm">
                                <a
                                    href="{{ route('admin.support-tickets.change-status', $supportTicket) }}"
                                    class="btn btn-danger confirm-action"
                                    title="{{ trans('Mark as resolved') }}"
                                    data-method="PUT"
                                    id="markAsResolvedBtn"
                                >
                                    <i class="ti ti-circle-check"></i>
                                </a>
                                <button type="button" class="btn btn-primary waves-effect waves-light" id="submitReplyBtn" disabled="disabled">
                                    <i class="ti ti-device-floppy"></i>
                                    {{ trans('Submit Reply') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="replyEditor">

                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-12">
            <ul class="timeline timeline-center mt-5" id="replies">
                @foreach($replies as $reply)
                    <li @class(['timeline-item', $reply->is_customer ? 'timeline-item-left' : 'timeline-item-right'])>
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
                            script: 'super'
                        },
                        {
                            script: 'sub'
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
                        'code-block'
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
                    ['link', 'image', 'video'],
                    ['clean']
                ];
                const replyEditor = new Quill('#replyEditor', {
                    bounds: '#replyEditor',
                    placeholder: 'Type Something...',
                    modules: {
                        formula: true,
                        toolbar: replyToolbar
                    },
                    theme: 'snow'
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

                $('#submitReplyBtn').on('click', function () {
                    let message = replyEditor.root.innerHTML;

                    if (message.replace(/(<([^>]+)>)/gi, "") === '') {
                        flash('error', trans('Please type something.'));
                        return false;
                    }

                    $.ajax({
                        url: route('admin.support-tickets.reply', {supportTicket}),
                        method: 'POST',
                        data: {
                            message,
                            last_reply
                        },
                        beforeSend: function () {
                            blockCard($('#replyEditor').closest('.card'));
                        },
                        success: function (response) {
                            flash('success', response.message);
                            replyEditor.root.innerHTML = '';
                            last_reply = response.data.reply.id;
                            renderReply(response.data.replies)
                        },
                        error: function (xhr) {
                            flash('error', xhr.responseJSON.message || trans('Something went wrong!'));
                        },
                        complete: function () {
                            $('#replyEditor').closest('.card').unblock();
                        }
                    })
                })
            }

            function renderReply(replies){
                $(replies).each(function (index, reply){
                    $('#replies').prepend(`
                        <li class="timeline-item ${reply.is_customer ? 'timeline-item-left' : 'timeline-item-right'}">
                        <span
                        class="timeline-indicator timeline-indicator-primary"
                        data-aos="zoom-in"
                        data-aos-delay="200"
                        >
                        <div class="avatar avatar-lg">
                        <img class="rounded-circle" src="${reply.avatar}"
                        alt="${reply.customer}"/>
                        </div>
                        </span>
                        <div class="timeline-event card p-0" data-aos="fade-right">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="card-title mb-0">${reply.customer}</h6>
                        </div>
                        <div class="card-body">
                        <p class="mb-2">
                        ${reply.message}
                        </p>
                        </div>
                        <div class="timeline-event-time">
                        ${reply.created_at}
                        </div>
                        </div>
                        </li>
                    `)
                })
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
