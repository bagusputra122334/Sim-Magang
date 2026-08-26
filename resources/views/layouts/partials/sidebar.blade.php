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
                ['icon' => 'bi-people-fill', 'label' => 'Magang Aktif', 'route' => 'admin.active-interns.index', 'params' => []],
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
        <a class="brand-mark d-flex align-items-center gap-3 text-decoration-none" href="{{ $dashboardUrl }}" aria-label="SIM-MAGANG Dashboard">
            <img src="{{ asset('storage/image/logo.png') }}" alt="Logo Tuban" class="h-10 w-auto object-contain flex-shrink-0 sidebar-brand-logo">
            <div>
                <h1 class="text-white font-bold text-lg leading-tight tracking-wide mb-0" style="font-size: 1.05rem; font-weight: 700; color: #ffffff;">SIM-MAGANG</h1>
                <p class="text-slate-400 text-xs font-medium mb-0" style="font-size: 0.75rem; color: #94a3b8;">Diskominfo SP Kab. Tuban</p>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">
        @foreach ($sidebarMenu as $menu)
            @php
                $menuRoute = $menu['route'] ?? null;
                $menuParams = $menu['params'] ?? [];
                $menuHref = '#';
                if ($menuRoute && \Illuminate\Support\Facades\Route::has($menuRoute)) {
                    try { $menuHref = route($menuRoute, $menuParams); } catch (\Throwable $e) { $menuHref = '#'; }
                } elseif (!empty($menu['url'])) {
                    $menuHref = url($menu['url']);
                }
                $isActive = false;
                if (!empty($menuRoute) && is_string($menuRoute)) {
                    if ($currentRoute === $menuRoute) {
                        $isActive = true;
                    } elseif (str_starts_with($currentRoute, $menuRoute . '.')) {
                        $isActive = true;
                    }
                }
                if (!empty($menu['activeWhen']) && is_array($menu['activeWhen'])) {
                    foreach ($menu['activeWhen'] as $r) {
                        if (str_starts_with($currentRoute, $r) || $currentRoute === $r) { $isActive = true; break; }
                    }
                }
            @endphp
            <a class="nav-link {{ $isActive ? 'active' : '' }}"
               href="{{ $menuHref }}"
               @if($isActive) aria-current="page" @endif>
                <span class="nav-icon"><i class="bi {{ $menu['icon'] ?? 'bi-circle' }}" aria-hidden="true"></i></span>
                <span class="nav-text">{{ $menu['label'] ?? 'Menu' }}</span>
            </a>
        @endforeach
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
