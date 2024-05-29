<div class="d-flex align-items-center justify-content-end">
    @if(auth()->user()->can('store-read') && !$store->deleted_at)
        <a href="{{ route('admin.stores.show', $store->id) }}" class="btn btn-sm btn-icon">
            <i class="ti ti-eye"></i>
        </a>
    @endif

    @if(auth()->user()->can('store-update') && !$store->deleted_at)
        <a href="{{ route('admin.stores.edit', $store->id) }}" class="text-body">
            <i class="ti ti-edit ti-sm mx-2"></i>
        </a>
    @endif

    <div class="d-inline-block">
        <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="text-primary ti ti-dots-vertical"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end m-0">
            @if(!$store->deleted_at)
            <li>
                <a href="{{ route('admin.stores.reviews.moderation.index', $store) }}" class="dropdown-item">
                    <i class="ti ti-stars ti-sm mx-2"></i>
                    {{ trans('Moderation') }}
                </a>
            </li>
            <li>
                <a href="{{ route('admin.stores.reviews.import.index', $store) }}" class="dropdown-item">
                    <i class="ti ti-table-import ti-sm mx-2"></i>
                    {{ trans('Import Reviews') }}
                </a>
            </li>
            <li>
                <a href="{{ route('admin.stores.reviews.publish-settings.index', $store) }}" class="dropdown-item">
                    <i class="ti ti-settings-star ti-sm mx-2"></i>
                    {{ trans('Publish Settings') }}
                </a>
            </li>

            <div class="dropdown-divider"></div>
            @endif
            <li>
                @if(auth()->user()->can('store-delete') && !$store->deleted_at)
                    <a href="{{ route('admin.stores.destroy', $store) }}" class="dropdown-item confirm-delete text-danger">
                        <i class="ti ti-trash ti-sm mx-2"></i>
                        {{ trans('Delete') }}
                    </a>
                @elseif(auth()->user()->can('store-delete') && $store->deleted_at)
                    <a href="{{ route('admin.stores.restore', $store) }}" class="dropdown-item confirm-action text-primary">
                        <i class="ti ti-arrow-back-up ti-sm mx-2"></i>
                        {{ trans('Restore') }}
                    </a>
                    <a
                        href="{{ route('admin.stores.destroy.force', $store) }}"
                        class="dropdown-item confirm-delete text-danger"
                        data-text="{{ trans('This action will permanently delete all reviews, subscriptions, and other related data associated with this store. Are you absolutely sure you want to proceed?') }}"
                    >
                        <i class="ti ti-trash ti-sm mx-2"></i>
                        {{ trans('Permanently') }}
                    </a>
                @endif
            </li>
        </ul>
    </div>
</div>
