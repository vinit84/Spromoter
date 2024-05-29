<div class="d-flex align-items-center">
    @can('support-ticket-read')
        <a href="{{ route('admin.support-tickets.show', $supportTicket) }}" class="btn btn-sm btn-icon">
            <i class="ti ti-eye ti-sm mx-2"></i>
        </a>
    @endcan

{{--    @can('support-ticket-update')
        <a href="{{ route('admin.support-tickets.edit', $supportTicket) }}" class="btn btn-sm btn-icon">
            <i class="ti ti-edit ti-sm mx-2"></i>
        </a>
    @endcan--}}

    @can('support-ticket-delete')
        <a href="{{ route('admin.support-tickets.destroy', $supportTicket) }}" class="btn btn-sm btn-icon confirm-delete">
            <i class="ti ti-trash ti-sm mx-2"></i>
        </a>
    @endcan

        <a href="javascript:void(0)" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="ti ti-dots-vertical"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end m-0">
            @if($supportTicket->status == \App\Models\SupportTicket::STATUS_OPEN && auth()->user()->can('support-ticket-update'))
                <a href="{{ route('admin.support-tickets.change-status', $supportTicket) }}" class="dropdown-item confirm-action" data-method="PUT">
                    <i class="ti ti-circle-x ti-sm mx-2"></i>
                    {{ trans('Close') }}
                </a>
            @else
                <a href="{{ route('admin.support-tickets.change-status', $supportTicket) }}" class="dropdown-item confirm-action" data-method="PUT">
                    <i class="ti ti-clock ti-sm mx-2"></i>
                    {{ trans('Reopen') }}
                </a>
            @endif
        </div>

</div>
