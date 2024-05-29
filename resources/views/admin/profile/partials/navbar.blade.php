<ul class="nav nav-pills flex-column flex-md-row mb-4">
    <li class="nav-item">
        <a @class(['nav-link', 'active' => Route::is('admin.profile.edit')]) href="{{ route('admin.profile.edit') }}">
            <i class="ti-xs ti ti-users me-1"></i>
            {{ trans('Account') }}
        </a>
    </li>
    <li class="nav-item">
        <a @class(['nav-link', 'active' => Route::is('admin.profile.security.index')]) href="{{ route('admin.profile.security.index') }}">
            <i class="ti-xs ti ti-lock me-1"></i>
            {{ trans('Security') }}
        </a>
    </li>
</ul>
