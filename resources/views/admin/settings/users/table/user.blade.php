<div class="d-flex justify-content-left align-items-center">
    <div class="avatar-wrapper">
        <div class="avatar me-3">
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle">
        </div>
    </div>
    <div class="d-flex flex-column">
        <a href="app-user-view-account.html" class="text-body text-truncate">
            <span class="fw-medium">{{ $user->name }}</span></a>
        <small class="text-muted">{{ $user->email }}</small>
    </div>
</div>
