<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-navbar-fixed layout-wide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../../assets/"
    data-template="front-pages-no-customizer">
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Landing Page - Front Pages | Vuexy - Bootstrap Admin Template</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/spromoter.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/front-page.css') }}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/node-waves/node-waves.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/plugins/nouislider/nouislider.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/swiper/swiper.css') }}" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="{{ asset('assets/css/pages/front-page-landing.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pages/front-page-help-center.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/front-config.js') }}"></script>
</head>

<body>
<script src="{{ asset('assets/js/dropdown-hover.js') }}"></script>
<script src="{{ asset('assets/js/mega-dropdown.js') }}"></script>

@yield('body')

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('assets/plugins/popper/popper.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/plugins/node-waves/node-waves.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('assets/plugins/nouislider/nouislider.js') }}"></script>
<script src="{{ asset('assets/plugins/swiper/swiper.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/front-main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('assets/js/front-page-landing.js') }}"></script>
</body>
</html>
