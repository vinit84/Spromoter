<ul class="nav nav-pills flex-column flex-md-row mb-4">
    <li class="nav-item">
        <a @class(['nav-link', 'active' => Route::is('user.profile.edit')]) href="{{ route('user.profile.edit') }}">
            <i class="ti-xs ti ti-users me-1"></i>
            {{ trans('Account') }}
        </a>
    </li>
    <li class="nav-item">
        <a @class(['nav-link', 'active' => Route::is('user.profile.security*')]) href="{{ route('user.profile.security.index') }}">
            <i class="ti-xs ti ti-lock me-1"></i>
            {{ trans('Security') }}
        </a>
    </li>
    <li class="nav-item">
        <a @class(['nav-link', 'active' => Route::is('user.profile.api-keys*')]) href="{{ route('user.profile.api-keys.index') }}">
            <i class="ti-xs ti ti-key me-1"></i>
            {{ trans('API Keys') }}
        </a>
    </li>
</ul>
