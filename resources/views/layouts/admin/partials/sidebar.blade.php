<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard.index') }}" class="app-brand-link">
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
        <!-- Dashboards -->

        @can('dashboard-read')
            <li @class(['menu-item', 'active' => Route::is('admin.dashboard*')])>
                <a href="{{ route('admin.dashboard.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <div>{{ trans('Dashboard') }}</div>
                </a>
            </li>
        @endcan

        @canany(['plan-read'])
            <li @class(['menu-item', 'active open' => Route::is('admin.business*')])>
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-building"></i>
                    <div>{{ trans('Business') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('plan-read')
                        <li @class(['menu-item', 'active' => Route::is('admin.business.plans*')])>
                            <a href="{{ route('admin.business.plans.index') }}" class="menu-link">
                                {{ trans('Plans') }}
                            </a>
                        </li>
                    @endcan

                    @can('plan-read')
                        <li @class(['menu-item', 'active' => Route::is('admin.business.invoices*')])>
                            <a href="{{ route('admin.business.invoices.index') }}" class="menu-link">
                                {{ trans('Invoices') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can(['customer-read'])
            <li @class(['menu-item', 'active' => Route::is('admin.customers*')])>
                <a href="{{ route('admin.customers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>{{ trans('Customers') }}</div>
                </a>
            </li>
        @endcan

        @can(['store-read'])
            <li @class(['menu-item', 'active' => Route::is('admin.stores*')])>
                <a href="{{ route('admin.stores.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                    <div>{{ trans('Stores') }}</div>
                </a>
            </li>
        @endcan

        @can(['support-ticket-read'])
            <li @class(['menu-item', 'active' => Route::is('admin.support-tickets*')])>
                <a href="{{ route('admin.support-tickets.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-help"></i>
                    <div>{{ trans('Support Tickets') }}</div>
                </a>
            </li>
        @endcan

        @canany(['page-read'])
            <li @class(['menu-item', 'active open' => Route::is('admin.frontend*')])>
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-device-desktop"></i>
                    <div>{{ trans('Frontend') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('page-read')
                        <li @class(['menu-item', 'active' => Route::is('admin.frontend.pages*')])>
                            <a href="{{ route('admin.frontend.pages.index') }}" class="menu-link">
                                {{ trans('Pages') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['viewPulse'])
        <li @class(['menu-item', 'active' => Request::is('*/pulse')])>
            <a href="{{ url(config('pulse.path')) }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-activity-heartbeat"></i>
                <div>{{ trans('Pulse') }}</div>
            </a>
        </li>
        @endcan

        @canany(['role-read', 'user-read', 'email-setting', 'language-read'])
        <li @class(['menu-item', 'active open' => Route::is('admin.settings*')])>
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div>{{ trans('Settings') }}</div>
            </a>
            <ul class="menu-sub">
                @can('general-setting-read')
                    <li @class(['menu-item', 'active' => Route::is('admin.settings.general*')])>
                        <a href="{{ route('admin.settings.general.index') }}" class="menu-link">
                            {{ trans('General') }}
                        </a>
                    </li>
                @endcan

                @can('role-read')
                    <li @class(['menu-item', 'active' => Route::is('admin.settings.roles*')])>
                        <a href="{{ route('admin.settings.roles.index') }}" class="menu-link">
                            {{ trans('Roles') }}
                        </a>
                    </li>
                @endcan

                @can('user-read')
                    <li @class(['menu-item', 'active' => Route::is('admin.settings.users*')])>
                        <a href="{{ route('admin.settings.users.index') }}" class="menu-link">
                            {{ trans('Users') }}
                        </a>
                    </li>
                @endcan

                @can('email-setting')
                    <li @class(['menu-item', 'active' => Route::is('admin.settings.email*')])>
                        <a href="{{ route('admin.settings.email.index') }}" class="menu-link">
                            {{ trans('Email') }}
                        </a>
                    </li>
                @endcan

                @can('language-read')
                    <li @class(['menu-item', 'active' => Route::is('admin.settings.languages*')])>
                        <a href="{{ route('admin.settings.languages.index') }}" class="menu-link">
                            {{ trans('Languages') }}
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
        @endcanany
    </ul>
</aside>
<!-- / Menu -->
