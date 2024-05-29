@extends('layouts.user.app', [
    'title' => trans('Profile')
])

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('user.profile.partials.navbar')
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <!-- Account -->
                <div class="card-body">
                    <input type="file" name="photo" id="photo">
                    <input type="hidden" id="avatar" value="{{ $user->profile_photo_url }}">
                    <div class="text-muted">{{ trans('Allowed JPG, or PNG. Max size of 800KB') }}</div>
                </div>
                <hr class="my-0"/>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" class="ajaxform" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="first_name" class="form-label">{{ trans('First Name') }}</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ $user->first_name }}"
                                    autofocus
                                    required
                                />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="last_name" class="form-label">{{ trans('Last Name') }}</label>
                                <input class="form-control" type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" required/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">{{ trans('E-mail') }}</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="email"
                                    name="email"
                                    value="{{ $user->email }}"
                                    placeholder="Ex. john.doe@example.com"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="phone">{{ trans('Phone') }}</label>
                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    class="form-control"
                                    value="{{ $user->phone }}"
                                    required
                                />
                                <input type="hidden" name="phone_code" id="phone_code" value="{{ $user->phone_code }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="company" class="form-label">{{ trans('Company') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="company"
                                    name="company"
                                    value="{{ $user->company }}"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="position" class="form-label">{{ trans('Position') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="position"
                                    name="position"
                                    value="{{ $user->position }}"/>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">{{ trans('Address') }}</label>
                                <input type="text" class="form-control" id="address" name="address"
                                       value="{{ $user->address }}" placeholder="{{ trans('Address') }}"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="city" class="form-label">{{ trans('City') }}</label>
                                <input class="form-control" type="text" id="city" name="city" value="{{ $user->city }}"
                                       placeholder="Ex. California"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="state" class="form-label">{{ trans('State') }}</label>
                                <input class="form-control" type="text" id="state" name="state" value="{{ $user->state }}"
                                       placeholder="Ex. California"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="postal_code" class="form-label">{{ trans('Postal Code') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="postal_code"
                                    name="postal_code"
                                    placeholder="Ex. 231465"
                                    value="{{ $user->postal_code }}"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="country">{{ trans('Country') }}</label>
                                <select name="country" id="country" class="select2 form-select" data-control="select2" data-placeholder="{{ trans('Select Country') }}">
                                    <option></option>
                                    @foreach($countries as $country)
                                        <option
                                            value="{{ $country['name'] }}"
                                            data-code="{{ $country['ISO2'] }}"
                                            @selected($country['name'] == $user->country)
                                        >{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="language" class="form-label">{{ trans('Language') }}</label>
                                <select name="language" id="language" class="select2 form-select" data-control="select2" data-placeholder="{{ trans('Select Language') }}">
                                    <option></option>
                                    @foreach($languages as $language)
                                        <option value="{{ $language->id }}" @selected($language->id == $user->language_id)>
                                            {{ $language->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="timezone" class="form-label">{{ trans('Timezone') }}</label>
                                <select name="timezone" id="timezone" class="select2 form-select" data-control="select2" data-placeholder="{{ trans('Select Timezone') }}">
                                    <option></option>
                                    @foreach($timeZones as $timeZone => $title)
                                        <option value="{{ $timeZone }}" @selected($timeZone == $user->timezone)>
                                            {{ str($title)->value() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">{{ trans('Save changes') }}</button>
                            <button type="reset" class="btn btn-label-secondary">{{ trans('Cancel') }}</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
            {{--<div class="card">
                <h5 class="card-header">{{ trans('Delete Account') }}</h5>
                <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h5 class="alert-heading mb-1">{{ trans('Are you sure you want to delete your account?') }}</h5>
                            <p class="mb-0">{{ trans('Once you delete your account, there is no going back. Please be certain.') }}</p>
                        </div>
                    </div>
                    <form action="{{ route('user.profile.deactivate') }}" method="POST" class="ajaxform">
                        @csrf
                        @method('DELETE')
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password">Current Password</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        class="form-control"
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required
                                    />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-4">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="agree"
                                id="agree"
                                required
                            />
                            <label class="form-check-label" for="agree"
                            >{{ trans('I confirm my account deactivation') }}</label
                            >
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account">{{ trans('Deactivate Account') }}</button>
                    </form>
                </div>
            </div>--}}
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/intl-tel-input/intlTelInput.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/plugins/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/plugins/intl-tel-input/intlTelInput-jquery.min.js') }}"></script>
@endpush

@push('pageScripts')
    <script src="{{ asset('assets/plugins/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script
        src="{{ asset('assets/plugins/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script
        src="{{ asset('assets/plugins/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateSize, FilePondPluginFileValidateType)

        const profilePhotoUrl = document.getElementById('avatar')

        const pond = FilePond.create(document.getElementById('photo'), {
            credits: false,
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
            allowFileTypeValidation: true,
            allowMultiple: false,
            maxFileSize: '800KB',
            maxFiles: 1,
            labelIdle: trans('Drag & Drop your avatar or :upload', {
                'upload': '<span class="filepond--label-action">'+ trans('Browse') +'</span>'
            }),
            server: {
                process: {
                    url: route('user.profile.upload-avatar'),
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                },
                revert: {
                    url: route('user.profile.delete-avatar'),
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                }
            },
        })

        if (profilePhotoUrl.value) {
            let pond = FilePond.find(document.getElementById('photo'))

            pond.setOptions({
                allowDrop: false,
                allowBrowse: false,
                allowPaste: false,
                allowMultiple: false,
                allowReorder: false,
                allowReplace: false,
                allowRevert: false,
                allowProcess: false,
                instantUpload: false,
                maxFileSize: '800KB',
                files: [{
                    source: profilePhotoUrl.value
                }]
            })

            pond.on('removefile', () => {
                pond.setOptions({
                    allowDrop: true,
                    allowBrowse: true,
                    allowPaste: true,
                    allowMultiple: false,
                    allowReorder: false,
                    allowReplace: false,
                    allowRevert: true,
                    allowProcess: true,
                    instantUpload: true,
                })
            });

            pond.on('processfile', () => {
                window.location.reload();
            });
        }

        $('#phone').intlTelInput({
            initialCountry: 'auto',
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function () {}, "jsonp").always(function (resp) {
                    let countryCode = (resp && resp.country) ? resp.country : "";

                    // Check if the country is selected
                    if ($('#country').find(":selected")) {
                        $('#country').find(`option[data-code="${countryCode}"]`).prop('selected', true).trigger('change');
                    }

                    callback(countryCode);
                });
            },
        });

        document.getElementById('phone').addEventListener("countrychange", function() {
            let countryData = $('#phone').intlTelInput('getSelectedCountryData');

            $('#phone_code').val(countryData.dialCode);
        });

    </script>
@endpush
