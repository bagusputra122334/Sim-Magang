<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SIMAGANG - Sistem Informasi Magang Pemerintah Kabupaten Tuban">

    <title>@yield('title', 'Dashboard') — SIMAGANG Pemerintah Kabupaten Tuban</title>

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

<body class="bg-slate-50">
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        @include('layouts.partials.sidebar')

        <div class="admin-main d-flex flex-column min-vh-100 flex-grow-1">
            @include('layouts.partials.navbar')

            <main class="dashboard-content flex-grow-1">
                <div class="container-fluid px-3 px-lg-4 pt-2 pb-8">

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-2.5 py-2 px-3" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill fs-6 me-2.5 mt-0.5"></i>
                                <div class="flex-grow-1 text-xs">
                                    <h6 class="alert-heading fw-semibold mb-1 text-xs">
                                        Terdapat kesalahan pada input data:
                                    </h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li class="mb-0.5">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="btn-close ms-2 p-2"
                                        data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
                        </div>
                    @endif

                    @foreach(['success', 'error', 'info', 'warning'] as $type)
                        @if(session()->has($type))
                            @php
                                $icon = match($type) {
                                    'success' => 'bi-check-circle-fill',
                                    'error'   => 'bi-x-circle-fill',
                                    'info'    => 'bi-info-circle-fill',
                                    'warning' => 'bi-exclamation-diamond-fill',
                                    default   => 'bi-info-circle-fill',
                                };
                            @endphp
                            <div class="alert alert-{{ $type }} alert-dismissible fade show mb-2.5 py-2 px-3" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $icon }} fs-6 me-2.5"></i>
                                    <div class="flex-grow-1 text-xs">{!! session($type) !!}</div>
                                    <button type="button" class="btn-close ms-2 p-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if(auth()->check() && auth()->user()->hasDefaultPassword() && !request()->routeIs('profile*'))
                        <div class="alert alert-danger bg-rose-50 border border-rose-300 text-rose-800 dark:bg-rose-950/40 dark:text-rose-200 dark:border-rose-800 px-3.5 py-2.5 rounded-xl mb-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2.5 shadow-xs" role="alert">
                            <div class="d-flex align-items-center gap-2.5">
                                <i class="bi bi-shield-exclamation fs-4 text-rose-600 dark:text-rose-400 flex-shrink-0"></i>
                                <div>
                                    <span class="fw-bold text-xs sm:text-sm d-block">Peringatan Keamanan Akun:</span>
                                    <span class="text-xs text-rose-700 dark:text-rose-300">Akun Anda saat ini masih menggunakan password default. Harap segera ganti kata sandi Anda di menu Pengaturan Akun demi menjaga keamanan akun dan data magang instansi Anda.</span>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-danger text-nowrap flex-shrink-0 font-bold py-1.5 px-3 text-xs rounded-lg shadow-xs">
                                <i class="bi bi-key-fill me-1"></i> Ganti Password Sekarang
                            </a>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    <script>
        window.adminHMDUser = {
            name: @json(auth()->user()?->name ?? 'Guest'),
            workspace: @json(
                auth()->user()?->isAdmin()
                    ? 'Workspace Admin'
                    : (auth()->user()?->isPembimbing()
                        ? 'Workspace Pembimbing'
                        : (auth()->user()?->isParticipant() ? 'Workspace Peserta' : 'Public Workspace')
                    )
            ),
            avatar: @json(auth()->user()?->foto_url ?? asset('assets/images/avatar/avatar.jpg'))
        };
    </script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @include('components.confirmation-modal')
    @stack('scripts')
</body>
</html>
