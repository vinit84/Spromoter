@extends('layouts.user.app', [
    'title' => trans('Add Store'),
    'back' => route('user.stores.index'),
])

@section('content')
    <div class="row">
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
                            <button type="reset" class="btn btn-label-secondary waves-effect">
                                <i class="ti ti-refresh me-sm-1 me-0 ti-xs"></i>
                                {{ trans('Reset') }}
                            </button>

                            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
                                {{ trans('Submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
