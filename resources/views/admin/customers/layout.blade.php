@extends('layouts.admin.app', [
    'back' => route('admin.customers.index'),
])

@section('content')
    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            <img
                                class="img-fluid rounded mb-3 pt-1 mt-4"
                                src="{{ $customer->avatar }}"
                                height="100"
                                width="100"
                                alt="{{ $customer->name }}" />
                            <div class="user-info text-center">
                                <h4 class="mb-2">{{ $customer->name }}</h4>
                                @if($customer->position)
                                    <span class="badge bg-label-secondary mt-1">
                                        {{ $customer->position }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{--  <div class="d-flex justify-content-around flex-wrap mt-3 pt-3 pb-4 border-bottom">
                          <div class="d-flex align-items-start me-4 mt-3 gap-2">
                              <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-checkbox ti-sm"></i></span>
                              <div>
                                  <p class="mb-0 fw-medium">1.23k</p>
                                  <small>Tasks Done</small>
                              </div>
                          </div>
                          <div class="d-flex align-items-start mt-3 gap-2">
                              <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-briefcase ti-sm"></i></span>
                              <div>
                                  <p class="mb-0 fw-medium">568</p>
                                  <small>Projects Done</small>
                              </div>
                          </div>
                      </div>--}}
                    <p class="mt-4 small text-uppercase text-muted">{{ trans('Details') }}</p>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="fw-medium me-1">{{ trans('Username:') }}</span>
                                <span>{{ $customer->username }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">{{ trans('Email:') }}</span>
                                <span>{{ $customer->email }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">{{ trans('Phone:') }}</span>
                                <span>{{ $customer->phone }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">{{ trans('Status:') }}</span>
                                <span @class([
                                    'badge',
                                    'bg-label-success' => $customer->status === 'active',
                                    'bg-label-danger' => $customer->status === 'suspend',
                                    ])>
                                    {{ str($customer->status)->title() }}
                                </span>
                            </li>
                            <li class="pt-1">
                                <span class="fw-medium me-1">{{ trans('Country:') }}</span>
                                <span>{{ $customer->country }}</span>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-primary me-3">
                                <i class="ti ti-user-edit me-0 me-sm-1 ti-xs"></i>
                                {{ trans('Edit') }}
                            </a>
                            @if($customer->status !== 'suspend')
                                <a
                                    href="{{ route('admin.customers.suspend', $customer) }}"
                                    class="btn btn-label-danger confirm-action"
                                    data-message="{{ trans('You are about to suspend this user') }}"
                                    data-confirm-icon="ti ti-user-cancel"
                                >
                                    <i class="ti ti-user-cancel me-0 me-sm-1 ti-xs"></i>
                                    {{ trans('Suspend') }}
                                </a>
                            @else
                                <a
                                    href="{{ route('admin.customers.active', $customer) }}"
                                    class="btn btn-label-danger confirm-action"
                                    data-message="{{ trans('You are about to unsuspend this user') }}"
                                    data-confirm-icon="ti ti-user-check"
                                >
                                    <i class="ti ti-user-check me-0 me-sm-1 ti-xs"></i>
                                    {{ trans('Active') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Card -->
        </div>
        <!--/ User Sidebar -->

        <!-- User Content -->
        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <!-- User Pills -->
            <ul class="nav nav-pills flex-column flex-md-row mb-4">
                <li class="nav-item">
                    <a
                        @class(['nav-link', 'active' => Route::is('admin.customers.show')])
                        href="{{ route('admin.customers.show', $customer->id) }}"
                    >
                        <i class="ti ti-user-check ti-xs me-1"></i>
                        {{ trans('Profile') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        @class(['nav-link', 'active' => Route::is('admin.customers.security.index')])
                        href="{{ route('admin.customers.security.index', $customer->id) }}"
                    >
                        <i class="ti ti-user-shield ti-xs me-1"></i>
                        {{ trans('Security') }}
                    </a>
                </li>
            </ul>
            <!--/ User Pills -->


            @yield('userContent')
        </div>
        <!--/ User Content -->
    </div>
@endsection

@push('pageScripts')
    <script>
        $('.confirm-action').on('confirmActionSuccess', function (xx, response) {
            localStorage.setItem('messageType', response.status);
            localStorage.setItem('message', response.message);
            localStorage.setItem('hasMessage', response.hasMessage);
            window.location.reload()
        })
    </script>
@endpush
