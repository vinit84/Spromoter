@foreach($user->roles as $role)
    <span class="text-truncate d-flex align-items-center">
      {{--  <span class="badge badge-center rounded-pill bg-label-primary me-3 w-px-30 h-px-30">
            <i class="ti ti-chart-pie-2 ti-sm"></i>
        </span>--}}
        {{ $role->name }}
    </span>
@endforeach
