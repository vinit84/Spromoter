@extends('layouts.admin.app', [
    'title' => trans('Edit Page'),
    'back' => route('admin.frontend.pages.index'),
])

@section('content')
    <form action="{{ route('admin.frontend.pages.update', $page) }}" method="POST" class="row g-3 ajaxform">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <label for="body">{{ trans('Body') }}</label>
                        <textarea id="body" name="body">{{ $page->body }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title">{{ trans('Title') }}</label>
                            <input type="text" name="title" id="title" value="{{ $page->title }}"
                                   placeholder="{{ trans('Enter page title') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="slug">{{ trans('Slug') }}</label>
                            <input type="text" name="slug" id="slug" value="{{ $page->slug }}"
                                   placeholder="{{ trans('Enter page slug') }}" class="form-control"
                                   required
                                @disabled($page->is_system)
                                @readonly($page->is_system)
                            >
                        </div>
                        <div class="mb-3">
                            <label for="is_active">{{ trans('Is Active') }}</label>
                            <select name="is_active" id="is_active" class="form-select" data-control="select2" required>
                                <option value="1" @selected($page->is_active)>{{ trans('Yes') }}</option>
                                <option value="0" @selected(!$page->is_active)>{{ trans('No') }}</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="reset" class="btn btn-label-secondary">
                                <i class="ti ti-refresh me-0 me-sm-1 ti-xs"></i>
                                {{ trans('Reset') }}
                            </button>

                            <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                <i class="ti ti-device-floppy me-0 me-sm-1 ti-xs"></i>
                                {{ trans('Update Changes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/plugins/ckeditor5/ckeditor.js') }}"></script>
@endpush

@push('pageScripts')
    <script>
        let bodyEditor = ClassicEditor
            .create(document.querySelector('#body'))
            .catch(error => {
                flash('error', error)
            });

        // Set the body value on editor change
        bodyEditor.then(editor => {
            editor.model.document.on('change:data', () => {
                $('#body').val(editor.getData());
            });
        });

        @if(!$page->is_system)
        let isManually = false;

        $('#title').on('keyup', function () {
            if (!isManually) {
                generateSlug();
            }
        });

        $('#slug').on('keyup', function () {
            isManually = true;
        }).on('input', function () {
            if ($(this).val() == '') {
                isManually = false;
                generateSlug();
            }
        });

        function generateSlug() {
            let title = $('#title').val();
            let slug = title.toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '')
                .replace(/&+/g, '-')
                .replace(/--+/g, '-');

            $('#slug').val(slug);
        }
        @endif
    </script>
@endpush
