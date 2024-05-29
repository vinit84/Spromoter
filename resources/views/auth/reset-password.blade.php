@extends('layouts.auth.cover')

@section('content')
    <form action="{{ route('password.update') }}" method="POST" class="ajaxform" id="passwordResetForm">
        @csrf
        <input type="hidden" name="token" value="{{ $request->token }}">
        <div class="row g-4">
            <div class="col-12">
                <input type="email" class="form-control disabled" name="email" id="email" value="{{ request('email') }}" disabled>
                <input type="hidden" name="email" value="{{ request('email') }}">
            </div>

            <div class="col-12">
                <input type="password" class="form-control pr-password" id="password" name="password" placeholder="{{ trans('New Password') }}" aria-label="" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+=[{\]};:<>|./?,-]).{8,}$" required autocomplete="new-password">
            </div>

            <div class="col-12">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="{{ trans('Confirm Password') }}" aria-label="" required autocomplete="new-password">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary align-items-center w-100 btn-lg">
                    {{ trans('Reset Password') }}
                    <i class="fi-rr-arrow-small-right ms-1"></i>
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $('#passwordResetForm').on('ajaxFormSuccess', function (e, response) {
            window.location.href = route('login');

            localStorage.setItem('messageType', 'success');
            localStorage.setItem('message', trans('passwords.reset'));
            localStorage.setItem('hasMessage', true);
        });
    </script>
@endpush
