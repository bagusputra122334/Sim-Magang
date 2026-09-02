<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SIM-MAGANG - Sistem Informasi Magang Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban">

    <title>@yield('title', 'Dashboard') — SIM-MAGANG Diskominfo SP Tuban</title>

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
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        @include('layouts.partials.sidebar')

        <div class="admin-main d-flex flex-column min-vh-100 flex-grow-1">
            @include('layouts.partials.navbar')

            <main class="dashboard-content flex-grow-1">
                <div class="container-fluid px-3 px-lg-4 py-4">

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill fs-5 me-3 mt-1"></i>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading fw-semibold mb-2">
                                        Terdapat kesalahan pada input data:
                                    </h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li class="mb-1">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="btn-close ms-3"
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
                            <div class="alert alert-{{ $type }} alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $icon }} fs-5 me-3"></i>
                                    <div class="flex-grow-1">{!! session($type) !!}</div>
                                    <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                </div>
                            </div>
                        @endif
                    @endforeach

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
                    : (auth()->user()?->isParticipant() ? 'Workspace Peserta' : 'Public Workspace')
            ),
            avatar: @json(auth()->user()?->foto_url ?? asset('assets/images/avatar/avatar.jpg'))
        };
    </script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
