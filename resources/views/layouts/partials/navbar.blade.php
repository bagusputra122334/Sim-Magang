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

        <div class="global-search-container d-none d-md-block ms-3 flex-grow-1 position-relative" style="max-width: 500px;">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-muted ps-3">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </span>
                <input class="form-control search-input border-start-0 ps-1 rounded-end"
                       id="globalSearchInput"
                       type="search"
                       placeholder="Cari pendaftar, posisi, atau nomor pendaftaran..."
                       aria-label="Cari pendaftar, posisi, atau nomor pendaftaran"
                       autocomplete="off"
                       data-search-url="{{ route('global.search') }}">
                <span class="position-absolute end-0 top-50 translate-middle-y me-3 d-none" id="globalSearchSpinner" style="z-index: 5;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status" style="width: 0.95rem; height: 0.95rem; border-width: 2px;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </span>
            </div>

            {{-- Search Results Dropdown --}}
            <div class="global-search-dropdown shadow-lg rounded-3 border d-none" id="globalSearchDropdown" role="listbox" aria-label="Hasil Pencarian">
                <div class="global-search-results-list" id="globalSearchResultsList">
                    {{-- Dynamically populated via Javascript --}}
                </div>
            </div>
        </div>

        <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle
                    aria-label="Switch color theme" title="Switch color theme">
                <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>

            {{-- Notification Area --}}
            <div class="dropdown">
                <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                    <span class="notification-dot"></span>
                    <i class="bi bi-bell" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-menu">
                    <div class="dropdown-header fw-bold text-body">Notifikasi</div>
                    <a class="dropdown-item" href="{{ $user?->isAdmin() ? route('admin.applications.index') : route('participant.registrations.index') }}">
                        <span class="notification-title">Status Pendaftaran SIM-MAGANG</span>
                        <span class="notification-time">Sistem Informasi Magang Diskominfo</span>
                    </a>
                </div>
            </div>

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
