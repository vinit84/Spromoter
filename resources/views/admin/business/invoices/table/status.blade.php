@if($invoice->status == 'paid')
    <span class="badge bg-label-success">
        <i class="ti ti-circle-check ti-xs"></i>
        {{ trans('Paid') }}
    </span>
@elseif($invoice->status == 'unpaid')
    <span class="badge bg-label-secondary">
        <i class="ti ti-circle-x ti-xs"></i>
        {{ trans('Unpaid') }}
    </span>
@elseif($invoice->status == 'partial')
    <span class="badge bg-label-info">
        <i class="ti ti-circle-x ti-xs"></i>
        {{ trans('Partial') }}
    </span>
@elseif($invoice->status == 'overdue')
    <span class="badge bg-label-danger">
        <i class="ti ti-circle-x ti-xs"></i>
        {{ trans('Overdue') }}
    </span>
@elseif($invoice->status == 'failed')
    <span class="badge bg-label-danger">
        <i class="ti ti-circle-x ti-xs"></i>
        {{ trans('Unpaid') }}
    </span>
@endif
