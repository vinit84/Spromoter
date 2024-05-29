@extends('layouts.user.app', [
    'title' => trans('Support Tickets Replies'),
    'back' => route('user.support-tickets.index'),
])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('user.support-tickets.store') }}" method="POST" class="ajaxform" id="messageForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4 col-md-offset-3">
                                <label for="department">
                                    {{ trans('Department') }}
                                </label>
                                <select name="department" id="department" class="form-select" data-control="select2" required>
                                    <option value="sales">{{ trans('Sales') }}</option>
                                    <option value="technical">{{ trans('Technical') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-md-offset-3">
                                <label for="priority">
                                    {{ trans('Priority') }}
                                </label>
                                <select name="priority" id="priority" class="form-select" data-control="select2" required>
                                    <option value="low">{{ trans('Low') }}</option>
                                    <option value="medium">{{ trans('Medium') }}</option>
                                    <option value="high">{{ trans('High') }}</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="subject">{{ trans('Subject') }}</label>
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="{{ trans('Enter subject') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="message">{{ trans('Message') }}</label>
                                <div id="message"></div>
                            </div>
                        </div>

                        <button class="btn btn-primary" type="submit" id="ticketSubmitBtn" disabled="disabled">
                            <i class="ti ti-device-floppy me-1"></i>
                            {{ trans("Submit") }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
        (function () {
            const messageToolbar = [
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
            const messageEditor = new Quill('#message', {
                bounds: '#message',
                placeholder: trans("Enter message..."),
                modules: {
                    formula: true,
                    toolbar: messageToolbar
                },
                theme: 'snow'
            });

            // Listen typing
            messageEditor.on('text-change', function () {
                let message = messageEditor.root.innerHTML;

                if (message.replace(/(<([^>]+)>)/gi, "") === '') {
                    $('#ticketSubmitBtn').attr('disabled', true);
                } else {
                    $('#ticketSubmitBtn').attr('disabled', false);
                }
            });

            $("#messageForm").on('ajaxFormSubmitBefore', function (e, formData) {
                formData.push({
                    name: 'message',
                    value: messageEditor.root.innerHTML
                })
            })

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
