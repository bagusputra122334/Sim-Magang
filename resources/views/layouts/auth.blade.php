<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SIMAGANG - Sistem Informasi Magang Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban">

    <title>@yield('title', 'Login') — SIMAGANG Diskominfo SP Tuban</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('traveland/images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('traveland/images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('traveland/images/logo.png') }}">

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

<body>
    <button class="icon-button theme-toggle auth-theme-toggle position-fixed top-0 end-0 m-3" type="button" data-theme-toggle
            aria-label="Switch color theme" title="Switch color theme" style="z-index: 1050;">
        <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
    </button>

    <style>
        @keyframes simagangEarthPan {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .simagang-earth-bg {
            background-size: 150% 150% !important; 
            animation: simagangEarthPan 90s ease-in-out infinite;
        }
    </style>

    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 py-2 px-3 simagang-earth-bg" 
         style="background: linear-gradient(rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.6)), url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1920&auto=format&fit=crop') no-repeat center center;">
        
        <!-- BRANDING SECTION: Tuban Logo and Titles (Inline Flex, Compact) -->
        <div class="d-flex flex-row align-items-center justify-content-center gap-3 mb-2" style="z-index: 10;">
            <img src="{{ asset('traveland/images/logo.png') }}" alt="Logo Tuban" style="height: 48px; width: auto;">
            <div class="text-start">
                <h4 class="text-white mb-0 fw-bold" style="font-size: 1.25rem;">Sistem Informasi Magang</h4>
                <p class="text-white-50 mb-0" style="font-size: 0.875rem;">Diskominfo SP Tuban</p>
            </div>
        </div>
        
        <!-- FORM SECTION -->
        <div class="card shadow-lg bg-white text-dark p-3 p-md-4 w-100" style="max-width: 440px; border-radius: 1rem; z-index: 10;">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-2 p-2 px-3" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill fs-6 me-2 mt-1"></i>
                        <div class="flex-grow-1 small">
                            @if($errors->count() === 1)
                                {{ $errors->first() }}
                            @else
                                <h6 class="alert-heading fw-semibold mb-1 small">
                                    Perbaiki kesalahan berikut:
                                </h6>
                                <ul class="mb-0 ps-3 small">
                                    @foreach($errors->all() as $error)
                                        <li class="mb-0">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <button type="button" class="btn-close p-2 ms-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
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
                    <div class="alert {{ $alertClass }} alert-dismissible fade show mb-2 p-2 px-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi {{ $icon }} fs-6 me-2"></i>
                            <div class="flex-grow-1 small">{!! session($type) !!}</div>
                            <button type="button" class="btn-close p-2 ms-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    </div>
                @endif
            @endforeach

            @yield('content')

            @php
                $currentRoute = request()->route()?->getName() ?? '';
            @endphp

            <div class="text-center mt-2 pt-2 border-top small">
                @if($currentRoute === 'login' && Route::has('register'))
                    <span class="text-muted">Belum punya akun?</span> <a href="{{ route('register') }}" class="fw-semibold text-indigo-600 hover:text-indigo-800 text-decoration-none">Daftar Sekarang</a>
                @elseif($currentRoute === 'register' && Route::has('login'))
                    <span class="text-muted">Sudah terdaftar?</span> <a href="{{ route('login') }}" class="fw-semibold text-indigo-600 hover:text-indigo-800 text-decoration-none">Sign In</a>
                @elseif(in_array($currentRoute, ['password.request', 'password.email']) && Route::has('login'))
                    <span class="text-muted">Ingat kredensial?</span> <a href="{{ route('login') }}" class="fw-semibold text-indigo-600 hover:text-indigo-800 text-decoration-none">Kembali ke Login</a>
                @elseif(str_starts_with($currentRoute, 'password.') && Route::has('login'))
                    <a href="{{ route('login') }}" class="fw-semibold text-indigo-600 hover:text-indigo-800 text-decoration-none">&larr; Kembali ke halaman Login</a>
                @elseif(str_starts_with($currentRoute, 'verification.'))
                    <form method="POST" action="{{ route('logout') }}" class="d-inline" id="auth-logout-form">@csrf</form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('auth-logout-form').submit();" class="fw-semibold text-indigo-600 hover:text-indigo-800 text-decoration-none">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
