<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>404 — Halaman Tidak Ditemukan | SIMAGANG Diskominfo SP Tuban</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('traveland/images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('traveland/images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col justify-between antialiased selection:bg-blue-500 selection:text-white">

    <!-- Header Brand Bar -->
    <header class="w-full py-4 px-6 sm:px-12 border-b border-slate-200/80 dark:border-slate-800/80 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('traveland/images/logo.png') }}" alt="SIMAGANG Logo" class="h-10 w-auto group-hover:scale-105 transition-transform duration-200">
                <div class="flex flex-col">
                    <span class="font-bold text-lg text-slate-900 dark:text-white leading-tight tracking-tight">SIMAGANG</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Diskominfo SP Kab. Tuban</span>
                </div>
            </a>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                <i class="bi bi-house-door"></i>
                <span>Beranda</span>
            </a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Gradient Glows -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-xl w-full text-center space-y-8 relative z-10 py-12">
            <!-- 404 Badge & Graphic Display -->
            <div class="relative inline-block">
                <span class="text-8xl sm:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 dark:from-blue-400 dark:via-indigo-400 dark:to-blue-300 tracking-tight select-none">
                    404
                </span>
                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-4 py-1 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 rounded-full text-blue-700 dark:text-blue-300 text-xs font-semibold uppercase tracking-wider shadow-sm whitespace-nowrap">
                    <i class="bi bi-exclamation-triangle-fill text-amber-500 mr-1.5"></i> Error 404 — Page Not Found
                </div>
            </div>

            <!-- Headline & Description -->
            <div class="space-y-3 pt-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Waduh! Halaman Tidak Ditemukan
                </h1>
                <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed max-w-md mx-auto">
                    Maaf, halaman yang Anda tuju mungkin telah dihapus, diubah namanya, atau memang tidak pernah ada.
                </p>
            </div>

            <!-- Call to Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4">
                <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-200 hover:-translate-y-0.5">
                    <i class="bi bi-house-door-fill text-base"></i>
                    <span>Kembali ke Beranda</span>
                </a>
                <button onclick="window.history.back()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-200 font-semibold text-sm rounded-xl border border-slate-200 dark:border-slate-700 transition-all duration-200 hover:-translate-y-0.5">
                    <i class="bi bi-arrow-left"></i>
                    <span>Halaman Sebelumnya</span>
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-4 text-center border-t border-slate-200/80 dark:border-slate-800/80 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm text-xs text-slate-500 dark:text-slate-400">
        <p>&copy; {{ date('Y') }} SIMAGANG — Diskominfo SP Kabupaten Tuban. All rights reserved.</p>
    </footer>

</body>
</html>
