<div class="d-flex align-items-center">
    <a href="userView" class="btn btn-sm btn-icon">
        <i class="ti ti-eye"></i>
    </a>
    <a href="javascript:" class="text-body">
        <i class="ti ti-edit ti-sm mx-2"></i>
    </a>
    <a href="javascript:" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="{{ route('admin.settings.users.destroy', $id) }}" class="dropdown-item confirm-delete">
            <i class="ti ti-trash ti-sm mx-2"></i>
            {{ trans('Delete') }}
        </a>
        <a href="javascript:" class="dropdown-item">
            <i class="ti ti-ban ti-sm mx-2"></i>
            {{ trans('Suspend') }}
        </a>
    </div>
</div>
