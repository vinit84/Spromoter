@extends('layouts.admin.app', [
    'title' => trans(':language Translations', ['language' => trans($language->name)]),
    'back' => route('admin.settings.languages.index'),
])

@section('content')
    <form action="{{ route('admin.settings.languages.translations.update', $language->id) }}" method="post" class="ajaxform">
        @method('PUT')
        <div class="card">
            <div class="card-header mb-0 pb-0">
                <div class="alert alert-danger mb-0">
                    {{ trans('Note: Please do not translate ":attribute" and ":value" as they are used in validation messages.') }}
                    <br>
                    {{ trans('Example: The :attribute must be :value. (Your :attribute translation like this :value.)') }}
                </div>
            </div>
            <hr>
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table" id="translations">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('Key') }}</th>
                        <th>{{ trans('Value') }}</th>
                    </tr>
                    </thead>
                    <tbody data-repeater-list="new-phrases">
                    @foreach($translations as $key => $value)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td class="w-50">{{ $key }}</td>
                            <td class="w-50">
                                <input type="text" name="translations[{{ $key }}]" class="form-control" value="{{ $value }}" aria-label="{{ $value }}" required>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-5 repeater">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title">
                    {{ trans('Add New Phrases') }}
                </h5>

                <button type="button" class="btn btn-primary" data-repeater-create data-bs-toggle="tooltip" title="{{ trans('Click to add new phrase') }}">
                    <i class="ti ti-plus"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    {{ trans('After adding new phrases you cannot remove it. So please be careful.') }}
                </div>

                <table class="table table-bordered table-striped" data-repeater-list="new-phrases">
                    <thead>
                    <tr>
                        <th>{{ trans('Key') }}</th>
                        <th>{{ trans('Value') }}</th>
                        <th>{{ trans('Action') }}</th>
                    </tr>
                    </thead>
                    <tr data-repeater-item>
                        <td>
                            <input type="text" name="key" class="form-control" placeholder="{{ trans('Enter translation key') }}" aria-label="" required>
                        </td>
                        <td>
                            <input type="text" name="value" class="form-control" placeholder="{{ trans('Enter translated value') }}" aria-label="" required>
                        </td>
                        <td width="5">
                            <button type="button" class="btn btn-danger" data-repeater-delete data-bs-toggle="tooltip" title="{{ trans('Click to delete') }}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                </table>

                <button class="btn btn-primary float-right mt-3" type="submit">
                    <i class="fi-rr-file"></i>
                    {{ trans('Update') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-repeater/jquery.repeater.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#translations').dataTable({
                paging: false,
                info: false,
                stateSave: true,
                columnDefs: [
                    {
                        "orderable": false,
                        "searchable": false,
                        "targets": 2
                    }
                ],
                language: {
                    search: "",
                    searchPlaceholder: trans('Search')
                },
                initComplete: function () {
                    $('.dataTables_filter input').removeClass('form-control-sm');
                }
            });

            $('.repeater').repeater({
                initEmpty: true,
                defaultValues: {
                    'key': '',
                    'value': ''
                },
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    if(confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function (setIndexes) {
                    // $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            })
        })
    </script>
@endpush
