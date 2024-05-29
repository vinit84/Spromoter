@extends('layouts.admin.app', [
    'title' => trans('Edit User'),
    'back' => route('admin.settings.users.index'),
])

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="row g-3 ajaxform">
                @method('PUT')
                @csrf
                <div class="col-12 col-md-6">
                    <label class="form-label" for="first_name">{{ trans('First Name') }}</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        class="form-control"
                        placeholder="Ex. John"
                        value="{{ $customer->first_name }}"
                        required
                    />
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="last_name">{{ trans('Last Name') }}</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        class="form-control"
                        placeholder="Ex. Doe"
                        value="{{ $customer->last_name }}"
                        required
                    />
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="username">{{ trans('Username') }}</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Ex. johndoe"
                        value="{{ $customer->username }}"
                        required
                    />
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="email">{{ trans('Email') }}</label>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="Ex. example@domain.com"
                        value="{{ $customer->email }}"
                        required
                    />
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="phone">{{ trans('Phone Number') }}</label>
                    <div class="input-group">
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="form-control phone-number-mask"
                            placeholder="202 555 0111"
                            value="{{ $customer->phone }}"
                        />
                        <input type="hidden" name="phone_code" id="phone_code" value="{{ $customer->phone_code }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="country">{{ trans('Country') }}</label>
                    <select
                        id="country"
                        name="country"
                        class="form-select"
                        data-control="select2"
                        data-placeholder="{{ trans('Select Country') }}"
                    >
                        <option value=""></option>
                        @foreach($countries as $country)
                            <option value="{{ $country['name'] }}" @selected($country['name'] == $customer->country)>
                                {{ $country['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label" for="status">{{ trans('Status') }}</label>
                    <select
                        id="status"
                        name="status"
                        class="form-select"
                        data-control="select2"
                        required
                    >
                        <option value="active" @selected($customer->status == 'active')>{{ trans('Active') }}</option>
                        <option value="suspend" @selected($customer->status == 'suspend')>{{ trans('Suspend') }}</option>
                    </select>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">
                        <i class="ti ti-user-edit me-0 me-sm-1 ti-xs"></i>
                        {{ trans('Update User') }}
                    </button>
                    <button type="reset" class="btn btn-label-secondary">
                        <i class="ti ti-refresh me-0 me-sm-1 ti-xs"></i>
                        {{ trans('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/intl-tel-input/intlTelInput.min.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/intl-tel-input/intlTelInput-jquery.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/plugins/cleavejs/cleave-phone.js') }}"></script>
    <script>
        $(function () {


            $('#phone').intlTelInput({
                initialCountry: 'auto',
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {}, "jsonp").always(function (resp) {
                        let countryCode = (resp && resp.country) ? resp.country : "";

                        callback(countryCode);
                    });
                },
            });

            document.getElementById('phone').addEventListener("countrychange", function() {
                let countryData = $('#phone').intlTelInput('getSelectedCountryData');

                $('#phone_code').val(countryData.dialCode);
            });
        });
    </script>
@endpush
