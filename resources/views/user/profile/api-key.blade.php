@extends('layouts.user.app', [
    'title' => trans('API Keys')
])

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('user.profile.partials.navbar')

            <div class="card mb-4">
                <h5 class="card-header">{{ trans('Create an API key') }}</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="app_id">{{ trans('APP ID') }}</label>
                            <div class="input-group">
                                <input type="text" id="app_id" class="form-control" value="{{ activeStore()->uuid }}" readonly>
                                <span class="input-group-text cursor-pointer" id="app_id_text" data-clipboard-text="{{ activeStore()->uuid }}">
                                    <i class="ti ti-clipboard"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create an API key -->
            <div class="card mb-4">
                <h5 class="card-header">{{ trans('Create an API key') }}</h5>
                <div class="row">
                    <div class="col-md-5">
                        <div class="card-body">
                            <form action="{{ route('user.profile.api-keys.store') }}" method="POST" class="ajaxform" id="apiKeyForm">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-12">
                                        <label for="name" class="form-label">{{ trans('Name the API key') }}</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            name="name"
                                            placeholder="ex. Key 1"
                                            maxlength="100"
                                            required
                                        />
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary me-2 d-grid w-100">{{ trans('Create Key') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Create an API key -->

            <!-- API Key List & Access -->
            <div class="card mb-4">
                <h5 class="card-header">{{ trans('API Key List') }}</h5>
                <div class="card-body">
                    <p>
                        {{ trans('An API key is a simple encrypted string that identifies an application without any principal.') }}
                    </p>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach($apiKeys as $apiKey)
                                <div @class(['bg-lighter rounded p-3 position-relative', 'mb-3' => !$loop->last])>
                                    <div class="dropdown api-key-actions">
                                        <a class="btn dropdown-toggle text-muted hide-arrow p-0" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical ti-sm"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="javascript:;" class="dropdown-item"><i class="ti ti-pencil me-2"></i>Edit</a>
                                            <a href="javascript:;" class="dropdown-item"><i class="ti ti-trash me-2"></i>Delete</a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="mb-0 me-3">{{ $apiKey->name }}</h4>
                                    </div>

                                    <span class="text-muted">{{ trans('Created on :date_time', ['date_time' => dateTimeFormat($apiKey->created_at)]) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!--/ API Key List & Access -->
        </div>
    </div>
@endsection

@section('modals')
    <!-- Enable OTP Modal -->
    <div class="modal fade" id="apiKeyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">{{ trans('API Key Created Successfully') }}</h3>
                    </div>
                    <p>{{ trans('Please copy the API key below and store it in a safe place. You will not be able to see it again.') }}</p>
                    <form id="enableOTPForm" class="row g-3" onsubmit="return false">
                        <div class="col-12">
                            <label class="form-label" for="modalEnableOTPPhone">{{ trans('API Key') }}</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="modalApiKey"
                                    class="form-control"
                                    value=""
                                    readonly
                                />
                                <span class="input-group-text cursor-pointer" data-clipboard-target="modalApiKey">
                                    <i class="ti ti-clipboard"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button
                                type="reset"
                                class="btn btn-label-secondary"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Enable OTP Modal -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/page-api-keys.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        $(function() {
            // Show API Key Modal
            $('#apiKeyForm').on('formSubmitSuccess', function($from, response) {
                $('#modalApiKey').val(response.data.token);

                $('#apiKeyModal').modal('show');

                const clipboard = new ClipboardJS('.input-group-text', {
                    target: function(trigger) {
                        return trigger.previousElementSibling;
                    },
                    container: document.getElementById('apiKeyModal'),
                }).on('success', function(e) {
                    e.clearSelection();

                    flash('success', trans('API Key copied to clipboard'));
                }).on('error', function (e){
                    flash('error', trans('Failed to copy API Key to clipboard'));
                })

                $('#apiKeyModal').on('hidden.bs.modal', function () {
                    clipboard.destroy();
                });
            });

            new ClipboardJS('#app_id_text').on('success', function(e) {
                e.clearSelection();

                flash('success', trans('APP ID copied to clipboard'));
            }).on('error', function (e){
                flash('error', trans('Failed to copy APP ID to clipboard'));
            })
        });
    </script>
@endpush
