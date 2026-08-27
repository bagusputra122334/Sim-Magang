@php
    $user = auth()->user();
    $userName = $user?->name ?? 'Guest';
    $defaultAvatar = asset('assets/images/avatar/avatar.jpg');
    $userAvatar = $user?->foto_url ?? $defaultAvatar;

    $settingsUrl = route('profile.edit');
    $profileUrl = match(true) {
        $user?->isAdmin() ?? false => route('admin.dashboard'),
        $user?->isParticipant() ?? false => route('participant.profile.index'),
        default => route('login'),
    };

    $dashboardUrl = match(true) {
        $user?->isAdmin() ?? false => route('admin.dashboard'),
        $user?->isParticipant() ?? false => route('participant.dashboard'),
        default => route('login'),
    };
@endphp

<nav class="navbar admin-navbar navbar-expand">
    <div class="container-fluid px-3 px-lg-4">
        <button class="sidebar-toggle" type="button" data-sidebar-toggle
                aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle
                    aria-label="Switch color theme" title="Switch color theme">
                <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>

            {{-- Profile Dropdown --}}
            <div class="dropdown">
                <button class="profile-button dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="avatar-img avatar-sm rounded-circle object-fit-cover"
                         src="{{ $userAvatar }}"
                         alt="{{ $userName }}"
                         onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';">
                    <span class="profile-name d-none d-sm-inline">{{ $userName }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ $profileUrl }}">
                            <i class="bi bi-person-badge me-2"></i> Profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ $settingsUrl }}">
                            <i class="bi bi-gear me-2"></i> Pengaturan Akun
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0" id="logout-form-navbar">
                            @csrf
                            <a class="dropdown-item" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
