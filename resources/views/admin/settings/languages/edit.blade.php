@extends('layouts.admin.app', [
    'title' => __('Edit Language'),
    'back' => route('admin.settings.languages.index'),
])

@section('content')
    <div class="row g-4 justify-content-center">
        <div class="col-12 col-xl-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.languages.update', $language->id) }}" class="ajaxform"
                          method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="name">{{ trans('Name') }}</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control"
                                value="{{ $language->name }}"
                                placeholder="{{ trans('Enter language name') }}"
                                required
                                autofocus />
                        </div>

                        <div class="form-group mb-3">
                            <label for="code">
                                {{ trans('Code') }}
                                <i
                                    class="fi-rr-messages-question"
                                    data-bs-toggle="tooltip"
                                    title="{{ trans('Code is not editable') }}"
                                />
                            </label>
                            <input
                                type="text"
                                name="code"
                                id="code"
                                class="form-control"
                                value="{{ $language->code }}"
                                readonly/>
                        </div>

                        <div class="form-group mb-3">
                            <label for="is_active">{{ trans('Active') }}</label>
                            <select name="is_active" id="is_active" class="form-select" data-control="select2" required>
                                <option value="1" @selected($language->is_active == 1)>{{ trans('Active') }}</option>
                                <option value="0" @selected($language->is_active == 0)>{{ trans('Inactive') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="is_rtl">{{ trans('Is RTL') }}</label>
                            <select name="is_rtl" id="is_rtl" class="form-select" data-control="select2" required>
                                <option value="0" @selected($language->is_rtl == 0)>{{ trans('No') }}</option>
                                <option value="1" @selected($language->is_rtl == 1)>{{ trans('Yes') }}</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">
                            <i class="ti ti-device-floppy me-0 me-sm-1 ti-xs"></i>
                            {{ trans('Update Changes') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
