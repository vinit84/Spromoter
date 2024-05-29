@isset($actions)
    @php
        $variant = 'primary';
    @endphp
    @foreach($actions as $action)
        @isset($action['can'])
            @can($action['can'])
                @include('layouts.admin.partials.action-buttons', ['action' => $action, 'variant' => $variant])
            @endcan
        @else
            @include('layouts.admin.partials.action-buttons', ['action' => $action, 'variant' => $variant])
        @endisset
    @endforeach
@endisset

@isset($back)
    <a href="{{ $back }}" class="btn btn-primary">
        <i class="ti ti-arrow-left"></i>
    </a>
@endisset

@isset($add)
    <a href="{{ $add }}" class="btn btn-primary">
        <i class="ti ti-plus"></i>
    </a>
@endisset
