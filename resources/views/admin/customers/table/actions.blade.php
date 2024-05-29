<div class="d-flex align-items-center">
    @can('customer-read')
        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-icon">
            <i class="ti ti-eye"></i>
        </a>
    @endcan

    @can('customer-update')
        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="text-body">
            <i class="ti ti-edit ti-sm mx-2"></i>
        </a>
    @endcan

    @canany(['customer-delete', 'customer-update'])
        <a href="javascript:" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="ti ti-dots-vertical ti-sm mx-1"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end m-0">
            @if($customer->deleted_at === null)
                @can('customer-delete')
                    <a href="{{ route('admin.customers.destroy', $customer->id) }}"
                       class="dropdown-item confirm-delete">
                        <i class="ti ti-trash ti-sm mx-2"></i>
                        {{ trans('Delete') }}
                    </a>
                @endcan

                @can('customer-update')
                    @if($customer->status !== 'suspend')
                        <a href="{{ route('admin.customers.suspend', $customer->id) }}"
                           class="dropdown-item confirm-action">
                            <i class="ti ti-ban ti-sm mx-2"></i>
                            {{ trans('Suspend') }}
                        </a>
                    @else
                        <a href="{{ route('admin.customers.active', $customer->id) }}"
                           class="dropdown-item confirm-action">
                            <i class="ti ti-check ti-sm mx-2"></i>
                            {{ trans('Active') }}
                        </a>
                    @endif

                    @if($customer->email_verified_at == null)
                        <a href="{{ route('admin.customers.verify', $customer->id) }}"
                           class="dropdown-item confirm-action">
                            <i class="ti ti-mail-check ti-sm mx-2"></i>
                            {{ trans('Verify Email') }}
                        </a>
                    @endif

                    <form action="{{ route('admin.customers.login-as', $customer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="ti ti-login me-0 me-sm-1 ti-sm mx-2"></i>
                            {{ trans('Login As') }}
                        </button>
                    </form>
                @endcan
            @else
                @can('customer-update')
                    <a
                        href="{{ route('admin.customers.restore', $customer->id) }}"
                        class="dropdown-item confirm-action"
                        data-message="{{ trans('You are about to restore this user') }}"
                    >
                        <i class="ti ti-arrow-back-up ti-sm mx-2"></i>
                        {{ trans('Restore') }}
                    </a>
                    <a
                        href="{{ route('admin.customers.destroy.force', $customer->id) }}"
                        class="dropdown-item confirm-delete text-danger"
                        data-text="{{ trans('This action will permanently delete all stores, reviews, subscriptions, and other related data associated with this customer. Are you absolutely sure you want to proceed?') }}"
                    >
                        <i class="ti ti-trash ti-sm mx-2"></i>
                        {{ trans('Permanently') }}
                    </a>
                @endcan
            @endif
        </div>
    @endcan
</div>
