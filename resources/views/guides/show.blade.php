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

        /* Breadcrumb Section */
        .guide-breadcrumb-area {
            background-color: var(--sim-surface-soft);
            border-bottom: 1px solid var(--sim-border);
            padding: 24px 0;
        }

        .guide-breadcrumbs {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 0;
            color: var(--sim-text-muted);
        }

        .guide-breadcrumbs a {
            color: var(--sim-text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .guide-breadcrumbs a:hover {
            color: var(--sim-primary);
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
        <div class="container-fluid px-3 px-md-4 px-xl-5" style="max-width: 1680px;">
            <div class="d-flex align-items-center justify-content-between">
                <a class="navbar-brand flex-shrink-0 mr-xl-3" href="{{ url('/') }}">
                    <img src="{{ asset('storage/image/logo.png') }}" alt="SIM-MAGANG Logo" class="brand-logo-img">
                    <div>
                        <span class="brand-text d-block">SIM-MAGANG</span>
                        <span class="brand-sub d-block">Diskominfo SP Kab. Tuban</span>
                    </div>
                </a>

                <div class="d-none d-xl-flex align-items-center">
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

                    <a href="https://tubankab.go.id/" target="_blank" rel="noopener noreferrer" class="main-btn nav-portal-btn" title="Buka Portal Resmi Pemerintah Kabupaten Tuban">
                        <i class="bi bi-bank mr-1"></i> Portal Pemerintah Kabupaten Tuban
                    </a>
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

    <!--====== BREADCRUMB ======-->
    <div class="guide-breadcrumb-area">
        <div class="container">
            <div class="guide-breadcrumbs">
                <a href="{{ url('/') }}"><i class="bi bi-house-door-fill mr-1"></i> Beranda</a>
                <span class="mx-1">/</span>
                <a href="{{ url('/#blog') }}">Panduan Magang</a>
                <span class="mx-1">/</span>
                <span class="text-primary font-weight-bold">{{ Str::limit($guide['title'], 45) }}</span>
            </div>
        </div>
    </div>

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
    <footer id="footer" class="footer_area">
        <div class="container">
            <div class="row pb-5">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('storage/image/logo.png') }}" alt="SIM-MAGANG Logo" class="footer-logo-img">
                        <div>
                            <strong class="text-white d-block" style="font-size: 18px;">SIM-MAGANG</strong>
                            <small class="text-muted">Diskominfo SP Kab. Tuban</small>
                        </div>
                    </div>
                    <p class="small text-muted mb-4">
                        Sistem Informasi Magang Resmi Pemerintah Kabupaten Tuban. Layanan digitalisasi pembinaan talenta digital, penelitian, dan magang kerja terpadu.
                    </p>
                    <div>
                        <a href="https://www.facebook.com/tubanpemkab" class="social-link-item" target="_blank" rel="noopener noreferrer" title="Facebook Resmi Pemerintah Kabupaten Tuban"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/kabupatentuban?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="social-link-item" target="_blank" rel="noopener noreferrer" title="Instagram Resmi Pemerintah Kabupaten Tuban"><i class="bi bi-instagram"></i></a>
                        <a href="https://x.com/tubankabgoid?s=11" class="social-link-item" target="_blank" rel="noopener noreferrer" title="Twitter / X Resmi Pemerintah Kabupaten Tuban"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://www.youtube.com/@tubanpemkab" class="social-link-item" target="_blank" rel="noopener noreferrer" title="YouTube Resmi Pemerintah Kabupaten Tuban"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer_title">Tautan Cepat</h5>
                    <ul class="footer_links">
                        <li><a href="{{ url('/#home') }}">Beranda</a></li>
                        <li><a href="{{ url('/#about') }}">Tentang Program</a></li>
                        <li><a href="{{ url('/#positions') }}">Formasi</a></li>
                        <li><a href="{{ url('/#services') }}">Keunggulan</a></li>
                        <li><a href="{{ url('/#alur') }}">Alur Pendaftaran</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer_title">Layanan Akun</h5>
                    <ul class="footer_links">
                        <li><a href="{{ route('login') }}">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}">Daftar Peserta</a></li>
                        <li><a href="{{ route('password.request') }}">Lupa Kata Sandi</a></li>
                        <li><a href="{{ url('/#faq') }}">Pusat Bantuan / FAQ</a></li>
                        <li><a href="{{ url('/#contact') }}">Kontak Kami</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h5 class="footer_title">Kantor Pelayanan</h5>
                    <p class="small text-muted mb-2">
                        <strong class="text-white">Dinas Komunikasi, Informatika, Statistik dan Persandian</strong><br>
                        Jl. Veteran No. 2, Kutorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62311
                    </p>
                    <p class="small text-muted mb-0">
                        <i class="bi bi-telephone text-primary mr-1"></i> (0356) 321000<br>
                        <i class="bi bi-envelope text-primary mr-1"></i> diskominfo@tubankab.go.id
                    </p>
                </div>
            </div>

            <div class="border-top border-secondary pt-4 text-center small text-muted">
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
