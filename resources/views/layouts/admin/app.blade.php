@extends('layouts.admin.master')

@section('body')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @include('layouts.admin.partials.sidebar')

            <div class="layout-page">
                @include('layouts.admin.partials.navbar')

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                            <div class="d-flex flex-column justify-content-center">
                                <h4 class="mb-1 mt-3">
                                    @isset($title)
                                        {{ $title }}
                                    @endisset
                                </h4>
                                @hasSection('description')
                                    <p class="text-muted">@yield('description')</p>
                                @endif
                            </div>
                            <div class="d-flex align-content-center flex-wrap gap-3">
                                <div class="d-flex gap-3">
                                    @include('layouts.admin.partials.actions')
                                </div>
                            </div>
                        </div>

                        @yield('content')
                    </div>

                    @include('layouts.admin.partials.footer')
                </div>
            </div>
        </div>
    </div>
@endsection
