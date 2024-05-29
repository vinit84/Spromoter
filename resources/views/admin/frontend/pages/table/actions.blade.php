<div class="d-flex align-items-center">
    @can('page-update')
        <a href="{{ route('admin.frontend.pages.edit', $page->id) }}" class="text-body">
            <i class="ti ti-edit ti-sm mx-2"></i>
        </a>
    @endcan

    @can('page-delete')
        <a href="{{ route('admin.frontend.pages.destroy', $page->id) }}" class="btn btn-sm btn-icon confirm-delete">
            <i class="ti ti-trash"></i>
        </a>
    @endcan
</div>
