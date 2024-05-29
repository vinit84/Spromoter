@extends('layouts.user.app')

@section('content')


    <div class="card">
        <div class="card-body">
            <ul class="list-unstyled">
                <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center pb-3">
                        <h5 class="text-body mb-0 me-auto">{{ trans('All Notifications') }}</h5>
                        <a
                            href="{{ route('user.notifications.mark-all-as-read') }}"
                            class="dropdown-notifications-all text-body"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            aria-label="Mark all as read"
                            data-bs-original-title="Mark all as read">
                            {{ __('Mark all as read') }}
                            <i class="ti ti-mail-opened"></i>
                        </a>
                    </div>
                </li>
            </ul>

            <li class="dropdown-notifications-list ps ps--active-y">
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <a
                            href="{{ route('user.notifications.visit', $notification->id) }}"
                            @class(['list-group-item list-group-item-action', 'unread' => !$notification->read_at]) >
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-danger"><i @class([$notification->data['icon']])></i></span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">
                                        {{ $notification->data['message'] }}
                                    </h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </li>
        </div>
    </div>
@endsection
