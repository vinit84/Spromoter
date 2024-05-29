<span @class([
        'badge',
        'bg-label-success' => $customer->status === 'active' && !$customer->deleted_at,
        'bg-label-warning' => $customer->status === 'suspend' && !$customer->deleted_at,
        'bg-label-danger' => $customer->deleted_at,
    ])>
    <i @class([
            'ti',
            'ti-circle-check' => $customer->status === 'active' && !$customer->deleted_at,
            'ti-ban' => $customer->status === 'suspend' && !$customer->deleted_at,
            'ti-trash' => $customer->deleted_at,
            'me-0 ms-sm-1 ti-xs'])></i>
    @if($customer->deleted_at)
        {{ trans('Deleted') }}
    @else
        {{ str($customer->status)->title() }}
    @endif
</span>
