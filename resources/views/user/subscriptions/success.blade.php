@extends('layouts.user.app')

@section('content')
    <div class="row mb-3 justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="mt-2">Thank You! 😇</h4>
                    <p>Your subscription has been successfully processed.</p>
                    <p class="mb-0">We sent an email to <a href="mailto:john.doe@example.com">john.doe@example.com</a> with your order confirmation and receipt. If the email hasn't arrived within two minutes, please check your spam folder to see if the email was routed there.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
