@extends('layouts.admin.app', [
    'title' => __('Create Language'),
    'back' => route('admin.settings.languages.index')
])

@section('content')
    <div class="row justify-content-center g-4">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.languages.store') }}" class="ajaxform" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{ trans('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="{{ trans('Enter language name') }}" required autofocus>
                        </div>

                        <div class="form-group mb-3">
                            <label for="code">{{ trans('Code') }}</label>
                            <input type="text" name="code" id="code" class="form-control" placeholder="{{ trans('Enter language code') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="is_active">{{ trans('Active') }}</label>
                            <select name="is_active" id="is_active" class="form-select" data-control="select2" required>
                                <option value="1">{{ trans('Active') }}</option>
                                <option value="0">{{ trans('Inactive') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="is_rtl">{{ trans('Is RTL') }}</label>
                            <select name="is_rtl" id="is_rtl" class="form-select" data-control="select2" required>
                                <option value="0">{{ trans('No') }}</option>
                                <option value="1">{{ trans('Yes') }}</option>
                            </select>
                        </div>

                        <button class="btn btn-primary mt-2 w-100" type="submit">
                            <i class="ti ti-device-floppy me-0 me-sm-1 ti-xs"></i>
                            {{ trans('Save') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
