<span @class([
        'badge',
        'bg-label-success' => $page->is_active,
        'bg-label-danger' => !$page->is_active,
    ])>
    <i @class([
            'ti',
            'ti-circle-check' => $page->is_active,
            'ti-circle-x' => !$page->is_active,
            'me-0 ms-sm-1 ti-xs'])></i>
    @if($page->is_active)
        {{ trans('Active') }}
    @else
        {{ trans('Inactive') }}
    @endif
</span>
