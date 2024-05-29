@if($plan->is_active)
    <span class="badge bg-success">
        <i class="ti ti-circle-check"></i>
        {{ trans('Active') }}
    </span>
@else
    <span class="badge bg-danger">
        <i class="ti ti-circle-x"></i>
        {{ trans('Inactive') }}
    </span>
@endif
