@php
    $user = auth()->user();
    $userName = $user?->name ?? 'Guest';
    $userRole = $user?->role?->value ?? 'guest';

    $dashboardUrl = match(true) {
        $user?->isAdmin() ?? false => route('admin.dashboard'),
        $user?->isParticipant() ?? false => route('participant.dashboard'),
        default => route('login'),
    };

    $workspaceLabel = match(true) {
        $user?->isAdmin() ?? false => 'Workspace Admin',
        $user?->isParticipant() ?? false => 'Workspace Peserta',
        default => 'Public Workspace',
    };

    $defaultAvatar = asset('assets/images/avatar/avatar.jpg');
    $userAvatar = $user?->foto_url ?? $defaultAvatar;

    if (!isset($sidebarMenu) || !is_array($sidebarMenu)) {
        $sidebarMenu = [];
        if ($user?->isAdmin()) {
            $sidebarMenu = [
                ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'route' => 'admin.dashboard', 'params' => []],
                ['icon' => 'bi-briefcase', 'label' => 'Posisi Magang', 'route' => 'admin.positions.index', 'params' => []],
                ['icon' => 'bi-journal-check', 'label' => 'Verifikasi Pendaftaran', 'route' => 'admin.applications.index', 'params' => []],
                ['icon' => 'bi-people-fill', 'label' => 'Status Magang', 'route' => 'admin.active-interns.index', 'params' => []],
                ['icon' => 'bi-star-fill', 'label' => 'Survei Kepuasan', 'route' => 'admin.surveys.index', 'params' => []],
                ['icon' => 'bi-gear-fill', 'label' => 'Pengaturan', 'route' => 'admin.settings.index', 'params' => []],
                ['icon' => 'bi-layout-text-window-reverse', 'label' => 'Konten Landing', 'route' => 'admin.landing-contents.index', 'params' => []],
                ['icon' => 'bi-person-gear', 'label' => 'Akun Saya', 'route' => 'profile.edit', 'params' => []],
            ];
        } elseif ($user?->isParticipant()) {
            $sidebarMenu = [
                ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'route' => 'participant.dashboard', 'params' => []],
                ['icon' => 'bi-person-badge', 'label' => 'Profil Saya', 'route' => 'participant.profile.index', 'params' => []],
                ['icon' => 'bi-journal-text', 'label' => 'Riwayat Magang', 'route' => 'participant.registrations.index', 'params' => []],
                ['icon' => 'bi-person-gear', 'label' => 'Akun Saya', 'route' => 'profile.edit', 'params' => []],
            ];
        }
    }

    $currentRoute = request()->route()?->getName() ?? '';
@endphp

<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
    <div class="sidebar-header">
        <a class="brand-mark d-flex align-items-center gap-3 text-decoration-none" href="{{ $dashboardUrl }}" aria-label="SIMAGANG Dashboard">
            <img src="{{ asset('traveland/images/logo.png') }}" alt="Logo Tuban" class="h-10 w-auto object-contain flex-shrink-0 sidebar-brand-logo">
            <div>
                <h1 class="text-white font-bold text-lg leading-tight tracking-wide mb-0" style="font-size: 1.05rem; font-weight: 700; color: #ffffff;">SIMAGANG</h1>
                <p class="text-slate-400 text-xs font-medium mb-0" style="font-size: 0.75rem; color: #94a3b8;">Diskominfo SP Kab. Tuban</p>
            </div>
        </a>
    </div>

    @php
        $currentRoute = request()->route()?->getName() ?? '';
        $currentPath = request()->path();
        $isPengaturanActive = str_starts_with($currentRoute, 'admin.settings') 
            || str_starts_with($currentRoute, 'admin.landing-contents')
            || str_contains($currentPath, 'admin/settings') 
            || str_contains($currentPath, 'admin/landing-contents');
    @endphp

    <nav class="sidebar-nav">
        <ul class="nav flex-column gap-1 p-0 mb-0">
            @if ($user?->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'admin.positions') ? 'active' : '' }}" href="{{ route('admin.positions.index') }}">
                        <span class="nav-icon"><i class="bi bi-briefcase" aria-hidden="true"></i></span>
                        <span class="nav-text">Posisi Magang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'admin.applications') ? 'active' : '' }}" href="{{ route('admin.applications.index') }}">
                        <span class="nav-icon"><i class="bi bi-journal-check" aria-hidden="true"></i></span>
                        <span class="nav-text">Verifikasi Pendaftaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'admin.active-interns') ? 'active' : '' }}" href="{{ route('admin.active-interns.index') }}">
                        <span class="nav-icon"><i class="bi bi-people-fill" aria-hidden="true"></i></span>
                        <span class="nav-text">Status Magang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'admin.surveys') ? 'active' : '' }}" href="{{ route('admin.surveys.index') }}">
                        <span class="nav-icon"><i class="bi bi-star-fill" aria-hidden="true"></i></span>
                        <span class="nav-text">Survei Kepuasan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $isPengaturanActive ? 'active' : '' }}" href="#" onclick="event.preventDefault(); const sub = document.getElementById('subPengaturan'); sub.style.display = (sub.style.display === 'none' || sub.style.display === '') ? 'block' : 'none';">
                        <i class="bi bi-gear me-2"></i>
                        <span>Pengaturan</span>
                        <i class="bi bi-chevron-down ms-auto float-end"></i>
                    </a>
                    <div id="subPengaturan" style="display: {{ $isPengaturanActive ? 'block' : 'none' }};">
                        <ul class="nav flex-column ms-3 mt-1">
                            <li class="nav-item">
                                <a class="nav-link py-1" href="{{ url('/admin/settings#pengaturan-global') }}">
                                    <i class="bi bi-globe me-2"></i>Pengaturan Global
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1" href="{{ url('/admin/settings#landing-page-portal') }}">
                                    <i class="bi bi-layout-text-window-reverse me-2"></i>Landing Page Portal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1" href="{{ url('/admin/settings#informasi-kontak') }}">
                                    <i class="bi bi-person-lines-fill me-2"></i>Informasi Kontak & Sosial Media
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1" href="{{ url('/admin/landing-contents') }}">
                                    <i class="bi bi-grid-fill me-2"></i>Konten Landing
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <span class="nav-icon"><i class="bi bi-person-gear" aria-hidden="true"></i></span>
                        <span class="nav-text">Akun Saya</span>
                    </a>
                </li>
            @elseif ($user?->isParticipant())
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'participant.dashboard') ? 'active' : '' }}" href="{{ route('participant.dashboard') }}">
                        <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'participant.profile') ? 'active' : '' }}" href="{{ route('participant.profile.index') }}">
                        <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
                        <span class="nav-text">Profil Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'participant.registrations') ? 'active' : '' }}" href="{{ route('participant.registrations.index') }}">
                        <span class="nav-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
                        <span class="nav-text">Riwayat Magang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_starts_with($currentRoute, 'profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                        <span class="nav-icon"><i class="bi bi-person-gear" aria-hidden="true"></i></span>
                        <span class="nav-text">Akun Saya</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar rounded-circle object-fit-cover"
             src="{{ $userAvatar }}"
             alt="{{ $userName }}"
             onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';">
        <strong>{{ $userName }}</strong>
        <small>{{ $workspaceLabel }}</small>
    </div>
</aside>
