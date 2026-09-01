<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $guide['summary'] }} — SIM-MAGANG Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.">

    <!--====== Title ======-->
    <title>{{ $guide['title'] }} — SIM-MAGANG Diskominfo SP Tuban</title>

    <!--====== Favicon Icon ======-->
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!--====== Script Theme Initializer ======-->
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

    <!--====== Stylesheets ======-->
    <link rel="stylesheet" href="{{ asset('traveland/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('traveland/css/LineIcons.2.0.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('traveland/css/bootstrap.4.5.2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('traveland/css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('traveland/css/style.css') }}">

    <style>
        :root, html[data-theme="light"] {
            --sim-primary: #0d6efd;
            --sim-primary-hover: #0b5ed7;
            --sim-primary-dark: #043873;
            --sim-primary-subtle: rgba(13, 110, 253, 0.08);

            --sim-surface: #ffffff;
            --sim-surface-soft: #f8fafc;
            --sim-surface-card: #ffffff;
            --sim-border: #e2e8f0;
            --sim-border-subtle: #f1f5f9;
            --sim-text: #0f172a;
            --sim-text-muted: #64748b;
            --sim-text-secondary: #334155;
            --sim-shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.05);
            --sim-shadow-md: 0 8px 24px rgba(15, 23, 42, 0.08);
            --sim-shadow-lg: 0 16px 36px rgba(13, 110, 253, 0.12);

            --nav-bg: rgba(255, 255, 255, 0.96);
            --nav-border: #e2e8f0;
            --nav-brand-text: #0f172a;
            --nav-brand-sub: #64748b;
            --nav-link-color: #334155;
            --nav-link-hover: #0d6efd;
            --nav-btn-outline-border: #0d6efd;
            --nav-btn-outline-color: #0d6efd;
            --nav-btn-outline-hover-bg: #0d6efd;
            --nav-btn-outline-hover-color: #ffffff;
            --nav-toggle-bg: #f1f5f9;
            --nav-toggle-color: #334155;
            --nav-toggle-border: #e2e8f0;

            --footer-bg: #081124;
            --footer-text: #94a3b8;
            --footer-title: #ffffff;
            --footer-border: rgba(255, 255, 255, 0.08);
        }

        html[data-theme="dark"] {
            --sim-primary: #3b82f6;
            --sim-primary-hover: #60a5fa;
            --sim-primary-dark: #1d4ed8;
            --sim-primary-subtle: rgba(59, 130, 246, 0.15);

            --sim-surface: #0b1329;
            --sim-surface-soft: #111e38;
            --sim-surface-card: #152445;
            --sim-border: #1e3a6a;
            --sim-border-subtle: #192d52;
            --sim-text: #f8fafc;
            --sim-text-muted: #94a3b8;
            --sim-text-secondary: #cbd5e1;
            --sim-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
            --sim-shadow-md: 0 8px 24px rgba(0, 0, 0, 0.4);
            --sim-shadow-lg: 0 16px 36px rgba(0, 0, 0, 0.5);

            --nav-bg: rgba(11, 19, 41, 0.96);
            --nav-border: #1e3a6a;
            --nav-brand-text: #f8fafc;
            --nav-brand-sub: #94a3b8;
            --nav-link-color: #cbd5e1;
            --nav-link-hover: #3b82f6;
            --nav-btn-outline-border: #3b82f6;
            --nav-btn-outline-color: #3b82f6;
            --nav-btn-outline-hover-bg: #3b82f6;
            --nav-btn-outline-hover-color: #ffffff;
            --nav-toggle-bg: #152445;
            --nav-toggle-color: #f8fafc;
            --nav-toggle-border: #1e3a6a;

            --footer-bg: #050b18;
            --footer-text: #94a3b8;
            --footer-title: #ffffff;
            --footer-border: rgba(255, 255, 255, 0.06);
        }

        body {
            font-family: "Poppins", system-ui, -apple-system, sans-serif;
            background-color: var(--sim-surface);
            color: var(--sim-text);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .gov-topbar {
            background: #0b1329;
            color: #94a3b8;
            font-size: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            z-index: 1001;
        }

        .gov-topbar-link {
            color: #ffffff !important;
            transition: color 0.2s ease, opacity 0.2s ease;
            cursor: pointer;
            text-decoration: none !important;
            position: relative;
            z-index: 1002;
            pointer-events: auto;
        }

        .gov-topbar-link:hover,
        .gov-topbar-link:focus {
            color: var(--sim-primary) !important;
            text-decoration: underline !important;
            opacity: 0.95;
        }

        .gov-topbar-link i {
            transition: transform 0.2s ease;
        }

        .gov-topbar-link:hover i {
            transform: scale(1.1);
        }

        .header_navbar {
            background: var(--nav-bg);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--nav-border);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 99;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            padding: 0;
        }

        .brand-logo-img {
            height: 96px;
            width: auto;
            max-width: 280px;
            object-fit: contain;
            display: inline-block;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .brand-logo-img:hover {
            transform: scale(1.03);
        }

        .footer-logo-img {
            height: 64px;
            width: auto;
            max-width: 180px;
            object-fit: contain;
            display: inline-block;
            flex-shrink: 0;
        }

        .brand-text {
            color: var(--nav-brand-text);
            font-size: 20px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.3px;
            transition: color 0.3s ease;
        }

        .brand-sub {
            color: var(--nav-brand-sub);
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: color 0.3s ease;
        }

        .header_navbar .navbar-nav .nav-item a {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--nav-link-color);
            padding: 10px 10px;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .header_navbar .navbar-nav .nav-item:hover > a,
        .header_navbar .navbar-nav .nav-item.active > a {
            color: var(--nav-link-hover);
        }

        .main-btn {
            background-color: var(--sim-primary);
            color: #ffffff !important;
            font-weight: 600;
            font-size: 14px;
            height: 42px;
            line-height: 42px;
            padding: 0 22px;
            border-radius: 8px;
            border: 1px solid var(--sim-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(13, 110, 253, 0.25);
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .main-btn:hover {
            background-color: var(--sim-primary-hover);
            transform: translateY(-2px);
            color: #ffffff !important;
            text-decoration: none;
        }

        .main-btn-outline {
            background-color: transparent;
            color: var(--nav-btn-outline-color) !important;
            border: 1.5px solid var(--nav-btn-outline-border);
            font-weight: 600;
            font-size: 14px;
            height: 42px;
            line-height: 39px;
            padding: 0 18px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .main-btn-outline:hover {
            background-color: var(--sim-primary);
            border-color: var(--sim-primary);
            color: #ffffff !important;
            text-decoration: none;
        }

        .theme-toggle-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--nav-toggle-bg);
            color: var(--nav-toggle-color);
            border: 1px solid var(--nav-toggle-border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .theme-toggle-btn:hover {
            border-color: var(--sim-primary);
            color: var(--sim-primary);
        }

        /* Portal Button Styling */
        .nav-portal-btn {
            font-size: 13px;
            height: 40px;
            line-height: 38px;
            padding: 0 16px;
            white-space: nowrap;
            gap: 6px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.25s ease;
            max-width: 100%;
        }

        @media (min-width: 1200px) and (max-width: 1399.98px) {
            .header_navbar .navbar-nav .nav-item a {
                padding: 16px 8px;
                font-size: 13.5px;
            }
            .brand-logo-img {
                height: 88px;
                max-width: 250px;
            }
            .brand-text { font-size: 19px; }
            .brand-sub { font-size: 11px; }
            .nav-portal-btn {
                font-size: 12.5px;
                padding: 0 12px;
                height: 38px;
                line-height: 36px;
            }
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            .brand-logo-img {
                height: 78px;
                max-width: 220px;
            }
            .brand-text { font-size: 17px; }
            .brand-sub { font-size: 10px; }
            .header_navbar .navbar-nav .nav-item a {
                padding: 14px 5px;
                font-size: 12px;
            }
            .theme-toggle-btn {
                width: 34px;
                height: 34px;
                font-size: 14px;
                margin-right: 6px !important;
            }
            .nav-portal-btn {
                font-size: 11.5px;
                padding: 0 8px;
                height: 34px;
                line-height: 32px;
                gap: 4px;
                border-radius: 6px;
            }
            .nav-portal-btn i {
                font-size: 12px;
            }
        }



        /* Article Main Styles */
        .guide-article-card {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: var(--sim-shadow-sm);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .guide-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--sim-primary-subtle);
            color: var(--sim-primary);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }

        .guide-title {
            font-size: 32px;
            font-weight: 800;
            color: var(--sim-text);
            line-height: 1.3;
            margin-bottom: 16px;
            letter-spacing: -0.3px;
        }

        @media (max-width: 767px) {
            .guide-title { font-size: 24px; }
            .guide-article-card { padding: 24px 18px; }
            .guide-featured-image { height: 220px; }
        }

        .guide-meta-bar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
            font-size: 13px;
            color: var(--sim-text-muted);
            padding-bottom: 22px;
            border-bottom: 1px solid var(--sim-border);
            margin-bottom: 28px;
        }

        .guide-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .guide-featured-image {
            width: 100%;
            height: 360px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid var(--sim-border);
            margin-bottom: 32px;
            box-shadow: var(--sim-shadow-sm);
        }

        /* Article Typography & Steps */
        .guide-content h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--sim-text);
            margin-top: 32px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .guide-content p, .guide-content li {
            font-size: 15px;
            line-height: 1.75;
            color: var(--sim-text-secondary);
        }

        .guide-content strong {
            color: var(--sim-text);
        }

        .guide-callout {
            background-color: var(--sim-surface-soft);
            border-left: 4px solid var(--sim-primary);
            border-radius: 0 14px 14px 0;
            padding: 20px 22px;
            margin: 24px 0;
            border-top: 1px solid var(--sim-border);
            border-right: 1px solid var(--sim-border);
            border-bottom: 1px solid var(--sim-border);
        }

        .guide-callout-title {
            font-size: 15.5px;
            font-weight: 700;
            color: var(--sim-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .guide-step-card {
            background: var(--sim-surface-soft);
            border: 1px solid var(--sim-border);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all 0.25s ease;
        }

        .guide-step-card:hover {
            border-color: var(--sim-primary);
            box-shadow: var(--sim-shadow-sm);
            transform: translateY(-2px);
        }

        .guide-step-num {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--sim-primary);
            color: #ffffff;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        /* Sidebar Widgets */
        .guide-sidebar-card {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 18px;
            padding: 26px 22px;
            box-shadow: var(--sim-shadow-sm);
            margin-bottom: 24px;
        }

        .guide-sidebar-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--sim-text);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--sim-border);
            padding-bottom: 12px;
        }

        .guide-other-item {
            display: block;
            padding: 12px 14px;
            border-radius: 10px;
            background: var(--sim-surface-soft);
            border: 1px solid var(--sim-border);
            text-decoration: none !important;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .guide-other-item:hover, .guide-other-item:focus {
            border-color: var(--sim-primary);
            background: var(--sim-primary-subtle);
            transform: translateX(3px);
            text-decoration: none !important;
        }

        .guide-other-item strong {
            display: block;
            font-size: 13.5px;
            color: var(--sim-text);
            margin-bottom: 4px;
            line-height: 1.35;
        }

        .guide-other-item:hover strong {
            color: var(--sim-primary);
        }

        .guide-other-item small {
            color: var(--sim-text-muted);
            font-size: 12px;
        }

        /* CTA Banner */
        .guide-cta-card {
            background: linear-gradient(135deg, #0d6efd 0%, #043873 100%);
            border-radius: 18px;
            padding: 30px 24px;
            color: #ffffff;
            box-shadow: var(--sim-shadow-lg);
            text-align: center;
        }

        .guide-cta-card h4 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .guide-cta-card p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13.5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!--====== GOV TOPBAR ======-->
    <div class="gov-topbar py-2 d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <a href="https://tubankab.go.id/" target="_blank" rel="noopener noreferrer" class="d-inline-flex align-items-center gap-2 font-weight-bold text-white text-decoration-none gov-topbar-link" title="Buka Portal Resmi Pemerintah Kabupaten Tuban">
                    <i class="bi bi-bank text-primary"></i>
                    <span>Pemerintah Kabupaten Tuban</span>
                </a>
                <span class="text-secondary mx-1">•</span>
                <span>Dinas Komunikasi, Informatika, Statistik dan Persandian</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span><i class="bi bi-geo-alt mr-1 text-primary"></i> Jl. Veteran No. 2, Tuban</span>
                <span><i class="bi bi-telephone mr-1 text-primary"></i> (0356) 321000</span>
            </div>
        </div>
    </div>

    <!--====== HEADER / NAVBAR ======-->
    <nav class="header_navbar">
            <div class="flex items-center justify-between w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                <a class="navbar-brand flex-shrink-0 mr-xl-3" href="{{ url('/') }}">
                    <img src="{{ asset('storage/image/logo.png') }}" alt="SIM-MAGANG Logo" class="brand-logo-img">
                    <div>
                        <span class="brand-text d-block">SIM-MAGANG</span>
                        <span class="brand-sub d-block">Diskominfo SP Kab. Tuban</span>
                    </div>
                </a>

                <div class="hidden md:flex xl:flex items-center gap-6">
                    <ul class="navbar-nav d-flex flex-row align-items-center mr-3">
                        <li class="nav-item"><a href="{{ url('/#home') }}">Beranda</a></li>
                        <li class="nav-item"><a href="{{ url('/#about') }}">Tentang</a></li>
                        <li class="nav-item"><a href="{{ url('/#positions') }}">Formasi</a></li>
                        <li class="nav-item"><a href="{{ url('/#services') }}">Keunggulan</a></li>
                        <li class="nav-item"><a href="{{ url('/#alur') }}">Alur</a></li>
                        <li class="nav-item"><a href="{{ url('/#faq') }}">FAQ</a></li>
                        <li class="nav-item active"><a href="{{ url('/#blog') }}">Panduan</a></li>
                        <li class="nav-item"><a href="{{ url('/#contact') }}">Kontak</a></li>
                    </ul>

                    <button class="theme-toggle-btn mr-3" type="button" id="themeToggleBtn" aria-label="Toggle theme" title="Ubah Tema Gelap/Terang">
                        <i class="bi bi-moon-stars" id="themeIcon"></i>
                    </button>

                    <form action="{{ url('/') }}#search-results" method="GET" class="relative hidden md:flex items-center w-56 lg:w-64" x-data="{ searchQuery: '{{ request('search') }}' }">
                        <!-- Icon: Only shows when input is empty -->
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none" x-show="searchQuery.length === 0" x-transition.opacity>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        
                        <!-- Input: Padding adjusts dynamically based on content length -->
                        <input type="text" 
                               name="search" 
                               x-model="searchQuery" 
                               autocomplete="off"
                               placeholder="Cari formasi, panduan..." 
                               class="w-full pr-4 py-2 bg-slate-50 border border-slate-200 rounded-full text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-inner"
                               :class="searchQuery.length > 0 ? 'pl-4' : 'pl-10'">
                    </form>
                </div>

                <div class="d-xl-none d-flex align-items-center gap-2">
                    <button class="theme-toggle-btn mr-2" type="button" id="mobileThemeToggleBtn" aria-label="Toggle theme">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    <a href="{{ url('/') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!--====== MAIN GUIDE CONTENT ======-->
    <main class="py-5" id="main-content">
        <div class="container">
            <div class="row">
                <!-- Left Column: Article Body -->
                <div class="col-lg-8 mb-4">
                    <article class="guide-article-card">
                        <div class="guide-tag">
                            <i class="bi {{ $guide['badge_icon'] }}"></i>
                            <span>{{ $guide['badge'] }}</span>
                        </div>

                        <h1 class="guide-title">{{ $guide['title'] }}</h1>

                        <div class="guide-meta-bar">
                            <div class="guide-meta-item">
                                <i class="bi bi-building text-primary"></i>
                                <span>{{ $guide['meta_author'] }}</span>
                            </div>
                            <div class="guide-meta-item">
                                <i class="bi bi-bookmark text-primary"></i>
                                <span>{{ $guide['meta_category'] }}</span>
                            </div>
                            <div class="guide-meta-item">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Layanan Resmi 24/7</span>
                            </div>
                        </div>

                        @php
                            $guideImage = $guide['image'];
                            if ($slug === 'surat-balasan') {
                                $guideImage = file_exists(public_path('storage/image/3.png'))
                                    ? 'storage/image/3.png'
                                    : (file_exists(public_path('storage/image/gambar 3.png')) ? 'storage/image/gambar 3.png' : 'storage/image/3.png');
                            }
                        @endphp
                        <img src="{{ asset($guideImage) }}" alt="{{ $guide['title'] }}" class="guide-featured-image">

                        <div class="guide-content">
                            @if($slug === 'pendaftaran')
                                <p class="lead font-weight-normal mb-4" style="font-size: 16px; color: var(--sim-text);">
                                    Pendaftaran Program Magang di Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban dilakukan secara daring melalui portal SIM-MAGANG. Calon peserta perlu menyiapkan dokumen persyaratan sebelum mengajukan pendaftaran pada formasi yang tersedia.
                                </p>

                                <h3><i class="bi bi-card-checklist text-primary"></i> 1. Dokumen Persyaratan Pendaftaran</h3>
                                <p>Terdapat 3 (tiga) berkas dokumen persyaratan berformat PDF yang wajib diunggah saat mendaftar:</p>

                                <div class="guide-step-card">
                                    <div class="guide-step-num"><i class="bi bi-file-earmark-person"></i></div>
                                    <div>
                                        <strong class="d-block mb-1" style="font-size: 15.5px;">Curriculum Vitae (CV) Terbaru</strong>
                                        <p class="mb-0 small text-muted">Format <strong>PDF (Maks. 2 MB)</strong>. Memuat data diri, kontak aktif, riwayat pendidikan, serta keahlian atau portofolio yang relevan.</p>
                                    </div>
                                </div>

                                <div class="guide-step-card">
                                    <div class="guide-step-num"><i class="bi bi-envelope-paper"></i></div>
                                    <div>
                                        <strong class="d-block mb-1" style="font-size: 15.5px;">Surat Pengantar Institusi Pendidikan</strong>
                                        <p class="mb-0 small text-muted">Format <strong>PDF (Maks. 3 MB)</strong>. Surat permohonan resmi berkop perguruan tinggi atau sekolah yang ditandatangani oleh pejabat berwenang.</p>
                                    </div>
                                </div>

                                <div class="guide-step-card">
                                    <div class="guide-step-num"><i class="bi bi-journal-text"></i></div>
                                    <div>
                                        <strong class="d-block mb-1" style="font-size: 15.5px;">Proposal Magang</strong>
                                        <p class="mb-0 small text-muted">Format <strong>PDF (Maks. 5 MB)</strong>. Memuat latar belakang, tujuan magang, bidang kerja yang diminati, serta usulan periode pelaksanaan magang.</p>
                                    </div>
                                </div>

                                <h3><i class="bi bi-diagram-3-fill text-primary"></i> 2. Langkah Pendaftaran Daring</h3>
                                <div class="guide-step-card">
                                    <div class="guide-step-num">1</div>
                                    <div>
                                        <strong>Registrasi Akun:</strong> Buat akun pada menu <a href="{{ route('register') }}" class="text-primary font-weight-bold">Daftar Akun</a> dengan mengisi nama lengkap, alamat email aktif, dan kata sandi.
                                    </div>
                                </div>

                                <div class="guide-step-card">
                                    <div class="guide-step-num">2</div>
                                    <div>
                                        <strong>Lengkapi Profil:</strong> Pilih kategori peserta (Mahasiswa atau Siswa SMK), lalu lengkapi biodata, NIK, identitas institusi pendidikan, nomor kontak WhatsApp, dan pas foto formal.
                                    </div>
                                </div>

                                <div class="guide-step-card">
                                    <div class="guide-step-num">3</div>
                                    <div>
                                        <strong>Pilih Formasi & Unggah Berkas:</strong> Buka menu <a href="{{ route('participant.registrations.create') }}" class="text-primary font-weight-bold">Daftar Magang</a>, tentukan formasi yang diminati, tentukan tanggal mulai dan selesai magang, lalu unggah file CV, Surat Pengantar, dan Proposal Magang.
                                    </div>
                                </div>

                                <div class="guide-step-card">
                                    <div class="guide-step-num">4</div>
                                    <div>
                                        <strong>Kirim Pendaftaran:</strong> Kirim pendaftaran untuk diverifikasi. Sistem menerbitkan Nomor Pendaftaran resmi berformat <code>MAGANG-YYYY-XXXX</code> serta mengirimkan konfirmasi via email.
                                    </div>
                                </div>

                                <div class="guide-callout">
                                    <div class="guide-callout-title"><i class="bi bi-info-circle-fill"></i> Ketentuan Tambahan Berkas:</div>
                                    <p class="mb-0 small">Pastikan hasil pemindaian (scan) dokumen jelas dan tidak buram, nama peserta pada dokumen sesuai dengan profil akun, serta kontak yang didaftarkan aktif untuk keperluan koordinasi.</p>
                                </div>

                            @elseif($slug === 'kategori-peserta')
                                <p class="lead font-weight-normal mb-4" style="font-size: 16px; color: var(--sim-text);">
                                    Program Magang terbuka bagi mahasiswa perguruan tinggi dan siswa SMK yang memenuhi persyaratan yang telah ditetapkan.
                                </p>

                                <h3><i class="bi bi-mortarboard-fill text-primary"></i> 1. Kategori Mahasiswa</h3>
                                <p>Kategori mahasiswa diperuntukkan bagi mahasiswa aktif program Diploma (D3/D4) atau Sarjana (S1) dari perguruan tinggi yang memenuhi ketentuan. Peserta wajib mengisi Nomor Induk Mahasiswa (NIM), data perguruan tinggi, program studi, dan semester yang sedang ditempuh.</p>
                                <ul>
                                    <li><strong>Nomor Induk Mahasiswa (NIM):</strong> Wajib diisi dan terdaftar unik pada sistem.</li>
                                    <li><strong>Perguruan Tinggi & Program Studi:</strong> Nama resmi perguruan tinggi dan program studi yang aktif.</li>
                                    <li><strong>Semester Aktif:</strong> Pilihan semester berjalan (Semester 1 s.d. 14).</li>
                                    <li><strong>Kesesuaian Bidang:</strong> Terbuka untuk program studi informatika, sistem informasi, ilmu komunikasi, statistika, hukum, manajemen, dan bidang terkait lainnya.</li>
                                </ul>

                                <h3><i class="bi bi-building text-primary"></i> 2. Kategori Siswa SMK</h3>
                                <p>Kategori siswa diperuntukkan bagi siswa SMK yang mengikuti program Magang sesuai ketentuan sekolah. Peserta wajib mengisi data sekolah dan program keahlian. Nomor Induk Siswa (NIS/NISN) dapat diisi sesuai data resmi sekolah.</p>
                                <ul>
                                    <li><strong>Nama Sekolah:</strong> Nama resmi SMK atau sekolah asal.</li>
                                    <li><strong>Nomor Induk Siswa (NIS/NISN):</strong> Nomor induk siswa resmi dari sekolah (opsional sesuai data sekolah).</li>
                                    <li><strong>Program Keahlian:</strong> Jurusan atau kompetensi keahlian (contoh: Rekayasa Perangkat Lunak, Teknik Komputer & Jaringan, Multimedia, DKV, Manajemen Perkantoran).</li>
                                    <li><strong>Formulir Khusus:</strong> Pada kategori siswa, kolom NIM dan semester dinonaktifkan secara otomatis.</li>
                                </ul>

                                <div class="guide-callout">
                                    <div class="guide-callout-title"><i class="bi bi-shield-check"></i> Persyaratan Umum:</div>
                                    <p class="mb-0 small">Setiap peserta wajib memiliki Nomor Induk Kependudukan (NIK) 16 digit yang valid, pas foto formal terbaru (format JPG/PNG maks. 2 MB), serta surat pengantar resmi dari institusi pendidikan asal.</p>
                                </div>

                            @elseif($slug === 'surat-balasan')
                                <p class="lead font-weight-normal mb-4" style="font-size: 16px; color: var(--sim-text);">
                                    Setiap berkas pendaftaran diverifikasi oleh tim Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban. Peserta yang dinyatakan diterima dapat mengunduh Surat Balasan resmi secara mandiri melalui portal SIM-MAGANG.
                                </p>

                                <h3><i class="bi bi-clock-history text-primary"></i> 1. Status Verifikasi Pendaftaran</h3>
                                <p>Status pendaftaran dapat dipantau secara berkala melalui Dashboard dengan rincian sebagai berikut:</p>
                                
                                <div class="guide-step-card">
                                    <div class="guide-step-num"><i class="bi bi-send-fill"></i></div>
                                    <div>
                                        <strong class="d-block text-primary">1. Submitted (Diajukan)</strong>
                                        <p class="mb-0 small text-muted">Berkas pendaftaran telah masuk ke dalam sistem dan berada dalam antrean verifikasi.</p>
                                    </div>
                                </div>

                                <div class="guide-step-card">
                                    <div class="guide-step-num"><i class="bi bi-hourglass-split"></i></div>
                                    <div>
                                        <strong class="d-block text-warning">2. Under Review (Sedang Diverifikasi)</strong>
                                        <p class="mb-0 small text-muted">Berkas pendaftaran sedang ditinjau dan divalidasi oleh administrator dinas.</p>
                                    </div>
                                </div>

                                <div class="guide-step-card">
                                    <div class="guide-step-num"><i class="bi bi-check-circle-fill"></i></div>
                                    <div>
                                        <strong class="d-block text-success">3. Accepted (Diterima)</strong>
                                        <p class="mb-0 small text-muted">Pendaftaran disetujui. Surat Balasan resmi berformat PDF telah diterbitkan dan dapat diunduh langsung.</p>
                                    </div>
                                </div>

                                <div class="guide-step-card">
                                    <div class="guide-step-num"><i class="bi bi-x-circle-fill"></i></div>
                                    <div>
                                        <strong class="d-block text-danger">4. Rejected (Ditolak)</strong>
                                        <p class="mb-0 small text-muted">Pendaftaran belum disetujui karena berkas tidak memenuhi syarat atau kuota periode penuh. Alasan penolakan tercantum pada catatan verifikasi.</p>
                                    </div>
                                </div>

                                <h3><i class="bi bi-file-earmark-pdf-fill text-primary"></i> 2. Tata Cara Mengunduh Surat Balasan</h3>
                                <p>Peserta yang berstatus <strong>Accepted</strong> dapat mengunduh Surat Balasan resmi dengan langkah berikut:</p>
                                <ol class="pl-3 mb-4" style="line-height: 1.8;">
                                    <li>Masuk ke akun SIM-MAGANG menggunakan email dan kata sandi terdaftar.</li>
                                    <li>Buka menu <strong>Dashboard</strong> atau <strong>Riwayat Pendaftaran</strong>.</li>
                                    <li>Pada riwayat pendaftaran yang berstatus <em>Accepted</em>, klik tombol <strong>"Unduh Surat Balasan (PDF)"</strong>.</li>
                                    <li>Simpan dan gunakan dokumen resmi untuk keperluan administrasi institusi pendidikan.</li>
                                </ol>

                                <div class="guide-callout">
                                    <div class="guide-callout-title"><i class="bi bi-patch-check-fill"></i> Keabsahan Dokumen:</div>
                                    <p class="mb-0 small">Surat Balasan berformat PDF yang diterbitkan melalui portal SIM-MAGANG adalah dokumen resmi Pemerintah Kabupaten Tuban yang memuat data peserta, formasi yang disetujui, dan periode pelaksanaan magang.</p>
                                </div>
                            @endif

                            <div class="pt-4 mt-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <a href="{{ url('/#blog') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar Panduan
                                </a>

                                @auth
                                    @if(auth()->user()->isPeserta())
                                        <a href="{{ route('participant.registrations.create') }}" class="main-btn">
                                            <i class="bi bi-send-check-fill"></i> Ajukan Pendaftaran Magang
                                        </a>
                                    @else
                                        <a href="{{ route('admin.dashboard') }}" class="main-btn">
                                            <i class="bi bi-speedometer2"></i> Dashboard Admin
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('register') }}" class="main-btn">
                                        <i class="bi bi-person-plus-fill"></i> Daftar Akun Sekarang
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Right Column: Sidebar -->
                <div class="col-lg-4">
                    <!-- Other Guides Widget -->
                    <div class="guide-sidebar-card">
                        <h4 class="guide-sidebar-title">
                            <i class="bi bi-journals text-primary"></i> Panduan Lainnya
                        </h4>
                        @foreach($otherGuides as $og)
                            <a href="{{ route('guides.show', $og['slug']) }}" class="guide-other-item">
                                <span class="badge badge-light text-primary font-weight-bold mb-1">{{ $og['badge'] }}</span>
                                <strong>{{ $og['title'] }}</strong>
                                <small class="d-block">{{ Str::limit($og['summary'], 75) }}</small>
                            </a>
                        @endforeach
                    </div>

                    <!-- CTA Box -->
                    <div class="guide-cta-card">
                        <i class="bi bi-mortarboard-fill" style="font-size: 38px; color: #ffffff;"></i>
                        <h4 class="mt-2">Siap Bergabung?</h4>
                        <p>Kembangkan potensi dan kompetensi digital Anda bersama praktisi ASN di Diskominfo SP Kabupaten Tuban.</p>
                        @auth
                            <a href="{{ route('participant.dashboard') }}" class="btn btn-light btn-block font-weight-bold text-primary py-2.5">
                                Buka Dashboard Saya
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light btn-block font-weight-bold text-primary py-2.5">
                                Daftar Magang Sekarang
                            </a>
                        @endauth
                    </div>

                    <!-- Contact Mini Card -->
                    <div class="guide-sidebar-card mt-4">
                        <h4 class="guide-sidebar-title">
                            <i class="bi bi-headset text-primary"></i> Butuh Bantuan?
                        </h4>
                        <p class="small text-muted mb-2">Hubungi layanan informasi resmi Diskominfo SP Tuban pada jam operasional:</p>
                        <p class="small font-weight-bold mb-1"><i class="bi bi-telephone-fill text-primary mr-1"></i> (0356) 321000</p>
                        <p class="small font-weight-bold mb-3"><i class="bi bi-envelope-fill text-primary mr-1"></i> diskominfo@tubankab.go.id</p>
                        <a href="{{ url('/#contact') }}" class="btn btn-sm btn-outline-primary btn-block">
                            <i class="bi bi-chat-left-dots mr-1"></i> Kirim Pesan Pertanyaan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!--====== FOOTER ======-->
    <footer id="footer" class="footer_area bg-[#0f172a] border-t border-slate-800">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-6">
                <!-- COLUMN 1: HUBUNGI KAMI -->
                <div class="w-full flex flex-col">
                    <h3 class="text-white font-bold text-sm mb-4 tracking-wide uppercase">HUBUNGI KAMI</h3>
                    <p class="text-slate-400 text-sm mb-4 leading-relaxed">Berikut adalah alamat dan kontak yang bisa anda hubungi secara langsung.</p>
                    <ul class="flex flex-col gap-3 text-sm text-slate-300">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500 mt-0.5">📍</span>
                            <span>Jl. Mastrip No. 5 A, Sidorejo, Kec. Tuban, Jawa Timur 62315</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-blue-500">✉️</span>
                            <span>diskominfo@tubankab.go.id</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-blue-500">📞</span>
                            <span>(0356) 8832697</span>
                        </li>
                    </ul>

                    <div class="flex items-center gap-3 mt-6">
                        <!-- Website -->
                        <a href="https://diskominfo.tubankab.go.id" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800/80 flex items-center justify-center text-slate-400 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </a>
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/diskominfo.tuban" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800/80 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/kominfo.tuban" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800/80 flex items-center justify-center text-slate-400 hover:bg-pink-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <!-- X (Twitter) -->
                        <a href="https://twitter.com/DiskominfoTuban" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800/80 flex items-center justify-center text-slate-400 hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <!-- YouTube -->
                        <a href="https://www.youtube.com/channel/UC7V9cxzD7Gk-K_jxGMbblgA?view_as=subscriber" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800/80 flex items-center justify-center text-slate-400 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.377.55a3.016 3.016 0 0 0-2.122 2.136C.001 8.07.001 12 .001 12s0 3.93.5 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.55 9.377.55 9.377.55s7.505 0 9.377-.55a3.016 3.016 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- COLUMN 2: LOKASI KANTOR -->
                <div class="w-full flex flex-col">
                    <h3 class="text-white font-bold text-sm mb-4 tracking-wide uppercase">LOKASI KANTOR</h3>
                    <div class="w-full bg-slate-800 p-1.5 rounded-xl mb-4 shadow-inner">
                        <div class="w-full h-32 bg-slate-700 rounded-lg overflow-hidden flex items-center justify-center text-slate-500 text-xs">
                            <iframe 
                                class="w-full h-full object-cover border-0" 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.336495521743!2d112.04618!3d-6.89965!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e77a4561074e62d%3A0x6e2c657dfb7a8585!2sJl.%20Mastrip%20No.5A%2C%20Sidorejo%2C%20Kec.%20Tuban%2C%20Kabupaten%20Tuban%2C%20Jawa%20Timur%2062315!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Lokasi Kantor Diskominfo Tuban">
                            </iframe>
                        </div>
                    </div>
                    <div class="w-full text-sm">
                        <p class="text-slate-400 font-semibold mb-1">JAM PELAYANAN</p>
                        <p class="text-slate-300">Senin - Jum'at: 07.30 - 16.00</p>
                        <p class="text-rose-400 font-medium">Sabtu - Minggu: Libur</p>
                    </div>
                </div>

                <!-- COLUMN 3: STATISTIK PENGUNJUNG -->
                <div class="w-full flex flex-col">
                    <h3 class="text-white font-bold text-sm mb-4 tracking-wide uppercase">STATISTIK PENGUNJUNG</h3>
                    <div class="w-full flex flex-col gap-2.5">
                        <div class="w-full flex justify-between items-center bg-slate-800/80 px-4 py-3 rounded-xl border border-slate-700">
                            <span class="text-slate-300 text-sm font-medium">Hari Ini</span>
                            <span class="bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">1</span>
                        </div>
                        <div class="w-full flex justify-between items-center bg-slate-800/80 px-4 py-3 rounded-xl border border-slate-700">
                            <span class="text-slate-300 text-sm font-medium">Minggu Ini</span>
                            <span class="bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">2</span>
                        </div>
                        <div class="w-full flex justify-between items-center bg-slate-800/80 px-4 py-3 rounded-xl border border-slate-700">
                            <span class="text-slate-300 text-sm font-medium">Bulan Ini</span>
                            <span class="bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">1</span>
                        </div>
                        <div class="w-full flex justify-between items-center bg-slate-800/80 px-4 py-3 rounded-xl border border-slate-700">
                            <span class="text-slate-300 text-sm font-medium">Total</span>
                            <span class="bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">86</span>
                        </div>
                    </div>
                </div>

                <!-- COLUMN 4: SURVEI KEPUASAN -->
                <div class="w-full flex flex-col" x-data="{ rating: 0, hoverRating: 0 }">
                    <h3 class="text-white font-bold text-sm mb-4 tracking-wide uppercase">SURVEI KEPUASAN</h3>
                    <form action="{{ route('surveys.store') }}" method="POST" class="w-full bg-white rounded-2xl p-5 flex flex-col items-center text-center shadow-xl">
                        @csrf
                        <h4 class="text-slate-800 font-extrabold text-sm mb-1">Indeks Kepuasan Masyarakat</h4>
                        <p class="text-slate-500 text-xs mb-3">Berikan penilaian Anda</p>
                        
                        <!-- Interactive Stars -->
                        <div class="w-full flex justify-center items-center gap-1 mb-4">
                            <input type="hidden" name="rating" x-model="rating" required>
                            @for($i = 1; $i <= 5; $i++)
                            <svg @click="rating = {{ $i }}" 
                                 @mouseenter="hoverRating = {{ $i }}" 
                                 @mouseleave="hoverRating = 0"
                                 :class="{'text-yellow-400': hoverRating >= {{ $i }} || rating >= {{ $i }}, 'text-slate-200': hoverRating < {{ $i }} && rating < {{ $i }}}"
                                 class="w-7 h-7 sm:w-8 sm:h-8 cursor-pointer transition-colors duration-150 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>

                        <!-- Message & Submit -->
                        <textarea name="komentar" rows="2" class="w-full text-xs border border-slate-200 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500 mb-3 transition-colors text-slate-800" placeholder="Tulis pesan/saran singkat..."></textarea>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 rounded-lg shadow-sm transition-all duration-200">Kirim Survei</button>
                    </form>
                </div>
            </div>

            <div class="border-top border-slate-800 py-3 text-center small text-muted">
                <p class="mb-0">
                    &copy; {{ date('Y') }} <strong>SIM-MAGANG</strong> — Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <!--====== Theme Switcher Script ======-->
    <script>
        (function() {
            function updateTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem('adminHMD.colorTheme', theme);
                
                var icon = document.getElementById('themeIcon');
                if (icon) {
                    icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
                }
            }

            var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            var icon = document.getElementById('themeIcon');
            if (icon) {
                icon.className = currentTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
            }

            var btn = document.getElementById('themeToggleBtn');
            if (btn) {
                btn.addEventListener('click', function() {
                    var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    updateTheme(next);
                });
            }

            var mobileBtn = document.getElementById('mobileThemeToggleBtn');
            if (mobileBtn) {
                mobileBtn.addEventListener('click', function() {
                    var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    updateTheme(next);
                });
            }
        })();
    </script>
</body>
</html>
