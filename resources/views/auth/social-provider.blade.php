<div class="divider my-4">
    <div class="divider-text">or</div>
</div>

<div class="d-flex justify-content-center">
    <a
        href="{{ route('oauth.redirect-to-provider', 'shopify') }}"
        class="btn btn-icon btn-label-facebook me-3"
        title="{{ trans('Login with :name', ['name' => trans('Shopify')]) }}"
        data-bs-toggle="tooltip"
    >
        <i class="tf-icons fa-brands fa-shopify fs-5"></i>
    </a>

    <a
        href="{{ route('oauth.redirect-to-provider', 'facebook') }}"
        class="btn btn-icon btn-label-facebook me-3"
        title="{{ trans('Login with :name', ['name' => trans('Facebook')]) }}"
        data-bs-toggle="tooltip"
    >
        <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
    </a>

    <a
        href="{{ route('oauth.redirect-to-provider', 'google') }}"
        class="btn btn-icon btn-label-google-plus me-3"
        title="{{ trans('Login with :name', ['name' => trans('Google')]) }}"
        data-bs-toggle="tooltip"
    >
        <i class="tf-icons fa-brands fa-google fs-5"></i>
    </a>
</div>
