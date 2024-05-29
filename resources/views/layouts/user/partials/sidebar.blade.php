<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('user.dashboard.index') }}" class="app-brand-link">
            <img class="logo-big" src="{{ asset('assets/img/logo.png') }}" alt="{{ config('app.name') }}">
            <img class="logo-small" src="{{ asset('assets/img/small-logo.jpg') }}" alt="{{ config('app.name') }}">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li @class(['menu-item', 'active' => Route::is('user.dashboard*')])>
            <a href="{{ route('user.dashboard.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{ trans('Dashboard') }}</div>
            </a>
        </li>


        <li @class(['menu-item', 'active open' => Route::is('user.reviews*')])>
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-stars"></i>
                <div>{{ trans('Reviews') }}</div>
            </a>
            <ul class="menu-sub">
                <li @class(['menu-item', 'active' => Route::is('user.reviews.moderation*')])>
                    <a href="{{ route('user.reviews.moderation.index') }}" class="menu-link">
                        <div>{{ trans('Moderation') }}</div>
                    </a>
                </li>
                <li @class(['menu-item', 'active' => Route::is('user.reviews.import*')])>
                    <a href="{{ route('user.reviews.import.index') }}" class="menu-link">
                        <div>{{ trans('Import Reviews') }}</div>
                    </a>
                </li>
                <li @class(['menu-item', 'active' => Route::is('user.reviews.publish-settings*')])>
                    <a href="{{ route('user.reviews.publish-settings.index') }}" class="menu-link">
                        <div>{{ trans('Publish Settings') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li @class(['menu-item', 'active open' => Route::is('user.emails*')])>
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div>{{ trans('Emails') }}</div>
            </a>
            <ul class="menu-sub">
                <li @class(['menu-item', 'active' => Route::is('user.emails.email-setup*')])>
                    <a href="{{ route('user.emails.email-setup.index') }}" class="menu-link">
                        <div>{{ trans('Email Setup') }}</div>
                    </a>
                </li>
                <li @class(['menu-item', 'active' => Route::is('user.emails.email-status*')])>
                    <a href="{{ route('user.emails.email-status.index') }}" class="menu-link">
                        <div>{{ trans('Email Status') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li @class(['menu-item', 'active open' => Route::is('user.analytics*')])>
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-report-analytics"></i>
                <div>{{ trans('Analytics') }}</div>
            </a>
            <ul class="menu-sub">
                <li @class(['menu-item', 'active' => Route::is('user.analytics.reviews*')])>
                    <a href="{{ route('user.analytics.reviews.index') }}" class="menu-link">
                        <div>{{ trans('Reviews') }}</div>
                    </a>
                </li>
               {{-- <li @class(['menu-item', 'active' => Route::is('user.analytics.emails*')])>
                    <a href="{{ route('user.analytics.emails.index') }}" class="menu-link">
                        <div>{{ trans('Emails') }}</div>
                    </a>
                </li>--}}
            </ul>
        </li>

        <li @class(['menu-item', 'active' => Route::is('user.support-tickets*')])>
            <a href="{{ route('user.support-tickets.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-help"></i>
                <div>{{ trans('Support Tickets') }}</div>
            </a>
        </li>
    </ul>

    <div class="card border card-border-shadow-primary mx-3 mb-3 card-side-bar-order">
        <div class="card-body text-center">
            <h6 class="mb-1">{{ trans('Orders') }}</h6>
            <p>{{ trans(':orders of :total', ['orders' => $usedOrders, 'total' => $totalOrders]) }}</p>
            <a class="btn btn-primary" href="{{ route('user.plans.index') }}">
                {{ trans('Purchase') }}
            </a>
        </div>
    </div>
</aside>
<!-- / Menu -->
