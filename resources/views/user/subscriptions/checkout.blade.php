@extends('layouts.user.blank')

@section('content')
    <div id="checkout"></div>
@endsection

@push('pageScripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // This is your test publishable API key.
        const stripe = Stripe({{ Js::from(config('cashier.key')) }});

        initialize();

        // Create a Checkout Session as soon as the page loads
        async function initialize() {
            const clientSecret = {{ Js::from($clientSecret) }};

            const checkout = await stripe.initEmbeddedCheckout({
                clientSecret,
            });

            // Mount Checkout
            checkout.mount('#checkout');
        }
    </script>
@endpush
