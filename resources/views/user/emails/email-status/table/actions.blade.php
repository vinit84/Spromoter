<div class="d-flex align-items-center justify-content-center">
    @if($orderEmail->sent_at == null && $orderEmail->failed_at == null && $orderEmail->limit_exceeded_at == null)
        <a href="{{ route('user.emails.email-status.send', $orderEmail) }}" class="btn btn-sm btn-icon ajax-link resend-link" title="{{ trans('Send Now') }}" data-bs-toggle="tooltip">
            <i class="ti ti-send ti-sm mx-2"></i>
        </a>
    @endif

    @if($orderEmail->failed_at || $orderEmail->limit_exceeded_at)
        <a href="{{ route('user.emails.email-status.send', $orderEmail) }}" class="btn btn-sm btn-icon ajax-link resend-link" title="{{ trans('Retry') }}" data-bs-toggle="tooltip">
            <i class="ti ti-refresh ti-sm mx-2"></i>
        </a>
    @endif

    @if($orderEmail->sent_at == null)
        <a href="{{ route('user.emails.email-status.destroy', $orderEmail) }}" class="btn btn-sm btn-icon text-danger confirm-delete" title="{{ trans('Delete') }}" data-bs-toggle="tooltip">
            <i class="ti ti-trash ti-sm mx-2"></i>
        </a>
    @endif
</div>
