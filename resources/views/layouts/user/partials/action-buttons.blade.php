<a href="{{ $action['link'] }}" @class(['btn', 'btn-'.$variant])>
    @isset($action['icon'])
        <i @class(['ti', $action['icon'], 'me-0 me-sm-1 ti-xs'])></i>
    @endisset
    {{ $action['text'] }}
</a>
