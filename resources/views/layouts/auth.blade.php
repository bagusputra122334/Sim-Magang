<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SIM-MAGANG - Sistem Informasi Magang Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban">

    <title>@yield('title', 'Login') — SIM-MAGANG Diskominfo SP Tuban</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <script>
        (function() {
            try {
                var t = localStorage.getItem('adminHMD.colorTheme');
                if (!t && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    t = 'dark';
                }
                if (t === 'dark' || t === 'light') {
                    document.documentElement.setAttribute('data-theme', t);
                    document.documentElement.setAttribute('data-bs-theme', t);
                }
            } catch(e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @stack('styles')
</head>

<body class="auth-split-body">
    <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle
            aria-label="Switch color theme" title="Switch color theme">
        <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
    </button>

    <div class="auth-split-wrapper">
        <section class="auth-split-brand" aria-label="Branding SIM-MAGANG">
            <div class="auth-split-brand-inner">
                <div class="auth-split-brand-logo">
                    <img src="{{ asset('storage/image/logo.png') }}" alt="SIM-MAGANG Logo" class="auth-brand-logo-img">
                    <div class="auth-split-brand-logo-text">
                        <strong>Diskominfo SP Tuban</strong>
                        <span>Dinas Komunikasi, Informatika, Statistik dan Persandian</span>
                    </div>
                </div>

                <div class="auth-split-brand-hero">
                    <h1>Sistem Informasi<br><span>Magang</span></h1>
                    <p class="auth-split-brand-tagline">
                        "Membangun Talenta Digital untuk Pelayanan Publik"
                    </p>
                </div>

                <ul class="auth-split-brand-features" aria-label="Keunggulan Program">
                    <li>
                        <i class="bi bi-check2" aria-hidden="true"></i>
                        <span>Pendaftaran magang digital yang cepat dan transparan</span>
                    </li>
                    <li>
                        <i class="bi bi-check2" aria-hidden="true"></i>
                        <span>Monitoring status pendaftaran secara real-time</span>
                    </li>
                    <li>
                        <i class="bi bi-check2" aria-hidden="true"></i>
                        <span>Pengelolaan dokumen resmi yang aman dan terpusat</span>
                    </li>
                    <li>
                        <i class="bi bi-check2" aria-hidden="true"></i>
                        <span>Didukung oleh Dinas Komunikasi, Informatika, Statistik dan Persandian</span>
                    </li>
                </ul>
            </div>

            <div class="auth-split-brand-footer">
                <span>&copy; {{ now()->year }} Diskominfo Tuban</span>
                <span>
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    Kabupaten Tuban, Jawa Timur
                </span>
            </div>
        </section>

        <main class="auth-split-main">
            <div class="auth-split-card">
                <div class="auth-split-mobile-brand">
                    <img src="{{ asset('storage/image/logo.png') }}" alt="SIM-MAGANG Logo" class="auth-mobile-logo-img">
                    <div class="auth-split-mobile-brand-text">
                        <strong>SIM-MAGANG</strong>
                        <span>Diskominfo Kabupaten Tuban</span>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill fs-5 me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                @if($errors->count() === 1)
                                    {{ $errors->first() }}
                                @else
                                    <h6 class="alert-heading fw-semibold mb-2">
                                        Perbaiki kesalahan berikut:
                                    </h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li class="mb-1">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <button type="button" class="btn-close ms-2"
                                    data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    </div>
                @endif

                @foreach(['success', 'error', 'info', 'warning'] as $type)
                    @if(session()->has($type))
                        @php
                            $icon = match($type) {
                                'success' => 'bi-check-circle-fill text-success',
                                'error'   => 'bi-x-circle-fill text-danger',
                                'info'    => 'bi-info-circle-fill text-info',
                                'warning' => 'bi-exclamation-diamond-fill text-warning',
                                default   => 'bi-info-circle-fill',
                            };
                            $alertClass = match($type) {
                                'success' => 'alert-success',
                                'error'   => 'alert-danger',
                                'info'    => 'alert-info',
                                'warning' => 'alert-warning',
                                default   => 'alert-secondary',
                            };
                        @endphp
                        <div class="alert {{ $alertClass }} alert-dismissible fade show mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi {{ $icon }} fs-5 me-2"></i>
                                <div class="flex-grow-1">{!! session($type) !!}</div>
                                <button type="button" class="btn-close ms-2"
                                        data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
                        </div>
                    @endif
                @endforeach

                @yield('content')

                @php
                    $currentRoute = request()->route()?->getName() ?? '';
                @endphp

                <div class="auth-split-footer">
                    @if($currentRoute === 'login' && Route::has('register'))
                        Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                    @elseif($currentRoute === 'register' && Route::has('login'))
                        Sudah terdaftar? <a href="{{ route('login') }}">Sign In</a>
                    @elseif(in_array($currentRoute, ['password.request', 'password.email']) && Route::has('login'))
                        Ingat kredensial? <a href="{{ route('login') }}">Kembali ke Login</a>
                    @elseif(str_starts_with($currentRoute, 'password.') && Route::has('login'))
                        <a href="{{ route('login') }}">&larr; Kembali ke halaman Login</a>
                    @elseif(str_starts_with($currentRoute, 'verification.'))
                        <form method="POST" action="{{ route('logout') }}" class="d-inline" id="auth-logout-form">@csrf</form>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('auth-logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
