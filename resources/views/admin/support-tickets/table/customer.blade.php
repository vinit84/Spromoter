@can('customer-read')
    <a href="{{ route('admin.customers.show', $supportTicket->user_id) }}" class="text-primary">
        {{ $supportTicket->user?->name }}
    </a>
@else
    {{ $supportTicket->user?->name }}
@endcan
