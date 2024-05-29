@if($supportTicket->status == \App\Models\SupportTicket::STATUS_OPEN)
    <a
        href="{{ route('admin.support-tickets.change-status', $supportTicket) }}"
        class="btn btn-sm btn-success confirm-action"
        title="{{ trans("Mark as close") }}"
        data-method="PUT"
        data-bs-toggle="tooltip"
    >
        <i class="ti ti-clock ti-xs me-1"></i>
        {{ str($supportTicket->status)->title() }}
    </a>
@else
    <a
        href="{{ route('admin.support-tickets.change-status', $supportTicket) }}"
        class="btn btn-sm btn-danger confirm-action"
        title="{{ trans("Mark as open") }}"
        data-method="PUT"
        data-bs-toggle="tooltip"
    >
        <i class="ti ti-circle-check ti-xs me-1"></i>
        {{ str($supportTicket->status)->title() }}
    </a>
@endif
