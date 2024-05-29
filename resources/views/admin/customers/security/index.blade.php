@extends('admin.customers.layout', [
    'title' => trans('Customer Security'),
])

@section('userContent')
    <!-- Change Password -->
    <div class="card mb-4">
        <h5 class="card-header">{{ trans('Change Password') }}</h5>
        <div class="card-body">
            <form action="{{ route('admin.customers.security.change-password', $customer->id) }}" method="POST" class="ajaxform">
                @csrf
                @method('PUT')
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading mb-2">{{ trans('Ensure that these requirements are met') }}</h5>
                    <span>{{ trans('Minimum 8 characters long, uppercase & symbol') }}</span>
                </div>
                <div class="row">
                    <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                        <label class="form-label" for="password">{{ trans('New Password') }}</label>
                        <div class="input-group input-group-merge">
                            <input
                                class="form-control"
                                type="password"
                                id="password"
                                name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                    </div>

                    <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                        <label class="form-label" for="confirmation_password">{{ trans('Confirm New Password') }}</label>
                        <div class="input-group input-group-merge">
                            <input
                                class="form-control"
                                type="password"
                                name="confirmation_password"
                                id="confirmation_password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary me-2">{{ trans('Change Password') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--/ Change Password -->

    <!-- Two-steps verification -->
    {{--<div class="card mb-4">
        <h5 class="card-header pb-2">Two-steps verification</h5>
        <div class="card-body">
            <span>Keep your account secure with authentication step.</span>
            <h6 class="mt-3 mb-2">SMS</h6>
            <div class="d-flex justify-content-between border-bottom mb-3 pb-2">
                <span>+1(968) 945-8832</span>
                <div class="action-icons">
                    <a
                        href="javascript:;"
                        class="text-body me-1"
                        data-bs-target="#enableOTP"
                        data-bs-toggle="modal"
                    ><i class="ti ti-edit ti-sm"></i
                        ></a>
                    <a href="javascript:;" class="text-body"><i class="ti ti-trash ti-sm"></i></a>
                </div>
            </div>
            <p class="mb-0">
                Two-factor authentication adds an additional layer of security to your account by requiring more
                than just a password to log in.
                <a href="javascript:void(0);" class="text-body">Learn more.</a>
            </p>
        </div>
    </div>--}}
    <!--/ Two-steps verification -->
@endsection
