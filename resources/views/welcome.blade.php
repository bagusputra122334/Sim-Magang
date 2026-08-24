<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SIM-MAGANG - Sistem Informasi Magang Resmi Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban. Layanan pendaftaran magang digital yang transparan, profesional, dan terpadu.">

    <!--====== Title ======-->
    <title>SIM-MAGANG — Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban</title>

    <!--====== Favicon Icon ======-->
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

    <!--====== Animate CSS ======-->
    <link rel="stylesheet" href="{{ asset('traveland/css/animate.css') }}">

    <!--====== Nice Select CSS ======-->
    <link rel="stylesheet" href="{{ asset('traveland/css/nice-select.css') }}">

    <!--====== Line Icons & Bootstrap Icons CSS ======-->
    <link rel="stylesheet" href="{{ asset('traveland/css/LineIcons.2.0.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <!--====== Bootstrap CSS ======-->
    <link rel="stylesheet" href="{{ asset('traveland/css/bootstrap.4.5.2.min.css') }}">

    <!--====== Default CSS ======-->
    <link rel="stylesheet" href="{{ asset('traveland/css/default.css') }}">

    <!--====== Style CSS ======-->
    <link rel="stylesheet" href="{{ asset('traveland/css/style.css') }}">

    <style>
        /* ==========================================================================
           SIM-MAGANG INSTITUTIONAL BLUE DESIGN SYSTEM (TRAVELAND FOUNDATION)
           ========================================================================== */
        :root, html[data-theme="light"] {
            /* Brand Identity Colors */
            --sim-primary: #0d6efd;
            --sim-primary-hover: #0b5ed7;
            --sim-primary-dark: #043873;
            --sim-primary-subtle: rgba(13, 110, 253, 0.08);

            /* LIGHT THEME TOKENS */
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

            /* Navbar in Light Mode */
            --nav-bg: rgba(255, 255, 255, 0.95);
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

            /* Hero in Light Mode (Bright, Clean, Professional) */
            --hero-bg: linear-gradient(135deg, #eff6ff 0%, #dbeafe 45%, #f8fafc 100%);
            --hero-pattern-color: rgba(13, 110, 253, 0.08);
            --hero-glow: radial-gradient(circle, rgba(13, 110, 253, 0.15) 0%, transparent 70%);
            --hero-badge-bg: rgba(13, 110, 253, 0.1);
            --hero-badge-border: rgba(13, 110, 253, 0.25);
            --hero-badge-text: #0d6efd;
            --hero-title-color: #0f172a;
            --hero-title-span: #0d6efd;
            --hero-desc-color: #475569;
            --hero-border-divider: #cbd5e1;
            --hero-trust-text: #334155;
            --hero-frame-border: #cbd5e1;
            --hero-frame-shadow: 0 20px 45px rgba(15, 23, 42, 0.1);
            --hero-floating-bg: rgba(255, 255, 255, 0.94);
            --hero-floating-border: #e2e8f0;
            --hero-floating-title: #0f172a;
            --hero-floating-sub: #64748b;

            /* Secondary Button on Hero */
            --hero-btn-outline-border: #0d6efd;
            --hero-btn-outline-color: #0d6efd;
            --hero-btn-outline-hover-bg: #0d6efd;
            --hero-btn-outline-hover-color: #ffffff;

            /* Footer in Light Mode */
            --footer-bg: #081124;
            --footer-text: #94a3b8;
            --footer-title: #ffffff;
            --footer-border: rgba(255, 255, 255, 0.08);
        }

        html[data-theme="dark"] {
            /* DARK THEME TOKENS */
            --sim-surface: #0b1329;
            --sim-surface-soft: #070d1e;
            --sim-surface-card: #15223e;
            --sim-border: #233559;
            --sim-border-subtle: #192849;
            --sim-text: #f8fafc;
            --sim-text-muted: #94a3b8;
            --sim-text-secondary: #cbd5e1;
            --sim-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.4);
            --sim-shadow-md: 0 8px 24px rgba(0, 0, 0, 0.5);
            --sim-shadow-lg: 0 16px 36px rgba(0, 0, 0, 0.65);

            /* Navbar in Dark Mode */
            --nav-bg: rgba(11, 19, 41, 0.92);
            --nav-border: #233559;
            --nav-brand-text: #f8fafc;
            --nav-brand-sub: #94a3b8;
            --nav-link-color: #cbd5e1;
            --nav-link-hover: #93c5fd;
            --nav-btn-outline-border: #60a5fa;
            --nav-btn-outline-color: #93c5fd;
            --nav-btn-outline-hover-bg: #0d6efd;
            --nav-btn-outline-hover-color: #ffffff;
            --nav-toggle-bg: #15223e;
            --nav-toggle-color: #f8fafc;
            --nav-toggle-border: #233559;

            /* Hero in Dark Mode (Deep Navy, High Contrast) */
            --hero-bg: linear-gradient(135deg, #071530 0%, #0d2f6d 50%, #0a1f48 100%);
            --hero-pattern-color: rgba(255, 255, 255, 0.06);
            --hero-glow: radial-gradient(circle, rgba(13, 110, 253, 0.35) 0%, transparent 70%);
            --hero-badge-bg: rgba(255, 255, 255, 0.1);
            --hero-badge-border: rgba(255, 255, 255, 0.2);
            --hero-badge-text: #93c5fd;
            --hero-title-color: #ffffff;
            --hero-title-span: #93c5fd;
            --hero-desc-color: #cbd5e1;
            --hero-border-divider: rgba(255, 255, 255, 0.15);
            --hero-trust-text: #cbd5e1;
            --hero-frame-border: rgba(255, 255, 255, 0.18);
            --hero-frame-shadow: 0 24px 60px rgba(0, 0, 0, 0.6);
            --hero-floating-bg: rgba(11, 19, 41, 0.92);
            --hero-floating-border: rgba(255, 255, 255, 0.15);
            --hero-floating-title: #ffffff;
            --hero-floating-sub: #94a3b8;

            /* Secondary Button on Hero in Dark Mode */
            --hero-btn-outline-border: rgba(255, 255, 255, 0.8);
            --hero-btn-outline-color: #ffffff;
            --hero-btn-outline-hover-bg: #ffffff;
            --hero-btn-outline-hover-color: #0d2f6d;

            /* Footer in Dark Mode */
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

        /* Top Government Bar */
        .gov-topbar {
            background: #0b1329;
            color: #94a3b8;
            font-size: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            z-index: 1001;
            height: 38px;
            line-height: 38px;
            padding: 0 !important;
        }

        .gov-topbar a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .gov-topbar a:hover {
            color: #ffffff;
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

        /* Institutional Navbar */
        .header_navbar {
            position: absolute;
            top: 38px;
            left: 0;
            width: 100%;
            z-index: 999;
            transition: all 0.3s ease;
            background: var(--nav-bg);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--nav-border);
            padding: 6px 0;
        }

        .header_navbar.sticky {
            position: fixed;
            top: 0 !important;
            background: var(--sim-surface);
            box-shadow: var(--sim-shadow-md);
            border-bottom: 1px solid var(--sim-border);
            padding: 6px 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
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
            font-size: 21px;
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
            padding: 18px 10px;
            transition: all 0.25s ease;
            position: relative;
            white-space: nowrap;
        }

        .header_navbar .navbar-nav .nav-item a::before {
            background-color: var(--sim-primary);
            height: 3px;
            bottom: 0;
        }

        .header_navbar .navbar-nav .nav-item.active > a,
        .header_navbar .navbar-nav .nav-item:hover > a {
            color: var(--nav-link-hover);
        }

        /* Buttons Hierarchy */
        .main-btn {
            background-color: var(--sim-primary);
            color: #ffffff !important;
            font-weight: 600;
            font-size: 14px;
            height: 44px;
            line-height: 44px;
            padding: 0 24px;
            border-radius: 8px;
            border: 1px solid var(--sim-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(13, 110, 253, 0.25);
            transition: all 0.25s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .main-btn:hover {
            background-color: var(--sim-primary-hover);
            border-color: var(--sim-primary-hover);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
            transform: translateY(-2px);
            color: #ffffff !important;
            text-decoration: none;
        }

        .main-btn:active {
            transform: translateY(0);
        }

        .main-btn-outline {
            background-color: transparent;
            color: var(--sim-primary) !important;
            font-weight: 600;
            font-size: 14px;
            height: 44px;
            line-height: 42px;
            padding: 0 24px;
            border-radius: 8px;
            border: 1.5px solid var(--sim-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .main-btn-outline:hover {
            background-color: var(--sim-primary);
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
            text-decoration: none;
        }

        .hero-btn-outline {
            background-color: rgba(255, 255, 255, 0.12);
            color: var(--sim-text) !important;
            font-weight: 600;
            font-size: 14px;
            height: 44px;
            line-height: 42px;
            padding: 0 24px;
            border-radius: 8px;
            border: 1.5px solid var(--sim-border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            backdrop-filter: blur(8px);
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .hero-btn-outline:hover {
            background-color: var(--sim-surface);
            border-color: var(--sim-primary);
            color: var(--sim-primary) !important;
            transform: translateY(-2px);
            box-shadow: var(--sim-shadow-md);
            text-decoration: none;
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            background: var(--sim-surface);
            border: 1px solid var(--sim-border);
            color: var(--sim-text);
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .theme-toggle-btn:hover {
            border-color: var(--sim-primary);
            color: var(--sim-primary);
        }

        /* Portal Button Styling */
        .nav-portal-btn {
            font-size: 12.5px;
            height: 38px;
            line-height: 36px;
            padding: 0 14px;
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

        /* Large desktop (1200px - 1399.98px) */
        @media (min-width: 1200px) and (max-width: 1399.98px) {
            .header_navbar .navbar-nav .nav-item a {
                padding: 16px 7px;
                font-size: 12.5px;
            }
            .brand-logo-img {
                height: 88px;
                max-width: 250px;
            }
            .brand-text { font-size: 18px; }
            .brand-sub { font-size: 10.5px; }
            .nav-portal-btn {
                font-size: 12px;
                padding: 0 10px;
                height: 36px;
                line-height: 34px;
            }
        }

        /* Tablet Landscape & Small Laptops (992px - 1199.98px, e.g. 1024px) */
        @media (min-width: 992px) and (max-width: 1199.98px) {
            .header_navbar {
                top: 38px;
            }
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
                margin-right: 4px !important;
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

        /* Tablet Portrait (768px - 991.98px) */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .header_navbar {
                top: 38px;
            }
        }

        /* Mobile (< 768px) */
        @media (max-width: 767.98px) {
            .header_navbar {
                top: 0;
            }
        }

        /* Mobile & Tablet Portrait (< 992px: 768px, 576px, 375px) */
        @media (max-width: 991.98px) {
            .header_navbar {
                background: var(--sim-surface);
                box-shadow: var(--sim-shadow-sm);
                padding: 6px 0;
            }
            .brand-logo-img {
                height: 68px;
                max-width: 190px;
            }
            .brand-text { font-size: 18px; }
            .brand-sub { font-size: 10.5px; }
            .navbar-toggler .toggler-icon { background-color: var(--sim-text); }
            .navbar-collapse {
                background-color: var(--sim-surface);
                border-top: 1px solid var(--sim-border);
                box-shadow: var(--sim-shadow-md);
                padding: 15px 20px;
                border-radius: 0 0 16px 16px;
                max-height: 80vh;
                overflow-y: auto;
            }
            .navbar-nav .nav-item a {
                color: var(--sim-text) !important;
                padding: 10px 0 !important;
                border-bottom: 1px solid var(--sim-border-subtle);
            }
            .navbar-nav .nav-item.active > a,
            .navbar-nav .nav-item:hover > a {
                color: var(--sim-primary) !important;
            }
            .mobile-auth-btns {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid var(--sim-border);
            }
            .mobile-auth-btns .nav-portal-btn,
            .mobile-auth-btns .main-btn,
            .mobile-auth-btns .main-btn-outline {
                width: 100% !important;
                height: auto !important;
                min-height: 42px;
                padding: 10px 14px !important;
                font-size: 13px !important;
                line-height: 1.35 !important;
                text-align: center;
                white-space: normal !important;
                word-break: normal !important;
                display: flex !important;
                align-items: center;
                justify-content: center;
                gap: 6px;
                box-sizing: border-box;
            }
        }

        /* Hero Section — Full First Viewport */
        .hero_wrapper {
            position: relative;
            background: var(--hero-bg);
            min-height: calc(100vh - 34px);
            min-height: calc(100dvh - 34px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 175px 0 65px;
            overflow: hidden;
            border-bottom-left-radius: 80px;
            transition: background 0.3s ease;
        }

        @media (min-width: 1400px) {
            .hero_wrapper {
                padding: 175px 0 65px;
            }
            .hero_title { font-size: 44px; margin-bottom: 16px; }
            .hero_desc { font-size: 16px; margin-bottom: 24px; }
        }

        @media (max-width: 1399.98px) and (min-width: 1200px) {
            .hero_wrapper {
                padding: 165px 0 55px;
                border-bottom-left-radius: 60px;
            }
            .hero_title { font-size: 38px; margin-bottom: 14px; }
            .hero_desc { font-size: 15px; margin-bottom: 22px; line-height: 1.55; }
        }

        @media (max-width: 1199.98px) and (min-width: 992px) {
            .hero_wrapper {
                padding: 155px 0 50px;
                border-bottom-left-radius: 50px;
            }
            .hero_title { font-size: 34px; margin-bottom: 12px; }
            .hero_desc { font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        }

        @media (max-width: 991.98px) {
            .hero_wrapper {
                min-height: auto;
                padding: 145px 0 45px;
                border-bottom-left-radius: 40px;
            }
            .hero_title { font-size: 30px; margin-bottom: 12px; }
            .hero_desc { font-size: 14px; margin-bottom: 20px; }
            .hero-preview-frame { max-width: 440px; margin: 0 auto; }
            .about_image_box img { height: 340px !important; }
        }

        @media (max-width: 767.98px) {
            .hero_wrapper {
                padding: 115px 0 40px;
            }
        }

        @media (max-width: 575.98px) {
            .brand-logo-img {
                height: 52px;
                max-width: 140px;
            }
            .brand-text { font-size: 16px; }
            .brand-sub { font-size: 9.5px; }
            .hero_wrapper {
                padding: 90px 0 30px;
            }
            .hero_title { font-size: 26px; margin-bottom: 10px; }
            .hero_desc { font-size: 13.5px; margin-bottom: 16px; }
            .hero-badge-pill { padding: 4px 12px; font-size: 11.5px; margin-bottom: 10px; }
            .hero-preview-frame { max-width: 100%; margin: 0 auto; }
            .about_image_box img { height: 260px !important; }
        }

        .hero-pattern-overlay {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(var(--hero-pattern-color) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        .hero-glow {
            position: absolute;
            top: -150px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: var(--hero-glow);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--hero-badge-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--hero-badge-border);
            color: var(--hero-badge-text);
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .hero_title {
            font-size: 38px;
            font-weight: 800;
            color: var(--hero-title-color);
            line-height: 1.2;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
            transition: color 0.3s ease;
        }

        .hero_title span {
            color: var(--hero-title-span);
        }

        .hero_desc {
            font-size: 15px;
            line-height: 1.6;
            color: var(--hero-desc-color);
            margin-bottom: 24px;
            max-width: 560px;
            transition: color 0.3s ease;
        }

        .hero-trust-bar {
            border-top: 1px solid var(--hero-border-divider);
            color: var(--hero-trust-text);
            font-size: 13.5px;
            transition: color 0.3s ease, border-color 0.3s ease;
        }

        .hero-trust-bar i {
            font-size: 16px;
        }

        .hero-preview-frame {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid var(--hero-frame-border);
            box-shadow: var(--hero-frame-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            background: transparent;
        }

        .hero-preview-frame:hover {
            transform: translateY(-6px);
        }

        .hero-preview-frame img {
            width: 100%;
            height: auto;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }

        .hero-floating-card {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: var(--hero-floating-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--hero-floating-border);
            border-radius: 14px;
            padding: 14px 18px;
            color: var(--hero-floating-title);
            box-shadow: var(--sim-shadow-md);
        }

        .hero-floating-card strong {
            color: var(--hero-floating-title);
        }

        .hero-floating-card small {
            color: var(--hero-floating-sub);
        }

        /* Section Titles */
        .section_title {
            margin-bottom: 45px;
        }

        .section-tag {
            display: inline-block;
            background: var(--sim-primary-subtle);
            color: var(--sim-primary);
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .section_title .title {
            font-size: 36px;
            font-weight: 800;
            color: var(--sim-text);
            line-height: 1.25;
            letter-spacing: -0.5px;
            transition: color 0.3s ease;
        }

        .section_title .title span {
            color: var(--sim-primary);
        }

        .section_title p {
            font-size: 15.5px;
            color: var(--sim-text-muted);
            margin-top: 12px;
            max-width: 680px;
            margin-left: auto;
            margin-right: auto;
            transition: color 0.3s ease;
        }

        /* About Section & Value Cards */
        .about_area {
            background-color: var(--sim-surface);
            transition: background-color 0.3s ease;
        }

        .about_image_box {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--sim-shadow-lg);
            border: 1px solid var(--sim-border);
        }

        .about-value-card {
            background-color: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 16px;
            box-shadow: var(--sim-shadow-sm);
            padding: 18px 16px;
            height: 100%;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: all 0.25s ease;
        }

        .about-value-card:hover {
            transform: translateY(-3px);
            border-color: var(--sim-primary);
            box-shadow: var(--sim-shadow-md);
        }

        .about-value-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--sim-primary-subtle);
            color: var(--sim-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }

        .about-value-card:hover .about-value-icon {
            background: var(--sim-primary);
            color: #ffffff;
        }

        .about-value-title {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--sim-text);
            margin-bottom: 4px;
            line-height: 1.3;
            transition: color 0.3s ease;
        }

        .about-value-desc {
            font-size: 12px;
            color: var(--sim-text-muted);
            margin: 0;
            line-height: 1.45;
            transition: color 0.3s ease;
        }

        /* Position / Formasi Cards */
        .destination_area {
            background-color: var(--sim-surface-soft);
            transition: background-color 0.3s ease;
        }

        .position-card {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 16px;
            padding: 26px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--sim-shadow-sm);
            transition: all 0.25s ease;
        }

        .position-card:hover {
            transform: translateY(-5px);
            border-color: var(--sim-primary);
            box-shadow: var(--sim-shadow-lg);
        }

        .position-badge {
            background: var(--sim-primary-subtle);
            color: var(--sim-primary);
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .position-title {
            font-size: 19px;
            font-weight: 700;
            color: var(--sim-text);
            margin: 14px 0 10px;
            line-height: 1.35;
            transition: color 0.3s ease;
        }

        .position-desc {
            font-size: 14px;
            color: var(--sim-text-muted);
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1;
            transition: color 0.3s ease;
        }

        .position-meta {
            border-top: 1px solid var(--sim-border);
            padding-top: 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: var(--sim-text-secondary);
            transition: color 0.3s ease, border-color 0.3s ease;
        }

        /* Divisions Highlight Pills */
        .division-pill {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            height: 100%;
            transition: all 0.2s ease;
        }

        .division-pill:hover {
            border-color: var(--sim-primary);
            box-shadow: var(--sim-shadow-sm);
            transform: translateY(-2px);
        }

        .division-pill strong {
            color: var(--sim-text);
            transition: color 0.3s ease;
        }

        .division-pill small {
            color: var(--sim-text-muted);
            transition: color 0.3s ease;
        }

        .division-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--sim-primary-subtle);
            color: var(--sim-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        /* Services & Features */
        .services_area {
            background-color: var(--sim-surface);
            transition: background-color 0.3s ease;
        }

        .single_service {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            box-shadow: var(--sim-shadow-sm);
            transition: all 0.25s ease;
            height: 100%;
        }

        .single_service:hover {
            border-color: var(--sim-primary);
            box-shadow: var(--sim-shadow-lg);
            transform: translateY(-6px);
        }

        .single_service .services_icon i {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            border: 2px solid var(--sim-primary);
            background: var(--sim-primary-subtle);
            color: var(--sim-primary);
            line-height: 66px;
            font-size: 30px;
            display: inline-block;
            margin-bottom: 20px;
            transition: all 0.25s ease;
        }

        .single_service:hover .services_icon i {
            background: var(--sim-primary);
            color: #ffffff;
        }

        .single_service .title {
            font-size: 18px;
            font-weight: 700;
            color: var(--sim-text);
            margin-bottom: 12px;
            transition: color 0.3s ease;
        }

        .single_service p {
            font-size: 14px;
            color: var(--sim-text-muted);
            line-height: 1.6;
            transition: color 0.3s ease;
        }

        /* Registration Flow Steps */
        .step-box {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 16px;
            box-shadow: var(--sim-shadow-sm);
            padding: 30px 22px;
            text-align: center;
            height: 100%;
            transition: all 0.25s ease;
            position: relative;
        }

        .step-box:hover {
            border-color: var(--sim-primary);
            box-shadow: var(--sim-shadow-md);
            transform: translateY(-4px);
        }

        .step-box .title {
            color: var(--sim-text);
            transition: color 0.3s ease;
        }

        .step-badge {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--sim-primary);
            color: #ffffff;
            font-size: 19px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        /* FAQ Accordion */
        .faq-item {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 14px;
            margin-bottom: 14px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .faq-item:hover {
            border-color: var(--sim-primary);
        }

        .faq-btn {
            width: 100%;
            background: transparent;
            border: 0;
            padding: 18px 22px;
            text-align: left;
            font-weight: 700;
            font-size: 16px;
            color: var(--sim-text);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .faq-btn:focus {
            outline: none;
        }

        .faq-body {
            padding: 0 22px 20px;
            font-size: 14.5px;
            color: var(--sim-text-secondary);
            line-height: 1.65;
            transition: color 0.3s ease;
        }

        /* Blog & Guides */
        .single_blog {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--sim-shadow-sm);
            transition: all 0.25s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-decoration: none !important;
            color: inherit;
            cursor: pointer;
        }

        .single_blog:hover,
        .single_blog:focus {
            border-color: var(--sim-primary);
            box-shadow: var(--sim-shadow-md);
            transform: translateY(-5px);
            text-decoration: none !important;
            color: inherit;
            outline: none;
        }

        .single_blog:focus-visible {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.35), var(--sim-shadow-md);
            border-color: var(--sim-primary);
        }

        .single_blog .blog_image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .single_blog .blog_image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .single_blog:hover .blog_image img,
        .single_blog:focus .blog_image img {
            transform: scale(1.05);
        }

        .single_blog .blog_content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .blog_meta {
            font-size: 12.5px;
            color: var(--sim-text-muted);
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        .blog_meta span {
            margin-right: 14px;
        }

        .blog_title {
            font-size: 18px;
            font-weight: 700;
            color: var(--sim-text);
            line-height: 1.4;
            margin-bottom: 10px;
            transition: color 0.25s ease;
        }

        .single_blog:hover .blog_title,
        .single_blog:focus .blog_title {
            color: var(--sim-primary);
        }

        .blog_read_more {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--sim-primary);
            margin-top: auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.25s ease;
        }

        .single_blog:hover .blog_read_more,
        .single_blog:focus .blog_read_more {
            color: var(--sim-primary-hover);
            transform: translateX(4px);
        }

        /* ==========================================================================
           CONTACT SECTION & NICE SELECT THEME STYLES
           ========================================================================== */
        .contact-info-card {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 18px;
            padding: 32px;
            box-shadow: var(--sim-shadow-sm);
            height: 100%;
            transition: all 0.3s ease;
        }

        .contact-info-card h4 {
            color: var(--sim-text);
            transition: color 0.3s ease;
        }

        .contact-entry {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 24px;
        }

        .contact-entry strong {
            color: var(--sim-text);
            transition: color 0.3s ease;
        }

        .contact-entry span {
            color: var(--sim-text-muted);
            transition: color 0.3s ease;
        }

        .contact-entry-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--sim-primary-subtle);
            color: var(--sim-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .contact-form-card {
            background: var(--sim-surface-card);
            border: 1px solid var(--sim-border);
            border-radius: 18px;
            padding: 34px;
            box-shadow: var(--sim-shadow-sm);
            transition: all 0.3s ease;
        }

        .contact-form-card h4 {
            color: var(--sim-text);
            transition: color 0.3s ease;
        }

        .contact-form-card .form-label {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--sim-text);
            margin-bottom: 6px;
            display: block;
        }

        .contact-form-card input,
        .contact-form-card select,
        .contact-form-card textarea {
            width: 100%;
            height: 48px;
            border: 1.5px solid var(--sim-border);
            border-radius: 8px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
            color: var(--sim-text);
            background-color: var(--sim-surface-soft);
            margin-bottom: 18px;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .contact-form-card input::placeholder,
        .contact-form-card textarea::placeholder {
            color: var(--sim-text-muted);
            opacity: 0.8;
        }

        .contact-form-card textarea {
            height: 120px;
            padding: 14px 16px;
        }

        .contact-form-card input:focus,
        .contact-form-card select:focus,
        .contact-form-card textarea:focus {
            border-color: var(--sim-primary);
            background-color: var(--sim-surface);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.18);
            outline: none;
        }

        .contact-form-card input.is-invalid,
        .contact-form-card select.is-invalid,
        .contact-form-card textarea.is-invalid,
        .contact-form-card .nice-select.is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
        }

        /* NICE SELECT THEME OVERRIDES (CRITICAL FIX FOR CONTRAST) */
        .contact-form-card .nice-select {
            width: 100% !important;
            height: 48px !important;
            line-height: 44px !important;
            border-radius: 8px !important;
            padding: 0 16px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            background-color: var(--sim-surface-soft) !important;
            border: 1.5px solid var(--sim-border) !important;
            color: var(--sim-text) !important;
            margin-bottom: 18px !important;
            float: none !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.2s ease !important;
            box-sizing: border-box !important;
        }

        .contact-form-card .nice-select:hover {
            border-color: var(--sim-primary) !important;
        }

        .contact-form-card .nice-select:focus,
        .contact-form-card .nice-select.open {
            border-color: var(--sim-primary) !important;
            background-color: var(--sim-surface) !important;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.18) !important;
        }

        .contact-form-card .nice-select:after {
            border-bottom: 2px solid var(--sim-text-muted) !important;
            border-right: 2px solid var(--sim-text-muted) !important;
            right: 18px !important;
            width: 7px !important;
            height: 7px !important;
            margin-top: -5px !important;
        }

        .contact-form-card .nice-select.open:after {
            border-bottom-color: var(--sim-primary) !important;
            border-right-color: var(--sim-primary) !important;
        }

        .contact-form-card .nice-select .current {
            color: var(--sim-text) !important;
            font-weight: 500 !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        .contact-form-card .nice-select .list {
            background-color: var(--sim-surface-card) !important;
            border: 1.5px solid var(--sim-border) !important;
            box-shadow: var(--sim-shadow-lg) !important;
            border-radius: 10px !important;
            width: 100% !important;
            margin-top: 6px !important;
            padding: 6px 0 !important;
            z-index: 100 !important;
        }

        .contact-form-card .nice-select .option {
            color: var(--sim-text) !important;
            background-color: transparent !important;
            padding: 10px 18px !important;
            min-height: 42px !important;
            line-height: 22px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.15s ease !important;
        }

        .contact-form-card .nice-select .option:hover,
        .contact-form-card .nice-select .option.focus {
            background-color: var(--sim-primary-subtle) !important;
            color: var(--sim-primary) !important;
            font-weight: 600 !important;
        }

        .contact-form-card .nice-select .option.selected {
            background-color: var(--sim-primary-subtle) !important;
            color: var(--sim-primary) !important;
            font-weight: 700 !important;
        }

        .contact-form-card .nice-select .option.disabled {
            color: var(--sim-text-muted) !important;
            opacity: 0.55 !important;
            cursor: not-allowed !important;
            background-color: transparent !important;
        }

        /* Native select styling fallback */
        .contact-form-card select option {
            background-color: var(--sim-surface-card);
            color: var(--sim-text);
        }

        /* Footer */
        .footer_area {
            background: var(--footer-bg);
            color: var(--footer-text);
            padding: 70px 0 30px;
            border-top: 1px solid var(--footer-border);
            transition: background 0.3s ease;
        }

        .footer_area a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer_area a:hover {
            color: #ffffff;
        }

        .footer_title {
            color: var(--footer-title);
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer_links li {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .social-link-item {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s ease;
            margin-right: 8px;
        }

        .social-link-item:hover {
            background: var(--sim-primary);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .back-to-top {
            background-color: var(--sim-primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(13, 110, 253, 0.35);
        }

        .back-to-top:hover {
            background-color: var(--sim-primary-hover);
            color: #ffffff;
        }
    </style>
</head>

<body>
    @php
        $positions = \App\Models\Position::query()
            ->where('status', \App\Enums\PositionStatus::Aktif)
            ->latest('created_at')
            ->take(6)
            ->get();

        $divisionsList = [
            ['name' => 'Sekretariat', 'desc' => 'Administrasi, Kearsipan Digital & Tata Naskah Dinas', 'icon' => 'bi-building-gear'],
            ['name' => 'Komunikasi & Informasi Publik (KIP)', 'desc' => 'Media Sosial, Liputan Berita, Konten & PPID', 'icon' => 'bi-megaphone-fill'],
            ['name' => 'Aplikasi & Informatika (Aptika)', 'desc' => 'Software Dev, UI/UX, Cloud & SPBE Daerah', 'icon' => 'bi-code-slash'],
            ['name' => 'Statistik', 'desc' => 'Analisis Data Daerah, Visualisasi & Satu Data Tuban', 'icon' => 'bi-bar-chart-line-fill'],
            ['name' => 'Persandian & Keamanan Informasi', 'desc' => 'Cyber Security, Sertifikat TTE & Audit Sistem', 'icon' => 'bi-shield-lock-fill'],
        ];
    @endphp

    <!--====== PRELOADER ======-->
    <div class="preloader">
        <div class="loader">
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <div class="ytp-spinner-left"><div class="ytp-spinner-circle" style="border-color: #0d6efd #0d6efd #f8fafc;"></div></div>
                        <div class="ytp-spinner-right"><div class="ytp-spinner-circle" style="border-color: #0d6efd #0d6efd #f8fafc;"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <section class="header_area">
        <div class="header_navbar">
            <div class="container-fluid px-3 px-md-4 px-xl-5" style="max-width: 1680px;">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-xl w-100">
                            <a class="navbar-brand flex-shrink-0 mr-xl-3" href="{{ url('/') }}">
                                <img src="{{ asset('storage/image/logo.png') }}" alt="SIM-MAGANG Logo" class="brand-logo-img">
                                <div>
                                    <span class="brand-text d-block">SIM-MAGANG</span>
                                    <span class="brand-sub d-block">Diskominfo SP Kab. Tuban</span>
                                </div>
                            </a>

                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse sub-menu-bar justify-content-between" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav mx-auto align-items-center">
                                    <li class="nav-item active"><a class="page-scroll" href="#home">Beranda</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#about">Tentang</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#positions">Formasi</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#services">Keunggulan</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#alur">Alur</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#faq">FAQ</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#blog">Panduan</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#contact">Kontak</a></li>
                                </ul>

                                <div class="d-none d-xl-flex align-items-center gap-2 flex-shrink-0">
                                    {{-- Theme Switcher Button --}}
                                    <button class="theme-toggle-btn mr-2" type="button" id="themeToggleBtn" aria-label="Toggle theme" title="Ubah Tema Gelap/Terang">
                                        <i class="bi bi-moon-stars" id="themeIcon"></i>
                                    </button>

                                    <a href="https://tubankab.go.id/" target="_blank" rel="noopener noreferrer" class="main-btn nav-portal-btn" title="Buka Portal Resmi Pemerintah Kabupaten Tuban">
                                        <i class="bi bi-bank mr-1"></i> Portal Pemerintah Kabupaten Tuban
                                    </a>
                                </div>

                                {{-- Mobile Menu Portal Button --}}
                                <div class="d-xl-none mobile-auth-btns">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small font-weight-bold text-muted">Mode Tampilan:</span>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="mobileThemeToggleBtn">
                                            <i class="bi bi-moon-stars mr-1"></i> Switch Theme
                                        </button>
                                    </div>
                                    <a href="https://tubankab.go.id/" target="_blank" rel="noopener noreferrer" class="main-btn nav-portal-btn" title="Buka Portal Resmi Pemerintah Kabupaten Tuban">
                                        <i class="bi bi-bank mr-1"></i> Portal Pemerintah Kabupaten Tuban
                                    </a>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hero Section --}}
        <div id="home" class="hero_wrapper">
            <div class="hero-pattern-overlay"></div>
            <div class="hero-glow"></div>

            <div class="container position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <div class="hero-badge-pill wow fadeInLeft" data-wow-duration="1.2s" data-wow-delay="0.1s">
                            <i class="bi bi-patch-check-fill text-primary"></i>
                            <span>Portal Resmi Pendaftaran Magang</span>
                        </div>

                        <h1 class="hero_title wow fadeInLeft" data-wow-duration="1.2s" data-wow-delay="0.3s">
                            Membangun Talenta Digital untuk <span>Pelayanan Publik</span>
                        </h1>

                        <p class="hero_desc wow fadeInLeft" data-wow-duration="1.2s" data-wow-delay="0.5s">
                            Program Magang resmi <strong>Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban</strong>. Didesain khusus untuk Mahasiswa Perguruan Tinggi & Siswa SMK secara 100% digital, terstruktur, dan transparan.
                        </p>

                        <div class="d-flex flex-wrap align-items-center gap-3 wow fadeInLeft" data-wow-duration="1.2s" data-wow-delay="0.7s">
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="main-btn mr-3">
                                        <i class="bi bi-speedometer2"></i> Dashboard Administrator
                                    </a>
                                @else
                                    <a href="{{ route('participant.dashboard') }}" class="main-btn mr-3">
                                        <i class="bi bi-person-workspace"></i> Buka Dashboard Saya
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}" class="main-btn mr-3">
                                    <i class="bi bi-send-check-fill"></i> Daftar Magang Sekarang
                                </a>
                                <a href="#positions" class="hero-btn-outline page-scroll">
                                    <i class="bi bi-grid-fill"></i> Lihat Formasi
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="col-lg-5 text-center wow fadeInRight" data-wow-duration="1.2s" data-wow-delay="0.4s">
                        <div class="hero-preview-frame">
                            <img src="{{ asset('storage/image/1.png') }}" alt="SIM-MAGANG Diskominfo Tuban Command Center" class="img-fluid w-100" style="height: auto; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== ABOUT & INSTITUTIONAL VALUES ======-->
    <section id="about" class="about_area pt-120 pb-120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 wow fadeInLeft" data-wow-duration="1.2s">
                    <div class="about_image_box">
                        <img src="{{ file_exists(public_path('storage/image/2.png')) ? asset('storage/image/2.png') : asset('storage/image/gambar 2.png') }}" alt="Mentoring Magang Diskominfo Tuban" class="img-fluid w-100" style="height: 440px; object-fit: cover;">
                    </div>
                </div>

                <div class="col-lg-6 wow fadeInRight" data-wow-duration="1.2s">
                    <div class="section_title mb-4">
                        <span class="section-tag">Tentang Program</span>
                        <h2 class="title">SIM-MAGANG <br> Diskominfo SP <span>Kabupaten Tuban</span></h2>
                        <p class="text-left mx-0">
                            Platform digital resmi terpadu yang memfasilitasi penerimaan peserta magang Mahasiswa Perguruan Tinggi dan Siswa SMK. Seluruh proses pendaftaran, verifikasi berkas oleh administrator, hingga penerbitan surat balasan resmi dilakukan secara transparan dan terintegrasi.
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="about-value-card">
                                <div class="about-value-icon">
                                    <i class="bi bi-laptop"></i>
                                </div>
                                <div>
                                    <h5 class="about-value-title">Layanan Digital Terpadu</h5>
                                    <p class="about-value-desc">Pendaftaran dan verifikasi berkas daring tanpa membawa berkas fisik.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="about-value-card">
                                <div class="about-value-icon">
                                    <i class="bi bi-person-workspace"></i>
                                </div>
                                <div>
                                    <h5 class="about-value-title">Bimbingan Praktisi ASN</h5>
                                    <p class="about-value-desc">Mentoring profesional di 5 bidang kerja strategis Diskominfo SP Tuban.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="about-value-card">
                                <div class="about-value-icon">
                                    <i class="bi bi-file-earmark-check-fill"></i>
                                </div>
                                <div>
                                    <h5 class="about-value-title">Surat Balasan Resmi</h5>
                                    <p class="about-value-desc">Penerbitan dokumen digital resmi berformat PDF langsung dari portal.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="about-value-card">
                                <div class="about-value-icon">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>
                                <div>
                                    <h5 class="about-value-title">Standar Kurikulum Magang</h5>
                                    <p class="about-value-desc">Mendukung konversi SKS akademik kampus dan kurikulum kejuruan SMK.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== POSISI & FORMASI ======-->
    <section id="positions" class="destination_area pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center">
                        <span class="section-tag">Formasi & Bidang Kerja</span>
                        <h2 class="title">Pilihan Formasi <br> Kembangkan <span>Potensi Anda</span></h2>
                        <p>Tersedia berbagai pilihan posisi magang di 5 bidang kerja strategis Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.</p>
                    </div>
                </div>
            </div>

            {{-- 5 Bidang Kerja Dinas --}}
            <div class="row mb-5">
                @foreach($divisionsList as $div)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="division-pill">
                            <div class="division-icon"><i class="bi {{ $div['icon'] }}"></i></div>
                            <div>
                                <strong class="d-block font-weight-bold" style="font-size: 14.5px;">{{ $div['name'] }}</strong>
                                <small>{{ $div['desc'] }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Dynamic Positions Cards --}}
            <div class="row">
                @if($positions->isNotEmpty())
                    @foreach($positions as $pos)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="position-card wow fadeInUp" data-wow-duration="1.2s">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="position-badge">
                                            <i class="bi bi-mortarboard-fill"></i> Mahasiswa & SMK
                                        </span>
                                        <span class="badge badge-success text-white px-2 py-1 small">Buka</span>
                                    </div>
                                    <h4 class="position-title">{{ $pos->nama_posisi }}</h4>
                                    <p class="position-desc">{{ Str::limit($pos->deskripsi, 130) }}</p>
                                </div>
                                <div>
                                    <div class="position-meta">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Terakhir Diperbarui:</span>
                                            <strong style="color: var(--sim-text);">{{ $pos->updated_at?->locale('id')->translatedFormat('d M Y') ?? '-' }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Status Formasi:</span>
                                            <strong class="text-success font-weight-bold"><i class="bi bi-check-circle-fill me-1"></i>Aktif & Terbuka</strong>
                                        </div>
                                    </div>
                                    @auth
                                        @if(auth()->user()->isPeserta())
                                            <a href="{{ route('participant.registrations.create', ['position_id' => $pos->id]) }}" class="main-btn btn-block text-center">
                                                Daftar Formasi Ini
                                            </a>
                                        @else
                                            <a href="{{ route('admin.positions.show', $pos->id) }}" class="main-btn btn-block text-center">
                                                Kelola Formasi
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('register') }}" class="main-btn btn-block text-center">
                                            Daftar Sekarang
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Default Positions sample --}}
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="position-card">
                            <div>
                                <span class="position-badge"><i class="bi bi-mortarboard-fill"></i> Mahasiswa & SMK</span>
                                <h4 class="position-title">Pengembangan Web & Aplikasi SPBE</h4>
                                <p class="position-desc">Pengembangan dan integrasi sistem informasi web pemerintah daerah berbasis Laravel, API SPBE, dan manajemen database MySQL.</p>
                            </div>
                            <div>
                                <div class="position-meta">
                                    <div class="d-flex justify-content-between mb-1"><span>Bidang:</span><strong style="color: var(--sim-text);">Aplikasi & Informatika</strong></div>
                                    <div class="d-flex justify-content-between"><span>Peserta:</span><strong style="color: var(--sim-text);">Mahasiswa / SMK</strong></div>
                                </div>
                                <a href="{{ route('register') }}" class="main-btn btn-block text-center">Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="position-card">
                            <div>
                                <span class="position-badge"><i class="bi bi-mortarboard-fill"></i> Mahasiswa & SMK</span>
                                <h4 class="position-title">Media Sosial & Konten Kreatif</h4>
                                <p class="position-desc">Produksi konten visual publikasi daerah, desain grafis, fotografi, liputan berita, dan pengelolaan media sosial resmi Pemkab Tuban.</p>
                            </div>
                            <div>
                                <div class="position-meta">
                                    <div class="d-flex justify-content-between mb-1"><span>Bidang:</span><strong style="color: var(--sim-text);">Komunikasi & Informasi Publik</strong></div>
                                    <div class="d-flex justify-content-between"><span>Peserta:</span><strong style="color: var(--sim-text);">Mahasiswa / SMK</strong></div>
                                </div>
                                <a href="{{ route('register') }}" class="main-btn btn-block text-center">Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="position-card">
                            <div>
                                <span class="position-badge"><i class="bi bi-mortarboard-fill"></i> Mahasiswa & SMK</span>
                                <h4 class="position-title">Jaringan & Keamanan Informasi</h4>
                                <p class="position-desc">Pemeliharaan infrastruktur jaringan fiber optik, server intranet dinas, dan monitoring pengamanan informasi persandian.</p>
                            </div>
                            <div>
                                <div class="position-meta">
                                    <div class="d-flex justify-content-between mb-1"><span>Bidang:</span><strong style="color: var(--sim-text);">Persandian & Jaringan</strong></div>
                                    <div class="d-flex justify-content-between"><span>Peserta:</span><strong style="color: var(--sim-text);">Mahasiswa / SMK</strong></div>
                                </div>
                                <a href="{{ route('register') }}" class="main-btn btn-block text-center">Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!--====== KEUNGGULAN & FASILITAS ======-->
    <section id="services" class="services_area pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center">
                        <span class="section-tag">Keunggulan Program</span>
                        <h2 class="title">Mengapa Memilih Magang di <br><span>Diskominfo SP Tuban?</span></h2>
                        <p>Dapatkan pengalaman kerja nyata dan kompetensi profesional yang relevan dengan transformasi digital sektor publik.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="single_service wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.1s">
                        <div class="services_icon">
                            <i class="lni lni-user"></i>
                        </div>
                        <h4 class="title">Bimbingan Mentor Ahli</h4>
                        <p>Dibimbing langsung oleh praktisi ASN dan profesional IT berkompeten di bidangnya.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="single_service wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.3s">
                        <div class="services_icon">
                            <i class="lni lni-laptop"></i>
                        </div>
                        <h4 class="title">Proyek Riil SPBE</h4>
                        <p>Terlibat langsung dalam sistem e-Government, digitalisasi data, dan infrastruktur Pemkab.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="single_service wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.5s">
                        <div class="services_icon">
                            <i class="lni lni-certificate"></i>
                        </div>
                        <h4 class="title">Surat & Sertifikat Resmi</h4>
                        <p>Penerbitan Surat Balasan resmi berformat digital PDF dan Sertifikat Magang instansi.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="single_service wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.7s">
                        <div class="services_icon">
                            <i class="lni lni-graduation"></i>
                        </div>
                        <h4 class="title">Mahasiswa & Siswa SMK</h4>
                        <p>Mendukung konversi SKS akademik kampus serta kurikulum magang kejuruan SMK.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== ALUR PENDAFTARAN ======-->
    <section id="alur" class="destination_area pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center">
                        <span class="section-tag">Tahapan Seleksi</span>
                        <h2 class="title">Alur Pendaftaran <br> 4 Langkah Mudah <span>Menjadi Peserta</span></h2>
                        <p>Proses pendaftaran dirancang 100% digital tanpa perlu datang membawa berkas fisik ke kantor dinas.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="step-box wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.1s">
                        <div class="step-badge">1</div>
                        <h4 class="title mb-2" style="font-size: 17px; font-weight: 700;">Buat Akun Peserta</h4>
                        <p class="text-muted small">Registrasi dengan email aktif dan pilih kategori Mahasiswa atau Siswa SMK.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="step-box wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.3s">
                        <div class="step-badge">2</div>
                        <h4 class="title mb-2" style="font-size: 17px; font-weight: 700;">Lengkapi Profil & Berkas</h4>
                        <p class="text-muted small">Isi biodata, data kampus/sekolah, dan unggah CV serta Surat Pengantar.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="step-box wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.5s">
                        <div class="step-badge">3</div>
                        <h4 class="title mb-2" style="font-size: 17px; font-weight: 700;">Verifikasi Berkas</h4>
                        <p class="text-muted small">Tim administrator Diskominfo SP meninjau kelayakan dan verifikasi berkas pendaftaran.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="step-box wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.7s">
                        <div class="step-badge">4</div>
                        <h4 class="title mb-2" style="font-size: 17px; font-weight: 700;">Unduh Surat Balasan</h4>
                        <p class="text-muted small">Peserta yang diterima mengunduh Surat Balasan resmi PDF dari dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== FAQ SECTION ======-->
    <section id="faq" class="services_area pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center">
                        <span class="section-tag">FAQ & Bantuan</span>
                        <h2 class="title">Pertanyaan yang Sering <span>Diajukan</span></h2>
                        <p>Informasi seputar syarat pendaftaran, format berkas, dan ketentuan program magang.</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="faq-item">
                        <button class="faq-btn" type="button" data-toggle="collapse" data-target="#faq1">
                            <span>1. Apakah Siswa SMK wajib mengisi NIM pada form profil?</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div id="faq1" class="collapse show">
                            <div class="faq-body">
                                <strong>Tidak.</strong> Kategori Siswa SMK tidak memerlukan NIM. Sistem SIM-MAGANG secara otomatis menyesuaikan formulir pendaftaran untuk Siswa SMK (menggunakan NIS/NISN dan Nama Sekolah).
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq2">
                            <span>2. Apa saja berkas yang wajib diunggah saat pendaftaran?</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div id="faq2" class="collapse">
                            <div class="faq-body">
                                Berkas wajib meliputi: (1) <strong>Curriculum Vitae (CV)</strong> terbaru, (2) <strong>Surat Pengantar / Rekomendasi</strong> dari Perguruan Tinggi atau Sekolah, dan (3) <strong>Proposal Magang</strong> (opsional/jika ada rencana program kerja). Semua dokumen diunggah dalam format PDF (maks. 2MB).
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq3">
                            <span>3. Bagaimana cara mengetahui status verifikasi berkas saya?</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div id="faq3" class="collapse">
                            <div class="faq-body">
                                Anda dapat login ke portal SIM-MAGANG dan membuka <strong>Dashboard Saya</strong>. Status pendaftaran (Submitted, Under Review, Accepted, atau Rejected) serta Surat Balasan resmi akan langsung ditampilkan secara real-time.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq4">
                            <span>4. Berapa lama durasi pelaksanaan magang di Diskominfo SP Tuban?</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div id="faq4" class="collapse">
                            <div class="faq-body">
                                Durasi magang disesuaikan dengan surat permohonan dari kampus atau sekolah, umumnya berkisar antara <strong>1 hingga 6 bulan</strong> (termasuk program magang MBKM).
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== PANDUAN & BERITA ======-->
    <section id="blog" class="destination_area pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center">
                        <span class="section-tag">Informasi & Artikel</span>
                        <h2 class="title">Panduan Magang <span>SIM-MAGANG</span></h2>
                        <p>Panduan praktis dan tips mempersiapkan dokumen agar proses verifikasi Anda berjalan lancar.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('guides.show', 'pendaftaran') }}" class="single_blog wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.1s" aria-label="Baca Panduan Lengkap Pendaftaran & Upload Berkas Magang">
                        <div class="blog_image">
                            <img src="{{ asset('traveland/images/blog-guide.png') }}" alt="Panduan Pendaftaran">
                        </div>
                        <div class="blog_content">
                            <div class="blog_meta">
                                <span><i class="bi bi-person mr-1"></i> Admin Diskominfo</span>
                                <span><i class="bi bi-bookmark-check mr-1"></i> Panduan Resmi</span>
                            </div>
                            <h4 class="blog_title">Panduan Lengkap Pendaftaran & Upload Berkas Magang</h4>
                            <p class="text-muted small mb-3">Persyaratan dokumen (CV, Surat Pengantar, Proposal Magang) dan tata cara pendaftaran daring melalui portal SIM-MAGANG.</p>
                            <span class="blog_read_more">Baca Panduan <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('guides.show', 'kategori-peserta') }}" class="single_blog wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.3s" aria-label="Baca Ketentuan Kategori Mahasiswa dan Siswa SMK">
                        <div class="blog_image">
                            <img src="{{ asset('traveland/images/blog-category.png') }}" alt="Kategori Peserta">
                        </div>
                        <div class="blog_content">
                            <div class="blog_meta">
                                <span><i class="bi bi-mortarboard mr-1"></i> Akademik</span>
                                <span><i class="bi bi-bookmark-check mr-1"></i> Informasi Resmi</span>
                            </div>
                            <h4 class="blog_title">Ketentuan Kategori Mahasiswa dan Siswa SMK</h4>
                            <p class="text-muted small mb-3">Ketentuan pengisian profil dan persyaratan pendaftaran bagi kategori Mahasiswa Perguruan Tinggi serta Siswa SMK.</p>
                            <span class="blog_read_more">Baca Panduan <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('guides.show', 'surat-balasan') }}" class="single_blog wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.5s" aria-label="Baca Penerbitan Surat Balasan Resmi Berformat Digital PDF">
                        <div class="blog_image">
                            <img src="{{ file_exists(public_path('storage/image/3.png')) ? asset('storage/image/3.png') : asset('storage/image/gambar 3.png') }}" alt="Surat Balasan">
                        </div>
                        <div class="blog_content">
                            <div class="blog_meta">
                                <span><i class="bi bi-file-earmark-check mr-1"></i> Verifikasi</span>
                                <span><i class="bi bi-bookmark-check mr-1"></i> Layanan Digital</span>
                            </div>
                            <h4 class="blog_title">Penerbitan Surat Balasan Resmi Berformat Digital PDF</h4>
                            <p class="text-muted small mb-3">Tahapan verifikasi pendaftaran dan tata cara mengunduh Surat Balasan resmi instansi berformat digital PDF.</p>
                            <span class="blog_read_more">Baca Panduan <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!--====== KONTAK & INFORMASI ======-->
    <section id="contact" class="services_area pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center">
                        <span class="section-tag">Pusat Layanan</span>
                        <h2 class="title">Hubungi Kami <br> Layanan <span>Informasi Magang</span></h2>
                        <p>Memiliki pertanyaan seputar persyaratan dan jadwal magang? Tim Diskominfo SP Tuban siap membantu Anda.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="contact-info-card">
                        <h4 class="font-weight-bold mb-4" style="font-size: 20px;">Kantor Diskominfo SP Tuban</h4>

                        <div class="contact-entry">
                            <div class="contact-entry-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <strong class="d-block">Alamat Kantor</strong>
                                <span class="small">Jl. Veteran No. 2, Kutorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62311</span>
                            </div>
                        </div>

                        <div class="contact-entry">
                            <div class="contact-entry-icon"><i class="bi bi-telephone-fill"></i></div>
                            <div>
                                <strong class="d-block">Telepon Layanan</strong>
                                <span class="small">(0356) 321000 / 321400</span>
                            </div>
                        </div>

                        <div class="contact-entry">
                            <div class="contact-entry-icon"><i class="bi bi-envelope-fill"></i></div>
                            <div>
                                <strong class="d-block">Email Resmi</strong>
                                <span class="small">diskominfo@tubankab.go.id</span>
                            </div>
                        </div>

                        <div class="contact-entry">
                            <div class="contact-entry-icon"><i class="bi bi-clock-fill"></i></div>
                            <div>
                                <strong class="d-block">Jam Operasional</strong>
                                <span class="small">Senin – Jumat: 07.30 – 16.00 WIB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="contact-form-card">
                        <h4 class="font-weight-bold mb-3" style="font-size: 20px;">Kirim Pertanyaan / Pesan</h4>
                        
                        {{-- Flash Message Alerts --}}
                        @if(session('contact_success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm d-flex align-items-center" role="alert">
                                <i class="bi bi-check-circle-fill fs-5 mr-2 text-success"></i>
                                <div>
                                    <strong>Berhasil Terkirim!</strong> {{ session('contact_success') }}
                                </div>
                            </div>
                        @endif

                        @if(session('contact_error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill fs-5 mr-2 text-danger"></i>
                                <div>
                                    <strong>Gagal Mengirim:</strong> {{ session('contact_error') }}
                                </div>
                            </div>
                        @endif

                        {{-- Dynamic JavaScript Alert Box --}}
                        <div id="contactFormAlert" class="d-none"></div>

                        <form action="{{ route('contact.send') }}" method="POST" id="contactForm" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="contactName">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" id="contactName" name="name" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                                    @error('name')
                                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactPhone">Nomor WhatsApp / Telp <span class="text-danger">*</span></label>
                                    <input type="text" id="contactPhone" name="phone" placeholder="Contoh: 081234567890" value="{{ old('phone') }}" class="{{ $errors->has('phone') ? 'is-invalid' : '' }}" required>
                                    @error('phone')
                                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactEmail">Alamat Email <span class="text-danger">*</span></label>
                                    <input type="email" id="contactEmail" name="email" placeholder="Contoh: nama@email.com" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                                    @error('email')
                                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactCategory">Kategori Peserta <span class="text-danger">*</span></label>
                                    <select id="contactCategory" name="category" class="{{ $errors->has('category') ? 'is-invalid' : '' }}" required>
                                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori Peserta...</option>
                                        <option value="mahasiswa" {{ old('category') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa / Perguruan Tinggi</option>
                                        <option value="siswa" {{ old('category') === 'siswa' ? 'selected' : '' }}>Siswa / SMK / SMA</option>
                                        <option value="dosen_guru" {{ old('category') === 'dosen_guru' ? 'selected' : '' }}>Dosen / Guru Pembimbing</option>
                                        <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('category')
                                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="contactMessage">Pesan atau Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea id="contactMessage" name="message" placeholder="Tuliskan pertanyaan Anda mengenai persyaratan atau pelaksanaan magang..." class="{{ $errors->has('message') ? 'is-invalid' : '' }}" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="main-btn btn-block" id="contactSubmitBtn">
                                        <i class="bi bi-send-fill"></i> Kirim Pesan Informasi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                        <li><a href="#home" class="page-scroll">Beranda</a></li>
                        <li><a href="#about" class="page-scroll">Tentang Program</a></li>
                        <li><a href="#positions" class="page-scroll">Formasi</a></li>
                        <li><a href="#services" class="page-scroll">Keunggulan</a></li>
                        <li><a href="#alur" class="page-scroll">Alur Pendaftaran</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer_title">Layanan Akun</h5>
                    <ul class="footer_links">
                        <li><a href="{{ route('login') }}">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}">Daftar Peserta</a></li>
                        <li><a href="{{ route('password.request') }}">Lupa Kata Sandi</a></li>
                        <li><a href="#faq" class="page-scroll">Pusat Bantuan / FAQ</a></li>
                        <li><a href="#contact" class="page-scroll">Kontak Kami</a></li>
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

    <!--====== BACK TO TOP ======-->
    <a href="#" class="back-to-top"><i class="lni lni-chevron-up"></i></a>

    <!--====== JavaScript Dependencies ======-->
    <script src="{{ asset('traveland/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('traveland/js/vendor/modernizr-3.7.1.min.js') }}"></script>
    <script src="{{ asset('traveland/js/popper.min.js') }}"></script>
    <script src="{{ asset('traveland/js/bootstrap.4.5.2.min.js') }}"></script>
    <script src="{{ asset('traveland/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('traveland/js/scrolling-nav.js') }}"></script>
    <script src="{{ asset('traveland/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('traveland/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('traveland/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('traveland/js/wow.min.js') }}"></script>
    <script src="{{ asset('traveland/js/main.js') }}"></script>

    <script>
        // Dark / Light Mode Toggle Logic
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

        // Contact Form Client-Side Mailto Handler with Validation & Fallback
        document.addEventListener('DOMContentLoaded', function() {
            var contactForm = document.getElementById('contactForm');
            var alertBox = document.getElementById('contactFormAlert');
            var submitBtn = document.getElementById('contactSubmitBtn');

            if (contactForm && alertBox && submitBtn) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Clear previous alerts & invalid styling
                    alertBox.className = 'd-none';
                    alertBox.innerHTML = '';
                    contactForm.querySelectorAll('.is-invalid').forEach(function(el) {
                        el.classList.remove('is-invalid');
                    });
                    contactForm.querySelectorAll('.nice-select.is-invalid').forEach(function(el) {
                        el.classList.remove('is-invalid');
                    });

                    var nameInput = document.getElementById('contactName');
                    var phoneInput = document.getElementById('contactPhone');
                    var emailInput = document.getElementById('contactEmail');
                    var categoryInput = document.getElementById('contactCategory');
                    var messageInput = document.getElementById('contactMessage');

                    var nameVal = nameInput ? nameInput.value.trim() : '';
                    var phoneVal = phoneInput ? phoneInput.value.trim() : '';
                    var emailVal = emailInput ? emailInput.value.trim() : '';
                    var categoryVal = categoryInput ? categoryInput.value.trim() : '';
                    var messageVal = messageInput ? messageInput.value.trim() : '';

                    var errors = [];

                    // 1. Validate Name
                    if (!nameVal) {
                        errors.push('Nama Lengkap wajib diisi.');
                        if (nameInput) nameInput.classList.add('is-invalid');
                    } else if (nameVal.length < 2) {
                        errors.push('Nama Lengkap minimal 2 karakter.');
                        if (nameInput) nameInput.classList.add('is-invalid');
                    }

                    // 2. Validate Phone
                    if (!phoneVal) {
                        errors.push('Nomor WhatsApp / Telp wajib diisi.');
                        if (phoneInput) phoneInput.classList.add('is-invalid');
                    } else if (phoneVal.length < 6) {
                        errors.push('Nomor WhatsApp / Telp tidak valid (minimal 6 digit).');
                        if (phoneInput) phoneInput.classList.add('is-invalid');
                    }

                    // 3. Validate Email
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailVal) {
                        errors.push('Alamat Email wajib diisi.');
                        if (emailInput) emailInput.classList.add('is-invalid');
                    } else if (!emailRegex.test(emailVal)) {
                        errors.push('Format Alamat Email tidak valid.');
                        if (emailInput) emailInput.classList.add('is-invalid');
                    }

                    // 4. Validate Category
                    if (!categoryVal) {
                        errors.push('Kategori Peserta wajib dipilih.');
                        if (categoryInput) {
                            categoryInput.classList.add('is-invalid');
                            var niceSelectEl = categoryInput.nextElementSibling;
                            if (niceSelectEl && niceSelectEl.classList.contains('nice-select')) {
                                niceSelectEl.classList.add('is-invalid');
                            }
                        }
                    }

                    // 5. Validate Message
                    if (!messageVal) {
                        errors.push('Pesan atau Pertanyaan wajib diisi.');
                        if (messageInput) messageInput.classList.add('is-invalid');
                    } else if (messageVal.length < 5) {
                        errors.push('Pesan atau Pertanyaan minimal 5 karakter.');
                        if (messageInput) messageInput.classList.add('is-invalid');
                    }

                    // If validation fails, display friendly alert box and focus
                    if (errors.length > 0) {
                        var errListHtml = '<ul class="mb-0 pl-3 small mt-1 text-danger">';
                        errors.forEach(function(err) {
                            errListHtml += '<li>' + err + '</li>';
                        });
                        errListHtml += '</ul>';

                        alertBox.className = 'alert alert-warning mb-4 rounded-3 border-0 shadow-sm';
                        alertBox.innerHTML = '<div class="d-flex align-items-center"><i class="bi bi-exclamation-circle-fill fs-5 mr-2 text-warning"></i><strong>Mohon lengkapi formulir dengan benar:</strong></div>' + errListHtml;
                        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        return;
                    }

                    // Map category value to human-readable label
                    var categoryLabels = {
                        'mahasiswa': 'Mahasiswa / Perguruan Tinggi',
                        'siswa': 'Siswa / SMK / SMA',
                        'dosen_guru': 'Dosen / Guru Pembimbing',
                        'lainnya': 'Lainnya'
                    };
                    var categoryLabel = categoryLabels[categoryVal] || categoryVal;

                    var recipientEmail = 'bagusdwijunior@gmail.com';
                    var emailSubject = 'Pertanyaan Magang SIM-MAGANG - ' + nameVal;

                    var emailBody = 'Halo Tim SIM-MAGANG Diskominfo Tuban,\n\n'
                        + 'Berikut adalah rincian pesan/pertanyaan dari formulir kontak SIM-MAGANG:\n\n'
                        + 'Nama Lengkap: ' + nameVal + '\n'
                        + 'Nomor WhatsApp / Telp: ' + phoneVal + '\n'
                        + 'Alamat Email: ' + emailVal + '\n'
                        + 'Kategori Peserta: ' + categoryLabel + '\n\n'
                        + 'Pesan / Pertanyaan:\n'
                        + messageVal + '\n\n'
                        + '---\n'
                        + 'Pesan ini dibuat melalui Formulir Kontak SIM-MAGANG Diskominfo SP Kabupaten Tuban.';

                    var mailtoUrl = 'mailto:' + encodeURIComponent(recipientEmail)
                        + '?subject=' + encodeURIComponent(emailSubject)
                        + '&body=' + encodeURIComponent(emailBody);

                    var gmailWebUrl = 'https://mail.google.com/mail/?view=cm&fs=1'
                        + '&to=' + encodeURIComponent(recipientEmail)
                        + '&su=' + encodeURIComponent(emailSubject)
                        + '&body=' + encodeURIComponent(emailBody);

                    // Open mailto link
                    var mailtoLink = document.createElement('a');
                    mailtoLink.href = mailtoUrl;
                    mailtoLink.target = '_self';
                    document.body.appendChild(mailtoLink);
                    mailtoLink.click();
                    document.body.removeChild(mailtoLink);

                    // Display informative, transparent, non-deceptive status alert
                    alertBox.className = 'alert alert-info mb-4 rounded-3 border-0 shadow-sm';
                    alertBox.innerHTML = '<div class="d-flex align-items-start">'
                        + '<i class="bi bi-envelope-paper-fill fs-5 mr-3 text-primary mt-1"></i>'
                        + '<div>'
                        + '<strong class="d-block mb-1 text-primary" style="font-size: 16px;">Draft Email Telah Dibuka di Aplikasi Email Anda</strong>'
                        + '<p class="small mb-2 text-secondary">'
                        + 'Formulir telah otomatis diisi dan diarahkan ke aplikasi email Anda dengan tujuan <strong>' + recipientEmail + '</strong>.'
                        + ' Silakan periksa draft tersebut dan tekan tombol <strong>Kirim / Send</strong> di aplikasi email Anda untuk menyelesaikan pengiriman.'
                        + '</p>'
                        + '<div class="d-flex flex-wrap align-items-center gap-2 mt-2 pt-2 border-top border-light">'
                        + '<span class="small text-muted mr-2">Aplikasi email tidak terbuka otomatis?</span>'
                        + '<a href="' + gmailWebUrl + '" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary">'
                        + '<i class="bi bi-google mr-1"></i> Buka via Gmail Web'
                        + '</a>'
                        + '<span class="small text-muted ml-2">atau kirim manual ke <strong>' + recipientEmail + '</strong></span>'
                        + '</div>'
                        + '</div>'
                        + '</div>';

                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            }
        });
    </script>
</body>

</html>
