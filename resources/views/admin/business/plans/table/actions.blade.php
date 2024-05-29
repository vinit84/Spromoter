<div class="d-flex align-items-center">
    @can('plan-read')
        <a href="{{ route('admin.business.plans.show', $plan->id) }}" class="btn btn-sm btn-icon">
            <i class="ti ti-eye ti-sm mx-2"></i>
        </a>
    @endcan

    @can('plan-update')
        <a href="{{ route('admin.business.plans.edit', $plan->id) }}" class="btn btn-sm btn-icon">
            <i class="ti ti-edit ti-sm mx-2"></i>
        </a>
    @endcan
</div>
