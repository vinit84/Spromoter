@extends('layouts.admin.app', [
    'title' => trans('Email Configuration'),
])

@section('content')
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.email.update') }}" method="POST" class="row g-3 ajaxform">
                        @csrf
                        @method('PUT')

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mail_mailer">{{ trans('SMTP') }}</label>
                            <input
                                type="text"
                                id="mail_mailer"
                                name="mail_mailer"
                                class="form-control"
                                placeholder="ex. smtp"
                                value="{{ env('MAIL_MAILER', 'smtp') }}"
                                required
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mail_host">{{ trans('Mail Host') }}</label>
                            <input
                                type="text"
                                id="mail_host"
                                name="mail_host"
                                class="form-control"
                                placeholder="ex. smtp.mailgun.org"
                                value="{{ env('MAIL_HOST') }}"
                                required
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mail_port">{{ trans('Mail Port') }}</label>
                            <input
                                type="text"
                                id="mail_port"
                                name="mail_port"
                                class="form-control"
                                placeholder="ex. 587"
                                value="{{ env('MAIL_PORT') }}"
                                required
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mail_username">{{ trans('Mail Username') }}</label>
                            <input
                                type="text"
                                id="mail_username"
                                name="mail_username"
                                class="form-control"
                                placeholder="ex. no-reply@example.com"
                                value="{{ env('MAIL_USERNAME') }}"
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mail_password">{{ trans('Mail Password') }}</label>
                            <input
                                type="password"
                                id="mail_password"
                                name="mail_password"
                                class="form-control"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                value="{{ env('MAIL_PASSWORD') }}"
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mail_encryption">{{ trans('Mail Encryption') }}</label>
                            <input
                                type="text"
                                id="mail_encryption"
                                name="mail_encryption"
                                class="form-control"
                                placeholder="ex. tls"
                                value="{{ env('MAIL_ENCRYPTION') }}"
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mail_from_address">{{ trans('Mail From Address') }}</label>
                            <input
                                type="text"
                                id="mail_from_address"
                                name="mail_from_address"
                                class="form-control"
                                placeholder="ex. no-reply@example.com"
                                value="{{ env('MAIL_FROM_ADDRESS') }}"
                                required
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mail_from_name">{{ trans('Mail From Address') }}</label>
                            <input
                                type="text"
                                id="mail_from_name"
                                name="mail_from_name"
                                class="form-control"
                                placeholder="ex. abnDevs"
                                value="{{ env('MAIL_FROM_NAME') }}"
                                required
                            />
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                <i class="ti ti-database-edit me-0 me-sm-1 ti-xs"></i>
                                {{ trans('Update Changes') }}
                            </button>
                            <button type="reset" class="btn btn-label-secondary">
                                <i class="ti ti-refresh me-0 me-sm-1 ti-xs"></i>
                                {{ trans('Reset') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.email.test') }}" method="POST" class="ajaxform row g-3">
                        <div class="col-12">
                            <label class="form-label" for="to">{{ trans('To') }}</label>
                            <input
                                type="email"
                                id="to"
                                name="to"
                                class="form-control"
                                placeholder="ex. john@example.com"
                                value="{{ auth()->user()->email }}"
                                required
                            />
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="subject">{{ trans('Subject') }}</label>
                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                class="form-control"
                                placeholder="ex. Send testing email"
                                required
                            />
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="body">{{ trans('Body') }}</label>
                            <textarea
                                id="body"
                                name="body"
                                class="form-control"
                                rows="4"
                                required
                            ></textarea>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                <i class="ti ti-send me-0 me-sm-1 ti-xs"></i>
                                {{ trans('Send') }}
                            </button>
                            <button type="reset" class="btn btn-label-secondary">
                                <i class="ti ti-refresh me-0 me-sm-1 ti-xs"></i>
                                {{ trans('Reset') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
