@extends('layouts.admin.app', [
    'title' => trans('Edit Store'),
    'back' => route('admin.stores.index'),
])

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.stores.update', $store) }}" method="POST" class="ajaxform">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="category">{{ trans('Category') }}</label>
                            <select name="category" id="category" class="form-select"
                                    data-control="select2" data-placeholder="{{ trans('Select Category') }}" required>
                                <option></option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($store->store_category_id == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">{{ trans('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="ex. Example Store" value="{{ $store->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="url">{{ trans('Website URL') }}</label>
                            <input type="url" name="url" id="url" class="form-control" placeholder="ex. https://example.com" value="{{ $store->url }}" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-label-secondary waves-effect">
                                <i class="ti ti-refresh me-sm-1 me-0 ti-xs"></i>
                                {{ trans('Reset') }}
                            </button>

                            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
                                <i class="ti ti-device-floppy me-sm-1 me-0 ti-xs"></i>
                                {{ trans('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('pageScripts')
    <script>
        $('#customer').select2({
            ajax: {
                url: route('admin.stores.customers'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    }
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                        pagination: {
                            more: data.current_page < data.last_page
                        }
                    };
                },
                cache: true
            },
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });
    </script>
@endpush
