@extends('layouts.admin.app', [
    'title' => trans('Import Reviews'),
    'back' => route('admin.stores.show', $store),
])

@section('description')
    {{ trans('Import your existing product reviews to :name and showcase them on your store.', ['name' => config('app.name')]) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="">
                        <div class="mb-3">
                            <label class="mb-2" for="provider">{{ trans("Which review's provider do you want to import from?") }}</label>
                            <select name="provider" id="provider" class="form-select" data-control="select2"
                                    data-placeholder="{{ trans('Select provider') }}">
                                <option value=""></option>
{{--                                <option value="shopify" data-tab="#navs-shopify">{{ trans('Shopify Reviews') }}</option>--}}
                                <option value="loox" data-tab="#navs-loox">{{ trans('Loox') }}</option>
{{--                                <option value="stamped" data-tab="#navs-stamped">{{ trans('Stamped.io') }}</option>--}}
{{--                                <option value="okendo" data-tab="#navs-okendo">{{ trans('Okendo') }}</option>--}}
{{--                                <option value="junip" data-tab="#navs-junip">{{ trans('Junip') }}</option>--}}
{{--                                <option value="feefo" data-tab="#navs-feefo">{{ trans('Feefo') }}</option>--}}
                                <option value="yotpo" data-tab="#navs-yotpo">{{ trans('Yotpo') }}</option>
                                <option value="judgeme" data-tab="#navs-judgeme">{{ trans('Judge.me') }}</option>
                                <option value="other" data-tab="#navs-other">{{ trans('Other') }}</option>
                            </select>
                        </div>

                        <input type="file" name="file" id="file" hidden>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade" id="navs-shopify">
                            <h5 class="card-title">
                                {{ trans('How to import reviews from :provider', ['provider' => 'Shopify Reviews']) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Export your reviews from :provider to a CSV file. :link', [
                                            'provider' => 'Shopify Reviews',
                                            'link' => '<a href="#" target="_blank">'.trans('See instructions').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-loox">
                            <h5 class="card-title">
                                {{ trans('How to import reviews from :provider', ['provider' => 'Loox']) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Export your reviews from :provider to a CSV file. :link', [
                                            'provider' => 'Loox',
                                            'link' => '<a href="https://help.loox.io/article/21-how-do-i-export-my-reviews" target="_blank">'.trans('See instructions').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-stamped">
                            <h5 class="card-title">
                                {{ trans('How to import reviews from :provider', ['provider' => 'Stamped']) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Export your reviews from :provider to a CSV file. :link', [
                                            'provider' => 'Stamped',
                                            'link' => '<a href="#" target="_blank">'.trans('See instructions').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-okendo">
                            <h5 class="card-title">
                                {{ trans('How to import reviews from :provider', ['provider' => 'Okendo']) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Export your reviews from :provider to a CSV file. :link', [
                                            'provider' => 'Okendo',
                                            'link' => '<a href="#" target="_blank">'.trans('See instructions').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-junip">
                            <h5 class="card-title">
                                {{ trans('How to import reviews from :provider', ['provider' => 'Junip']) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Export your reviews from :provider to a CSV file. :link', [
                                            'provider' => 'Junip',
                                            'link' => '<a href="#" target="_blank">'.trans('See instructions').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-feefo">
                            <h5 class="card-title">
                                {{ trans('How to import reviews from :provider', ['provider' => 'Feefo']) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Export your reviews from :provider to a CSV file. :link', [
                                            'provider' => 'Feefo',
                                            'link' => '<a href="#" target="_blank">'.trans('See instructions').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-judgeme">
                            <h5 class="card-title">
                                {{ trans('How to import reviews from :provider', ['provider' => 'Judge.me']) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Export your reviews from :provider to a CSV file. :link', [
                                            'provider' => 'Judge.me',
                                            'link' => '<a href="https://help.judge.me/en/articles/8236266-exporting-reviews" target="_blank">'.trans('See instructions').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-yotpo">
                            <h5 class="card-title">
                                {{ trans('How to import reviews from :provider', ['provider' => 'Yotpo']) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Export your reviews from :provider to a CSV file. :link', [
                                            'provider' => 'Yotpo',
                                            'link' => '<a href="https://support.yotpo.com/docs/exporting-reviews-from-yotpo" target="_blank">'.trans('See instructions').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-other">
                            <h5 class="card-title">
                                {{ trans('Importing reviews using :name template', ['name' => config('app.name')]) }}
                            </h5>
                            <div class="card-text">
                                <ol>
                                    <li>
                                        {!! trans('Download the CSV file template. :link', [
                                            'link' => '<a href="'.asset('assets/files/Import Review Template.csv').'" target="_blank">'.trans('Download').'<i class="ti ti-download"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>
                                        {!! trans('Add your reviews to the file. :link', [
                                            'link' => '<a href="#" target="_blank">'.trans('Prepare your file').'<i class="ti ti-external-link"></i></a>'
                                        ]) !!}
                                    </li>
                                    <li>{{ trans('Upload the file to :name', ['name' => config('app.name')]) }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">{{ trans('Hurrah! Your file is importable.') }}</h3>
                        <p class="text-muted">
                            You also need to select a method by which the proxy authenticates to the directory serve.
                        </p>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content ps-3" for="customRadioTemp2">
                                    <span class="d-flex align-items-start">
                                        <i class="ti ti-file ti-xl me-3"></i>
                                        <span>
                                            <span class="custom-option-header">
                                                <span class="h4 mb-2" id="modalFileName"></span>
                                            </span>
                                            <span class="custom-option-body">
                                                <span class="mb-0" id="modalFileDescription"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <a class="btn btn-primary me-sm-3 me-1 ajax-link" id="confirmImportButton">
                                <i class="ti ti-file-import"></i>
                                {{ trans('Submit') }}
                            </a>
                            <button
                                type="reset"
                                class="btn btn-label-secondary"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                {{ trans('Cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/filepond.min.css') }}"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        let store = {{ Js::from($store->uuid) }}
    </script>
    <script>
        $(function () {
            FilePond.registerPlugin(FilePondPluginFileValidateSize, FilePondPluginFileValidateType)

            let serverResponse = null;

            const pond = FilePond.create(document.getElementById('file'), {
                credits: false,
                acceptedFileTypes: ['text/csv', 'application/wps-office.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
                allowFileTypeValidation: true,
                allowMultiple: false,
                allowDrop: false,
                allowBrowse: false,
                maxFileSize: '800KB',
                maxFiles: 1,
                labelIdle: trans('Drag & Drop your file or :upload', {
                    'upload': '<span class="filepond--label-action">'+ trans('Browse') +'</span>'
                }),
                server: {
                    process: {
                        url: route('admin.stores.reviews.import.store', store),
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                        },
                        ondata: (formData) => {
                            formData.append('provider', $('#provider').val());
                            return formData;
                        },
                        onerror: (response) => {
                            let error = JSON.parse(response);
                            serverResponse = error;

                            flash('error', error.message);
                        },
                    },
                    revert: {
                        url: route('admin.stores.reviews.import.delete-temporary-file', store),
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                        }
                    }
                },
                labelFileProcessingError: (error) => {
                   return serverResponse.message;
                },
            });

            $('#provider').on('change.select2', function (e) {
                var provider = $(this).val();

                if (provider) {
                    // Activate the tab
                    let tabId = $(this).find('option:selected').data('tab')
                    $('.tab-pane').removeClass('active show')
                    $(tabId).addClass('active show')

                    pond.setOptions({
                        allowDrop: true,
                        allowBrowse: true,
                    });
                }
            });

            const confirmationModal = new bootstrap.Modal('#confirmationModal', {
                keyboard: false,
                backdrop: 'static',
            });

            // Pond on success
            pond.on('processfile', (error, file) => {
                if (error) {
                    return;
                }

                const response = JSON.parse(file.serverId);

                if (response.status === 'success') {
                    $('#modalFileName').text(response.data.name);
                    $('#modalFileDescription').text(response.data.description);
                    $('#confirmImportButton').attr('href', route('admin.stores.reviews.import.confirm', {store, file: response.data.folder, provider: response.data.provider}))
                    confirmationModal.show();
                } else {
                    $('#modalFileName').text('');
                    $('#modalFileDescription').text('');
                }
            });

            $('#confirmImportButton').on('ajaxLinkSuccess', function (e, data) {
                confirmationModal.hide();
                pond.removeFiles();
            });

            document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function (e) {
                pond.removeFiles();
            });
        });
    </script>
@endpush
