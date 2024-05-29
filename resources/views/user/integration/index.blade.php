@extends('layouts.user.app', [
    'title' => trans('Integration'),
])

@section('content')
    <div class="row w-100 justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-center">
                        {{ trans('Great! You have successfully added your store.') }}
                    </h5>
                    <p class="text-center">{{ trans('Finish the integration by adding the following plugin to your website.') }}</p>

                    <div class="row justify-content-center">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="badge rounded-pill p-2 bg-label-primary mb-2">
                                        <i class="ti ti-brand-wordpress ti-sm"></i>
                                    </div>

                                    <h5 class="card-title">
                                        {{ trans('Manual Integration') }}
                                    </h5>

                                    <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#wordpressIntegrationModal">
                                        {{ trans('See Instructions') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="wordpressIntegrationModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wordpressIntegrationModalTitle">{{ trans('WordPress Integration') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion mt-3" id="accordionWithIcon">
                        <div class="card accordion-item">
                            <h2 class="accordion-header d-flex align-items-center">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#wpAccordionItemDownload" aria-expanded="false">
                                    <i class="ti ti-download ti-xs me-2"></i>
                                    {{ trans('Download :name Plugin', ['name' => config('app.name')]) }}
                                </button>
                            </h2>

                            <div id="wpAccordionItemDownload" class="accordion-collapse collapse" style="">
                                <div class="accordion-body py-3">
                                    <ol>
                                        <li>{{ trans('Download the plugin from the link below.') }}</li>
                                    </ol>
                                    <a href="{{ $pluginDownloadUrl }}" class="btn btn-outline-primary" download>
                                        <i class="ti ti-download"></i>
                                        {{ trans('Download') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card">
                            <h2 class="accordion-header d-flex align-items-center">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#wpAccordionItemInstall" aria-expanded="false">
                                    <i class="me-2 ti ti-upload ti-xs"></i>
                                    {{ trans('Install :name Plugin', ['name' => config('app.name')]) }}
                                </button>
                            </h2>
                            <div id="wpAccordionItemInstall" class="accordion-collapse collapse" style="">
                                <div class="accordion-body py-3">
                                    <ol>
                                        <li>
                                            {{ trans('Log in to your WordPress Admin, select "Plugins" , and then click "Add New Plugin"') }}
                                            <img src="{{ asset('assets/img/wp/integration-1.png') }}" alt="">
                                        </li>
                                        <li>
                                            {{ trans('Click "Upload Plugin" and then click "Choose File" to select the plugin zip file you downloaded. Click "Install Now".') }}
                                            <img src="{{ asset('assets/img/wp/integration-2.png') }}" class="w-100" alt="">
                                        </li>
                                        <li>
                                            {{ trans('Click "Activate Plugin".') }}
                                            <img src="{{ asset('assets/img/wp/integration-3.png') }}" class="w-100" alt="">
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card">
                            <h2 class="accordion-header d-flex align-items-center">
                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#wpAccordionItemSetup" aria-expanded="false">
                                    <i class="me-2 ti ti-plug-connected ti-xs"></i>
                                    {{ trans('Setup :name', ['name' => config('app.name')]) }}
                                </button>
                            </h2>
                            <div id="wpAccordionItemSetup" class="accordion-collapse collapse">
                                <div class="accordion-body py-3">
                                    <ol>
                                        <li>
                                            {!! trans('Navigate to :name in your WordPress admin panel sidebar.', ['name' => '<code>'. config('app.name'). '</code>']) !!}
                                            <img src="{{ asset('assets/img/wp/integration-4.png') }}" class="w-100" alt="">
                                        </li>
                                        <li>
                                            {{ trans('Login') }}
                                            <img src="{{ asset('assets/img/wp/integration-5.png') }}" class="w-100" alt="">
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">
                        {{ trans('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/page-misc.css') }}" />
@endpush
