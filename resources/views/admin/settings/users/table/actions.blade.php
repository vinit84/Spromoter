<div class="d-flex align-items-center">
    <a href="{{ route('admin.settings.users.show', $user->id) }}" class="btn btn-sm btn-icon">
        <i class="ti ti-eye"></i>
    </a>
    <a href="{{ route('admin.settings.users.edit', $user->id) }}" class="text-body">
        <i class="ti ti-edit ti-sm mx-2"></i>
    </a>
    <a href="javascript:" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="{{ route('admin.settings.users.destroy', $user->id) }}" class="dropdown-item confirm-delete">
            <i class="ti ti-trash ti-sm mx-2"></i>
            {{ trans('Delete') }}
        </a>
        @if($user->status !== 'suspend')
            <a href="{{ route('admin.settings.users.suspend', $user->id) }}" class="dropdown-item confirm-action">
                <i class="ti ti-ban ti-sm mx-2"></i>
                {{ trans('Suspend') }}
            </a>
        @else
            <a href="{{ route('admin.settings.users.active', $user->id) }}" class="dropdown-item confirm-action">
                <i class="ti ti-check ti-sm mx-2"></i>
                {{ trans('Active') }}
            </a>
        @endif
    </div>
</div>
