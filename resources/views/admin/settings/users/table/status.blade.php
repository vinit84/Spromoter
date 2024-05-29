<span @class([
        'badge',
        'bg-label-success' => $user->status === 'active',
        'bg-label-danger' => $user->status === 'suspend',
    ])>
    <i @class([
            'ti',
            'ti-circle-check' => $user->status === 'active',
            'ti-circle-x' => $user->status === 'suspend',
            'me-0 ms-sm-1 ti-xs'])></i>
    {{ str($user->status)->title() }}
</span>
