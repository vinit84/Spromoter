@extends('layouts.user.blank', [
    'title' => trans('Stores'),
])

@section('content')
    <div class="row w-100 justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('user.stores.store') }}" method="POST" class="ajaxform">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="store_category_id">{{ trans('Category') }}</label>
                            <select name="store_category_id" id="store_category_id" class="form-select"
                                    data-control="select2" data-placeholder="{{ trans('Select Category') }}" required>
                                <option></option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">{{ trans('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="ex. Example Store" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="url">{{ trans('Website URL') }}</label>
                            <input type="url" name="url" id="url" class="form-control" placeholder="ex. https://example.com" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary waves-effect waves-light w-100">
                                <i class="ti ti-device-floppy"></i>
                                {{ trans('Submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/page-misc.css') }}" />
@endpush
