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
    @if(is_array($back))
        @isset($back['can'])
            @can($back['can'])
                <a href="{{ $back['url'] }}" class="btn btn-primary">
                    <i class="ti ti-arrow-left"></i>
                </a>
            @endcan
        @else
            <a href="{{ $back['url'] }}" class="btn btn-primary">
                <i class="ti ti-arrow-left"></i>
            </a>
        @endisset
    @else
        <a href="{{ $back }}" class="btn btn-primary">
            <i class="ti ti-arrow-left"></i>
        </a>
    @endif
@endisset
