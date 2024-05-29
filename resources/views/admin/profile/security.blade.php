@extends('layouts.admin.app', [
    'title' => trans('Security')
])

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('admin.profile.partials.navbar')

            <!-- Change Password -->
            <div class="card mb-4">
                <h5 class="card-header">{{ trans('Change Password') }}</h5>
                <div class="card-body">
                    <form action="{{ route('admin.profile.security.change-password') }}" method="POST" class="ajaxform" id="changePasswordForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="current_password">{{ trans('Current Password') }}</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        class="form-control"
                                        type="password"
                                        name="current_password"
                                        id="current_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required
                                    />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password">{{ trans('New Password') }}</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        class="form-control"
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        minlength="8"
                                        required
                                    />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">{{ trans('Confirm New Password') }}</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        class="form-control"
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        minlength="8"
                                        required
                                    />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <h6>{{ trans('Password Requirements') }}:</h6>
                                <ul class="ps-3 mb-0">
                                    <li class="mb-1">{{ trans('Minimum 8 characters long - the more, the better') }}</li>
                                    <li class="mb-1">{{ trans('At least one lowercase character') }}</li>
                                    <li>{{ trans('At least one number, symbol, or whitespace character') }}</li>
                                </ul>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary me-2">{{ trans('Save changes') }}</button>
                                <button type="reset" class="btn btn-label-secondary">{{ trans('Cancel') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Change Password -->
        </div>
    </div>
@endsection
