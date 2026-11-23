<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>RA Islam Al Futuh - Pendidikan Karakter Qur'ani & Anak Usia Dini</title>
    <meta name="description" content="Portal Resmi & Sistem Informasi Akademik RA Islam Al Futuh Semin, Gunungkidul. Membina generasi cerdas, mandiri, dan berakhlakul karimah.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            /* Skema Warna Resmi Logo RA Islam Al Futuh */
            --brand-green-deep: #0d4a2b;
            --brand-green-primary: #15803d;
            --brand-green-medium: #166534;
            --brand-green-accent: #22c55e;
            --brand-green-subtle: #f0fdf4;
            --brand-green-border: #bbf7d0;

            --brand-gold-deep: #b45309;
            --brand-gold-primary: #d97706;
            --brand-gold-light: #f59e0b;
            --brand-gold-subtle: #fffbeb;
            --brand-gold-border: #fde68a;

            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;

            --bg-page: #ffffff;
            --bg-alt: #f8fafc;
            --border-light: #e2e8f0;

            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-pill: 9999px;

            --shadow-card: 0 4px 20px -2px rgba(13, 74, 43, 0.05);
            --shadow-hover: 0 16px 36px -4px rgba(13, 74, 43, 0.12);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-body);
            background-color: var(--bg-page);
            line-height: 1.65;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            color: var(--text-heading);
            letter-spacing: -0.02em;
        }

        /* Top Bar Info */
        .top-info-bar {
            background-color: var(--brand-green-deep);
            color: #e2e8f0;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .top-info-bar a {
            color: #f8fafc;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .top-info-bar a:hover {
            color: var(--brand-gold-light);
        }

        /* Navbar */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }
        .navbar-custom.scrolled {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.06);
        }
        .nav-link {
            font-weight: 600;
            color: var(--text-body) !important;
            font-size: 0.92rem;
            padding: 0.5rem 0.95rem !important;
            transition: all 0.2s ease;
            position: relative;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--brand-green-primary) !important;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--brand-green-primary);
            transition: all 0.2s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover::after {
            width: 24px;
        }

        /* Buttons - Flat Design */
        .btn-brand-primary {
            background-color: var(--brand-green-primary);
            color: #ffffff;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-pill);
            padding: 0.72rem 1.6rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }
        .btn-brand-primary:hover {
            background-color: var(--brand-green-deep);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }
        .btn-brand-gold {
            background-color: var(--brand-gold-primary);
            color: #ffffff;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-pill);
            padding: 0.72rem 1.6rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }
        .btn-brand-gold:hover {
            background-color: var(--brand-gold-deep);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }
        .btn-brand-outline {
            border: 1.5px solid var(--border-light);
            color: var(--text-heading);
            background: #ffffff;
            font-weight: 600;
            border-radius: var(--radius-pill);
            padding: 0.72rem 1.6rem;
            transition: all 0.25s ease;
        }
        .btn-brand-outline:hover {
            border-color: var(--brand-green-primary);
            color: var(--brand-green-primary);
            background: var(--brand-green-subtle);
            transform: translateY(-2px);
        }

        /* Hero / Masthead - Flat */
        .hero-section {
            padding-top: 5.5rem;
            padding-bottom: 5rem;
            background-color: #f8fafc;
            position: relative;
        }
        .hero-title {
            font-size: clamp(2.2rem, 4.3vw, 3.5rem);
            font-weight: 800;
            line-height: 1.18;
            margin-bottom: 1.25rem;
            letter-spacing: -0.02em;
            color: #1e293b;
        }
        /* Warna Judul Flat Murni (Bebas Neon & Bebas Gradasi) */
        .hero-title .title-flat-blue {
            color: #1d4ed8; /* Flat Royal Blue */
        }
        .hero-title .title-flat-amber {
            color: #ff8223ff; /* Flat Warm Amber */
        }
        .hero-title .title-flat-green {
            color: #15803d; /* Flat Forest Green */
        }
        .hero-title .title-flat-teal {
            color: #0f766e; /* Flat Deep Teal */
        }
        .hero-title .title-flat-berry {
            color: #be123c; /* Flat Berry Red */
        }
        .hero-title .title-flat-muted {
            color: #64748b; /* Flat Slate Netral */
            font-weight: 600;
        }
        .hero-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--brand-green-subtle);
            border: 1px solid var(--brand-green-border);
            color: var(--brand-green-medium);
            padding: 0.4rem 1rem;
            border-radius: var(--radius-pill);
            font-size: 0.85rem;
            font-weight: 700;
        }

        /* Hero Card Showcase */
        .hero-showcase-box {
            position: relative;
            padding: 1.5rem;
        }
        .hero-main-card {
            background-color: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 2rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .hero-logo-img {
            width: 185px;
            height: 185px;
            object-fit: contain;
            filter: drop-shadow(0 10px 16px rgba(0, 0, 0, 0.08));
            transition: transform 0.4s ease;
        }
        .hero-main-card:hover .hero-logo-img {
            transform: scale(1.04);
        }
        .floating-badge {
            position: absolute;
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 1rem;
            padding: 0.7rem 1.2rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 3;
            animation: floatSlow 4s ease-in-out infinite alternate;
        }
        .floating-badge-1 {
            top: 6%;
            left: -4%;
        }
        .floating-badge-2 {
            bottom: 8%;
            right: -4%;
            animation-delay: 2s;
        }
        @keyframes floatSlow {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-7px); }
        }

        /* Clean Icon Box */
        .clean-icon-box {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .icon-box-emerald {
            background-color: var(--brand-green-subtle);
            color: var(--brand-green-primary);
            border: 1px solid var(--brand-green-border);
        }
        .icon-box-gold {
            background-color: var(--brand-gold-subtle);
            color: var(--brand-gold-primary);
            border: 1px solid var(--brand-gold-border);
        }
        .icon-box-blue {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }
        .icon-box-teal {
            background-color: #f0fdfa;
            color: #0d9488;
            border: 1px solid #99f6e4;
        }

        /* Stats Strip */
        .stats-strip {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
            padding: 1.5rem 2rem;
            margin-top: -2.5rem;
            position: relative;
            z-index: 10;
        }
        .stat-item {
            border-right: 1px solid var(--border-light);
        }
        .stat-item:last-child {
            border-right: none;
        }
        .stat-number {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--brand-green-deep);
            line-height: 1;
        }

        /* Section Commons */
        .section-padding {
            padding: 5.5rem 0;
        }
        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--brand-gold-deep);
            background: var(--brand-gold-subtle);
            border: 1px solid var(--brand-gold-border);
            padding: 0.35rem 0.9rem;
            border-radius: var(--radius-pill);
            margin-bottom: 0.75rem;
        }
        .section-title {
            font-size: clamp(1.8rem, 3.2vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 0.75rem;
        }
        .section-subtitle {
            font-size: 1.05rem;
            color: var(--text-muted);
            max-width: 660px;
            margin: 0 auto;
        }

        /* Feature Card Clean */
        .clean-feature-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 1.25rem;
            padding: 2rem 1.75rem;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-card);
        }
        .clean-feature-card:hover {
            transform: translateY(-6px);
            border-color: var(--brand-green-border);
            box-shadow: var(--shadow-hover);
        }
        .clean-feature-card:hover .clean-icon-box {
            transform: scale(1.08);
        }

        /* Visi Misi Card - Flat */
        .vision-card {
            background-color: #0d4a2b;
            color: #ffffff;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 10px 25px -5px rgba(13, 74, 43, 0.15);
        }

        /* News Cards */
        .clean-news-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 1.25rem;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-card);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .clean-news-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
            border-color: #cbd5e1;
        }
        .news-img-box {
            height: 210px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }
        .news-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .clean-news-card:hover .news-img-box img {
            transform: scale(1.06);
        }
        .news-date-badge {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            color: #f8fafc;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: var(--radius-pill);
        }

        /* FAQ Accordion Clean */
        .faq-accordion .accordion-item {
            border: 1px solid var(--border-light);
            border-radius: 1rem !important;
            margin-bottom: 0.85rem;
            overflow: hidden;
            box-shadow: var(--shadow-card);
        }
        .faq-accordion .accordion-button {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-heading);
            padding: 1.2rem 1.5rem;
            background-color: #ffffff;
            box-shadow: none !important;
        }
        .faq-accordion .accordion-button:not(.collapsed) {
            background-color: var(--brand-green-subtle);
            color: var(--brand-green-deep);
        }
        .faq-accordion .accordion-body {
            padding: 1.2rem 1.5rem;
            color: var(--text-body);
            font-size: 0.95rem;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
        }

        /* PPDB Section - Flat */
        .ppdb-section {
            background-color: #0d4a2b;
            color: #ffffff;
            position: relative;
            padding: 6rem 0;
        }
        .ppdb-card {
            background: #ffffff;
            border-radius: 1.75rem;
            box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.25);
            border: none;
            overflow: hidden;
        }
        .ppdb-card-header {
            background-color: #f8fafc;
            border-bottom: 1px solid var(--border-light);
            padding: 1.75rem 2.25rem;
        }
        .form-control, .form-select {
            border: 1.5px solid #d1d5db;
            border-radius: 0.65rem;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-green-primary);
            box-shadow: 0 0 0 4px rgba(21, 128, 61, 0.12);
        }
        .form-label-custom {
            font-weight: 700;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Step Badge */
        .step-badge {
            width: 32px;
            height: 32px;
            min-width: 32px;
            min-height: 32px;
            max-width: 32px;
            max-height: 32px;
            aspect-ratio: 1 / 1;
            background: var(--brand-green-primary);
            color: #ffffff;
            border-radius: 50% !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            flex-shrink: 0 !important;
            line-height: 1;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Tombol Submit PPDB */
        .btn-submit-ppdb {
            padding: 0.85rem 2.5rem;
            font-size: 1.05rem;
            font-weight: 700;
            border-radius: var(--radius-pill);
            transition: all 0.25s ease;
        }

        /* Footer */
        .footer-main {
            background-color: #0b1329;
            color: #cbd5e1;
            padding: 4.5rem 0 2rem;
            border-top: 4px solid var(--brand-green-primary);
        }
        .footer-main a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .footer-main a:hover {
            color: var(--brand-gold-light);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 3.5rem;
            padding-top: 2rem;
            font-size: 0.85rem;
        }

        /* Floating WhatsApp Button */
        .floating-wa-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #25d366;
            color: #ffffff;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.38);
            z-index: 999;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .floating-wa-btn:hover {
            transform: scale(1.1) translateY(-3px);
            color: #ffffff;
            box-shadow: 0 15px 30px rgba(37, 211, 102, 0.5);
        }

        /* Penyesuaian Mobile: Padding, Margin, Teks & Tombol */
        @media (max-width: 991.98px) {
            .hero-section {
                padding-top: 3.5rem;
                padding-bottom: 3.5rem;
                text-align: center;
            }
            .hero-title {
                font-size: 2.1rem;
                line-height: 1.25;
            }
            .hero-section .lead {
                font-size: 1rem;
                padding-right: 0 !important;
            }
            .hero-section .btn {
                width: 100%;
                justify-content: center;
                padding: 0.85rem 1.25rem;
            }
            .stats-strip {
                margin-top: 2rem;
                padding: 1.25rem 1rem;
            }
            .stat-item {
                border-right: none;
                padding: 0.5rem 0.25rem;
            }
            .stat-number {
                font-size: 1.75rem;
            }
            .section-padding {
                padding: 3.5rem 0;
            }
            .section-title {
                font-size: 1.75rem;
            }
            .section-subtitle {
                font-size: 0.95rem;
            }
            .vision-card {
                padding: 1.75rem 1.25rem;
                border-radius: 1.25rem;
            }
            .vision-card blockquote {
                font-size: 1.15rem !important;
            }
            .ppdb-section {
                padding: 3.5rem 0;
            }
            .ppdb-card {
                border-radius: 1.25rem;
            }
            .ppdb-card-header {
                padding: 1.25rem;
            }
            .ppdb-card .card-body {
                padding: 1.5rem 1.25rem;
            }
            .ppdb-card .btn {
                width: 100%;
                padding: 0.85rem 1rem;
                font-size: 1rem;
            }
            .step-badge {
                width: 28px !important;
                height: 28px !important;
                min-width: 28px !important;
                min-height: 28px !important;
                max-width: 28px !important;
                max-height: 28px !important;
                aspect-ratio: 1 / 1 !important;
                font-size: 0.8rem !important;
                border-radius: 50% !important;
                flex-shrink: 0 !important;
            }
            .btn-submit-ppdb {
                width: 100% !important;
                padding: 0.72rem 1.25rem !important;
                font-size: 0.95rem !important;
                border-radius: var(--radius-pill) !important;
            }
            .floating-wa-btn {
                bottom: 18px;
                right: 18px;
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
        }
    </style>
</head>
<body id="page-top">

    <!-- Top Info Bar -->
    <div class="top-info-bar py-2 d-none d-md-block">
        <div class="container px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-4">
                    <span><i class="bi-geo-alt text-warning me-1"></i> Semin, Gunungkidul, D.I. Yogyakarta</span>
                    <span><i class="bi-clock text-warning me-1"></i> Jam Belajar: Senin - Jumat (07.00 - 12.00 WIB)</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="https://wa.me/6285864480331" target="_blank"><i class="bi-whatsapp text-success me-1"></i> 0858-6448-0331</a>
                    <span class="opacity-25">|</span>
                    <a href="{{ route('login') }}"><i class="bi-box-arrow-in-right text-warning me-1"></i> Masuk Sistem</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top" id="mainNav">
        <div class="container px-4">
            <a class="navbar-brand d-flex align-items-center" href="#page-top">
                <img src="{{ asset('img/logo.png') }}" alt="Logo RA Al Futuh" width="46" height="46" class="me-2 rounded-circle border border-2 border-success-subtle shadow-sm" style="object-fit: contain;">
                <div>
                    <span class="font-heading fw-extrabold text-success d-block lh-1 fs-4">RA ISLAM AL FUTUH</span>
                    <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.08em;">Pondasi Generasi Qur'ani</span>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi-list fs-2 text-success"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-3 my-lg-0 align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="#page-top">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#profil">Profil & Visi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#keunggulan">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#berita">Kabar Sekolah</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-brand-gold py-2 px-3 fs-6 d-inline-flex align-items-center gap-2" href="#ppdb">
                            <i class="bi-pencil-square"></i>
                            <span>Daftar PPDB</span>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        @auth
                            <a class="btn btn-brand-primary py-2 px-3 fs-6 d-inline-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                                <i class="bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a class="btn btn-brand-outline py-2 px-3 fs-6 d-inline-flex align-items-center gap-2" href="{{ route('login') }}">
                                <i class="bi-person"></i>
                                <span>Portal Masuk</span>
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section" id="hero">
        <div class="container px-4">
            <div class="row align-items-center gy-5">
                <div class="col-lg-7 text-center text-lg-start">
                    <div class="hero-badge-pill mb-3">
                        <i class="bi-stars text-warning fs-6"></i>
                        <span>Penerimaan Peserta Didik Baru (PPDB) 2026/2027 Dibuka</span>
                    </div>

                    <h1 class="hero-title">
                        <span class="title-flat-blue">Pendidikan</span>
                        <span class="title-flat-amber">Usia Dini</span> <br>
                        <span class="title-flat-green">Berkarakter</span>
                        <span class="title-flat-teal">Qur'ani</span>
                        <span class="title-flat-muted">&amp;</span>
                        <span class="title-flat-berry">Ceria.</span>
                    </h1>

                    <p class="lead text-secondary mb-4 pe-lg-4 fs-5">
                        RA Islam Al Futuh menghadirkan ruang tumbuh kembang ideal bagi ananda tercinta. Memadukan pembiasaan ibadah praktis, tahfidz Juz 'Amma, kurikulum merdeka PAUD, serta stimulasi kecerdasan dan kreativitas motorik.
                    </p>

                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center justify-content-lg-start gap-3 mb-5">
                        <a class="btn btn-brand-primary btn-lg d-inline-flex align-items-center gap-2" href="#ppdb">
                            <i class="bi-journal-check fs-5"></i>
                            <span>Formulir PPDB Online</span>
                        </a>
                        <a class="btn btn-brand-outline btn-lg d-inline-flex align-items-center gap-2" href="#keunggulan">
                            <i class="bi-compass fs-5 text-success"></i>
                            <span>Jelajahi Program</span>
                        </a>
                    </div>

                    <!-- Highlight Badges Strip -->
                    <div class="pt-3 border-top border-light-subtle d-flex flex-wrap gap-4 justify-content-center justify-content-lg-start text-muted small fw-semibold">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi-shield-check text-success fs-5"></i>
                            <span>Izin Resmi Kemenag</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi-book text-warning fs-5"></i>
                            <span>Dawaamul Qur'an & Hadits</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi-laptop text-info fs-5"></i>
                            <span>Pantauan Digital SI-Alfutuh</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <div class="hero-showcase-box text-center">
                        <!-- Floating Badge 1 -->
                        <div class="floating-badge floating-badge-1">
                            <div class="clean-icon-box icon-box-emerald" style="width: 42px; height: 42px; font-size: 1.2rem;">
                                <i class="bi-mortarboard"></i>
                            </div>
                            <div class="text-start">
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Kurikulum</small>
                                <strong class="text-dark small">Merdeka PAUD & STEAM</strong>
                            </div>
                        </div>

                        <!-- Main Showcase Card -->
                        <div class="hero-main-card">
                            <div class="p-3 mb-3">
                                <img src="{{ asset('img/logo.png') }}" class="hero-logo-img" alt="Logo Resmi RA Al Futuh">
                            </div>
                            <h4 class="font-heading fw-bold text-dark mb-1">RA ISLAM AL FUTUH</h4>
                            <p class="text-muted small mb-3">Membentuk Generasi Sholeh, Cerdas, dan Mandiri</p>
                            <div class="d-inline-flex align-items-center gap-2 py-1 px-3 bg-white border border-success-subtle rounded-pill small fw-bold text-success">
                                <i class="bi-geo-alt text-danger"></i> Semin, Gunungkidul
                            </div>
                        </div>

                        <!-- Floating Badge 2 -->
                        <div class="floating-badge floating-badge-2">
                            <div class="clean-icon-box icon-box-gold" style="width: 42px; height: 42px; font-size: 1.2rem;">
                                <i class="bi-award"></i>
                            </div>
                            <div class="text-start">
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Layanan</small>
                                <strong class="text-dark small">Pantauan Aktivitas Anak Secara Online</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Ribbon -->
            <div class="stats-strip mt-5">
                <div class="row g-4 text-center">
                    <div class="col-6 col-md-3 stat-item">
                        <div class="stat-number">50+</div>
                        <div class="text-muted small fw-semibold mt-1">Siswa & Alumni Aktif</div>
                    </div>
                    <div class="col-6 col-md-3 stat-item">
                        <div class="stat-number">100%</div>
                        <div class="text-muted small fw-semibold mt-1">Pendidik Berpengalaman</div>
                    </div>
                    <div class="col-6 col-md-3 stat-item">
                        <div class="stat-number">6</div>
                        <div class="text-muted small fw-semibold mt-1">Aspek Tumbuh Kembang</div>
                    </div>
                    <div class="col-6 col-md-3 stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="text-muted small fw-semibold mt-1">Akses Portal Pantauan</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Profil & Visi Misi Section -->
    <section class="section-padding bg-light" id="profil">
        <div class="container px-4">
            <div class="text-center mb-5">
                <span class="section-tag"><i class="bi-info-circle"></i> Mengenal Lebih Dekat</span>
                <h2 class="section-title">Profil & Visi Misi Sekolah</h2>
                <p class="section-subtitle">Pondasi kokoh untuk membimbing ananda menapaki gerbang masa depan yang gemilang dengan bekal nilai-nilai Islam.</p>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="vision-card h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="bg-white rounded-circle p-2 d-flex text-success" style="width: 50px; height: 50px; align-items: center; justify-content: center;">
                                    <i class="bi-award fs-3 text-warning"></i>
                                </div>
                                <div>
                                    <span class="text-warning text-uppercase fw-bold small" style="letter-spacing: 0.05em;">Cita-Cita Luhur</span>
                                    <h4 class="text-white fw-bold mb-0">Visi RA Islam Al Futuh</h4>
                                </div>
                            </div>
                            <blockquote class="fs-5 fw-semibold lh-base mb-4" style="color: #ecfdf5;">
                                "Mencetak Generasi Yang Islami, Mandiri, dan Berprestasi."
                            </blockquote>
                        </div>
                        <div class="pt-4 border-top border-white border-opacity-25 small text-white-50">
                            <i class="bi-quote fs-4 text-warning me-1"></i> Pembinaan fitrah anak sesuai tuntunan Al-Qur'an dan Sunnah Rasulullah Shalallahu alaihi wasallam.
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 p-md-5 bg-white">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="clean-icon-box icon-box-emerald" style="width: 48px; height: 48px; font-size: 1.4rem;">
                                <i class="bi-bullseye"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Misi Utama Kami</h4>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background-color: #f8fafc; border-left: 4px solid #15803d;">
                                <span class="step-badge mt-1">1</span>
                                <div>
                                    <strong class="text-dark d-block mb-1">Penanaman Nilai Aqidah & Ibadah Sehari-hari</strong>
                                    <span class="text-muted small">Membiasakan sholat dhuha, melafalkan doa-doa harian, hafalan surah juz 'amma, serta adab sopan santun islami.</span>
                                </div>
                            </div>
                            <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background-color: #f8fafc; border-left: 4px solid #d97706;">
                                <span class="step-badge mt-1" style="background: #d97706;">2</span>
                                <div>
                                    <strong class="text-dark d-block mb-1">Stimulasi Kognitif & Literasi Kreatif (STEAM)</strong>
                                    <span class="text-muted small">Mengasah daya pikir kritis anak melalui pengamatan alam terbuka, eksperimen sederhana, serta pemanfaatan bahan loose-parts.</span>
                                </div>
                            </div>
                            <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background-color: #f8fafc; border-left: 4px solid #2563eb;">
                                <span class="step-badge mt-1" style="background: #2563eb;">3</span>
                                <div>
                                    <strong class="text-dark d-block mb-1">Sosial Emosional & Kemandirian Anak</strong>
                                    <span class="text-muted small">Mendidik rasa percaya diri, empati menolong sesama teman, kepemimpinan dini, serta keterampilan hidup dasar (*life skills*).</span>
                                </div>
                            </div>
                            <div class="d-flex gap-3 align-items-start p-3 rounded-3" style="background-color: #f8fafc; border-left: 4px solid #0d9488;">
                                <span class="step-badge mt-1" style="background: #0d9488;">4</span>
                                <div>
                                    <strong class="text-dark d-block mb-1">Sinergi Harmonis Bersama Orang Tua (Parenting)</strong>
                                    <span class="text-muted small">Transparansi perkembangan anak dan administrasi keuangan melalui portal digital SI-Alfutuh yang dapat diakses kapan saja.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section class="section-padding bg-white" id="keunggulan">
        <div class="container px-4">
            <div class="text-center mb-5">
                <span class="section-tag"><i class="bi-star"></i> Kenapa Memilih Kami?</span>
                <h2 class="section-title">Program Unggulan RA Al Futuh</h2>
                <p class="section-subtitle">Kurikulum terpadu dan pembiasaan positif yang dirancang khusus untuk memfasilitasi masa keemasan (*golden age*) sang buah hati.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="clean-feature-card">
                        <div class="clean-icon-box icon-box-emerald mb-4">
                            <i class="bi-book"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Dawaamul Qur'an & Tahfidz</h5>
                        <p class="text-muted small mb-0">Hafalan surah-surah pendek pilihan (Juz 30), hadits akhlak harian, dan doa-doa pembiasaan dengan metode talaqqi yang menyenangkan.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="clean-feature-card">
                        <div class="clean-icon-box icon-box-gold mb-4">
                            <i class="bi-puzzle"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Kurikulum Merdeka & STEAM</h5>
                        <p class="text-muted small mb-0">Pembelajaran berbasis proyek (P5-PPRA), eksplorasi sains alam, karya seni kreatif, dan pemanfaatan media *loose parts* ramah lingkungan.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="clean-feature-card">
                        <div class="clean-icon-box icon-box-blue mb-4">
                            <i class="bi-heart-pulse"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Pemeriksaan Fisik & Gizi Berkala</h5>
                        <p class="text-muted small mb-0">Pemantauan berat badan, tinggi badan, lingkar kepala, dan kebersihan diri secara rutin bekerjasama dengan puskesmas setempat.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="clean-feature-card">
                        <div class="clean-icon-box icon-box-teal mb-4">
                            <i class="bi-people"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Pendidik Sabar & Bersertifikasi</h5>
                        <p class="text-muted small mb-0">Guru-guru berdedikasi tinggi, berpendidikan linier PAUD, penyayang anak, dan senantiasa mendampingi dengan keteladanan akhlak mulia.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="clean-feature-card">
                        <div class="clean-icon-box icon-box-emerald mb-4">
                            <i class="bi-laptop"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Sistem Informasi SI-Alfutuh</h5>
                        <p class="text-muted small mb-0">Ayah & Bunda dapat memantau presensi kehadiran, riwayat iuran SPP, kas komite, hingga e-Rapor siap cetak langsung dari smartphone.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="clean-feature-card">
                        <div class="clean-icon-box icon-box-gold mb-4">
                            <i class="bi-tree"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Lingkungan Asri & Ramah Anak</h5>
                        <p class="text-muted small mb-0">Lokasi sekolah yang aman dan nyaman, area bermain luar ruangan hijau, dan fasilitas pembelajaran yang memadai untuk eksplorasi anak.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kabar Sekolah (Berita) Section -->
    <section class="section-padding bg-light" id="berita">
        <div class="container px-4">
            <div class="text-center mb-5">
                <span class="section-tag"><i class="bi-newspaper"></i> Dokumentasi & Pengumuman</span>
                <h2 class="section-title">Kabar Terbaru RA Al Futuh</h2>
                <p class="section-subtitle">Ikuti rekam jejak keceriaan belajar, kegiatan parenting, serta pengumuman agenda resmi madrasah.</p>
            </div>

            <div class="row g-4 justify-content-center">
                @forelse($beritaTerbaru as $berita)
                    <div class="col-md-6 col-lg-4">
                        <div class="clean-news-card">
                            <div class="news-img-box">
                                @if($berita->gambar)
                                    <img src="{{ asset('storage/berita/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=700&q=80" alt="Placeholder Berita">
                                @endif
                                <span class="news-date-badge">
                                    <i class="bi-calendar3 me-1 text-warning"></i> {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="fw-bold text-dark mb-2 lh-base">{{ $berita->judul }}</h5>
                                    <p class="text-muted small mb-4">
                                        {{ Str::limit(strip_tags($berita->konten), 95, '...') }}
                                    </p>
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm rounded-pill fw-semibold py-2 w-100" data-bs-toggle="modal" data-bs-target="#beritaModal{{ $berita->id }}">
                                    Baca Selengkapnya <i class="bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detail Berita -->
                    <div class="modal fade" id="beritaModal{{ $berita->id }}" tabindex="-1" aria-labelledby="beritaModalLabel{{ $berita->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                                <div class="modal-header border-0 pb-0 pt-4 px-4">
                                    <div>
                                        <span class="badge bg-success-subtle text-success mb-2 px-3 py-1 rounded-pill fw-bold">Kabar Sekolah</span>
                                        <h4 class="modal-title fw-bold text-dark" id="beritaModalLabel{{ $berita->id }}">{{ $berita->judul }}</h4>
                                        <div class="small text-muted mt-1">
                                            <i class="bi-clock me-1"></i> {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y') }}
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    @if($berita->gambar)
                                        <img src="{{ asset('storage/berita/' . $berita->gambar) }}" class="w-100 rounded-4 shadow-sm mb-4" style="max-height: 420px; object-fit: cover;" alt="{{ $berita->judul }}">
                                    @endif
                                    <div class="text-secondary lh-lg" style="white-space: pre-line; font-size: 1rem;">
                                        {!! $berita->konten !!}
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="bg-white p-5 rounded-4 border shadow-sm d-inline-block">
                            <i class="bi-journal-x display-4 text-muted opacity-50 mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">Belum Ada Kabar Terbaru</h5>
                            <p class="text-muted small mb-0">Informasi dan dokumentasi kegiatan terbaru akan segera dipublikasikan di sini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($beritaTerbaru->count() >= 3)
                <div class="text-center mt-5">
                    <a href="{{ route('berita.semua') }}" class="btn btn-brand-primary py-3 px-5 fw-bold shadow">
                        Lihat Seluruh Arsip Berita <i class="bi-arrow-right ms-2"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section-padding bg-white" id="faq">
        <div class="container px-4">
            <div class="text-center mb-5">
                <span class="section-tag"><i class="bi-question-circle"></i> Pusat Informasi</span>
                <h2 class="section-title">Pertanyaan Sering Diajukan (FAQ)</h2>
                <p class="section-subtitle">Ringkasan tanya jawab seputar penerimaan siswa baru, kurikulum, dan kegiatan belajar di RA Islam Al Futuh.</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion faq-accordion" id="accordionFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHead1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCol1" aria-expanded="true" aria-controls="faqCol1">
                                    Berapa syarat usia minimal untuk mendaftar di RA Al Futuh?
                                </button>
                            </h2>
                            <div id="faqCol1" class="accordion-collapse collapse show" aria-labelledby="faqHead1" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Untuk <strong>Kelompok Bermain (KB / Toddler)</strong> berusia minimal 3 tahun. Untuk jenjang <strong>Kelas A</strong> berusia 4 - 5 tahun, dan untuk <strong>Kelas B</strong> berusia 5 - 6 tahun terhitung per Juli pada tahun ajaran baru berjalan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHead2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCol2" aria-expanded="false" aria-controls="faqCol2">
                                    Bagaimana sistem pemantauan perkembangan anak oleh orang tua?
                                </button>
                            </h2>
                            <div id="faqCol2" class="accordion-collapse collapse" aria-labelledby="faqHead2" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Setiap wali murid mendapatkan akses akun ke <strong>Portal Si-Alfutuh</strong>. Di dalam sistem, Ayah & Bunda dapat melihat foto-foto kegiatan harian, catatan presensi kehadiran, riwayat iuran SPP dan tabungan, materi parenting madrasah, hingga cetak lembar e-Rapor resmi secara mandiri.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHead3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCol3" aria-expanded="false" aria-controls="faqCol3">
                                    Apakah pendaftaran online (PPDB) ini langsung mengunci kuota kursi?
                                </button>
                            </h2>
                            <div id="faqCol3" class="accordion-collapse collapse" aria-labelledby="faqHead3" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Ya. Setelah Anda mengisi formulir PPDB online di bawah dan mendapatkan Nomor Pendaftaran resmi, data ananda akan langsung masuk ke daftar antrean terverifikasi panitia sekolah. Panitia kami akan menghubungi Anda via WhatsApp untuk konfirmasi berkas fisik saat hari orientasi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHead4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCol4" aria-expanded="false" aria-controls="faqCol4">
                                    Apa saja dokumen yang perlu disiapkan untuk pendaftaran?
                                </button>
                            </h2>
                            <div id="faqCol4" class="accordion-collapse collapse" aria-labelledby="faqHead4" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Cukup siapkan foto/scan yang jelas dari <strong>Pas Foto Calon Siswa</strong>, <strong>Kartu Keluarga (KK)</strong>, dan <strong>Akta Kelahiran</strong> dalam format JPG atau PDF.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PPDB Section -->
    <section class="ppdb-section" id="ppdb">
        <div class="container px-4">
            <div class="text-center mb-5">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                    <i class="bi-pencil-square me-1"></i> Pendaftaran Online 2026/2027
                </span>
                <h2 class="display-5 fw-bold text-white mb-2">Formulir PPDB Online RA Al Futuh</h2>
                <p class="text-white-50 max-w-600 mx-auto fs-6">Silakan lengkapi formulir di bawah ini dengan data yang valid. Proses cepat, mudah, dan langsung terintegrasi.</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if(session('success'))
                        <div class="alert alert-success bg-white text-success fw-bold border-0 shadow-lg p-4 mb-4 rounded-4 text-center">
                            <i class="bi-check-circle display-5 text-success mb-2 d-block"></i>
                            <h5 class="fw-bold mb-1">Pendaftaran Berhasil Terkirim!</h5>
                            <p class="mb-0 text-muted small">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger bg-white text-danger border-0 shadow-lg p-4 mb-4 rounded-4">
                            <div class="d-flex align-items-center gap-2 font-weight-bold mb-2">
                                <i class="bi-exclamation-octagon fs-5"></i>
                                <strong>Mohon periksa kembali isian formulir:</strong>
                            </div>
                            <ul class="mb-0 small ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="ppdb-card">
                        <div class="ppdb-card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h4 class="fw-bold text-dark mb-1">Formulir Pendaftaran Siswa Baru</h4>
                                <small class="text-muted">Isi data lengkap sesuai Kartu Keluarga (KK) dan Akta Kelahiran</small>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                                <i class="bi-shield-lock me-1"></i> Data Terjamin Aman
                            </span>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Bagian 1: Data Siswa -->
                                <div class="mb-5">
                                    <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom">
                                        <span class="step-badge">1</span>
                                        <h5 class="fw-bold text-dark mb-0">Data Calon Peserta Didik</h5>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Nama Lengkap Anak <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" placeholder="Sesuai akta kelahiran" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Nama Panggilan <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_panggilan" class="form-control" value="{{ old('nama_panggilan') }}" placeholder="Nama akrab anak" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">NIK / No. Akta Kelahiran <span class="text-danger">*</span></label>
                                            <input type="text" name="nik_atau_akta" class="form-control" value="{{ old('nik_atau_akta') }}" placeholder="16 digit NIK atau Nomor Registrasi Akta" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select name="jenis_kelamin" class="form-select" required>
                                                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Tempat Lahir <span class="text-danger">*</span></label>
                                            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" placeholder="Contoh: Gunungkidul" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Tanggal Lahir <span class="text-danger">*</span></label>
                                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian 2: Data Orang Tua -->
                                <div class="mb-5">
                                    <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom">
                                        <span class="step-badge" style="background: var(--brand-gold-primary);">2</span>
                                        <h5 class="fw-bold text-dark mb-0">Data Orang Tua / Wali Murid</h5>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Nama Lengkap Ayah <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah') }}" placeholder="Nama kepala keluarga" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Nama Lengkap Ibu <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu') }}" placeholder="Nama ibu kandung" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Pekerjaan Utama Orang Tua <span class="text-danger">*</span></label>
                                            <input type="text" name="pekerjaan_orang_tua" class="form-control" value="{{ old('pekerjaan_orang_tua') }}" placeholder="Contoh: Wiraswasta, PNS, Petani" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">Email Aktif (Untuk Akun Portal) <span class="text-danger">*</span></label>
                                            <input type="email" name="email_ortu" class="form-control" value="{{ old('email_ortu') }}" placeholder="contoh@gmail.com" required>
                                            <small class="text-muted" style="font-size: 0.75rem;">Email ini otomatis menjadi username login ke Portal Wali Murid.</small>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label-custom">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                                            <input type="text" name="no_hp_ortu" class="form-control" value="{{ old('no_hp_ortu') }}" placeholder="Contoh: 081234567890" required>
                                            <small class="text-muted" style="font-size: 0.75rem;">Pastikan terhubung WhatsApp untuk konfirmasi dan informasi orientasi.</small>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label-custom">Alamat Domisili Lengkap <span class="text-danger">*</span></label>
                                            <textarea name="alamat_lengkap" class="form-control" rows="3" placeholder="Nama RT/RW, Dusun, Desa/Kalurahan, Kapanewon, Kabupaten" required>{{ old('alamat_lengkap') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian 3: Unggah Dokumen -->
                                <div class="mb-5">
                                    <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom">
                                        <span class="step-badge" style="background: #2563eb;">3</span>
                                        <h5 class="fw-bold text-dark mb-0">Unggah Berkas Pendukung</h5>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="p-3 border rounded-3 bg-light text-center h-100">
                                                <div class="clean-icon-box icon-box-emerald mx-auto mb-2" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                                    <i class="bi-image"></i>
                                                </div>
                                                <label class="form-label-custom justify-content-center">Pas Foto Anak <span class="text-danger">*</span></label>
                                                <small class="text-muted d-block mb-2" style="font-size: 0.72rem;">Format JPG/PNG, maks 10MB</small>
                                                <input type="file" name="file_foto" class="form-control form-control-sm" accept=".jpg,.jpeg,.png" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="p-3 border rounded-3 bg-light text-center h-100">
                                                <div class="clean-icon-box icon-box-gold mx-auto mb-2" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                                    <i class="bi-file-earmark-person"></i>
                                                </div>
                                                <label class="form-label-custom justify-content-center">Scan / Foto Kartu Keluarga <span class="text-danger">*</span></label>
                                                <small class="text-muted d-block mb-2" style="font-size: 0.72rem;">Format PDF/JPG/PNG, maks 10MB</small>
                                                <input type="file" name="file_kk" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="p-3 border rounded-3 bg-light text-center h-100">
                                                <div class="clean-icon-box icon-box-blue mx-auto mb-2" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                                    <i class="bi-file-earmark-text"></i>
                                                </div>
                                                <label class="form-label-custom justify-content-center">Scan / Foto Akta Kelahiran <span class="text-danger">*</span></label>
                                                <small class="text-muted d-block mb-2" style="font-size: 0.72rem;">Format PDF/JPG/PNG, maks 10MB</small>
                                                <input type="file" name="file_akta" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center pt-2">
                                    <button type="submit" class="btn btn-brand-primary btn-submit-ppdb shadow-md d-inline-flex align-items-center justify-content-center gap-2">
                                        <i class="bi-send"></i>
                                        <span>Kirim Pendaftaran Online</span>
                                    </button>
                                    <p class="text-muted small mt-3 mb-0">
                                        <i class="bi-info-circle me-1"></i> Data Anda tersimpan aman dan terenkripsi pada basis data resmi RA Islam Al Futuh.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-main">
        <div class="container px-4">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo RA Al Futuh" width="50" height="50" class="rounded-circle bg-white p-1">
                        <div>
                            <h5 class="text-white fw-bold mb-0 font-heading">RA ISLAM AL FUTUH</h5>
                            <span class="text-warning small fw-semibold">Pondasi Generasi Qur'ani & Berakhlak Mulia</span>
                        </div>
                    </div>
                    <p class="small text-white-50 mb-3 lh-base">
                        Lembaga Pendidikan Anak Usia Dini Islam terpadu yang membina aqidah, kemandirian karakter, dan kecerdasan anak dengan lingkungan asri serta sarana edukasi modern.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="https://wa.me/6285864480331" target="_blank" class="btn btn-sm btn-outline-light rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi-whatsapp"></i></a>
                        <a href="#!" class="btn btn-sm btn-outline-light rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi-facebook"></i></a>
                        <a href="#!" class="btn btn-sm btn-outline-light rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi-instagram"></i></a>
                        <a href="#!" class="btn btn-sm btn-outline-light rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bi-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase font-heading" style="letter-spacing: 0.05em; font-size: 0.85rem;">Navigasi</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="#hero"><i class="bi-chevron-right text-success me-1"></i> Beranda</a></li>
                        <li><a href="#profil"><i class="bi-chevron-right text-success me-1"></i> Profil & Visi Misi</a></li>
                        <li><a href="#keunggulan"><i class="bi-chevron-right text-success me-1"></i> Program Unggulan</a></li>
                        <li><a href="#berita"><i class="bi-chevron-right text-success me-1"></i> Kabar Sekolah</a></li>
                        <li><a href="#ppdb"><i class="bi-chevron-right text-success me-1"></i> Pendaftaran Online</a></li>
                        <li><a href="{{ route('login') }}"><i class="bi-chevron-right text-success me-1"></i> Portal Masuk</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase font-heading" style="letter-spacing: 0.05em; font-size: 0.85rem;">Jam Belajar & Layanan</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0 text-white-50">
                        <li class="d-flex justify-content-between">
                            <span>Senin - Jumat:</span>
                            <strong class="text-white">07.00 - 12.00 WIB</strong>
                        </li>
                        <li class="d-flex justify-content-between text-warning">
                            <span>Sabtu, Ahad & Libur:</span>
                            <strong>Tutup / Libur</strong>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase font-heading" style="letter-spacing: 0.05em; font-size: 0.85rem;">Kontak & Lokasi</h6>
                    <div class="small text-white-50 d-flex flex-column gap-2">
                        <div>
                            <i class="bi-geo-alt text-warning me-2"></i>
                            <span>Semin, Kabupaten Gunungkidul, D.I. Yogyakarta 55854</span>
                        </div>
                        <div>
                            <i class="bi-envelope text-warning me-2"></i>
                            <span>info@ra-alfutuh.sch.id</span>
                        </div>
                        <div>
                            <i class="bi-telephone text-warning me-2"></i>
                            <span>+62 858-6448-0331</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    &copy; {{ date('Y') }} <strong>RA Islam Al Futuh</strong>. Seluruh hak cipta dilindungi undang-undang.
                </div>
                <div class="d-flex gap-3 text-white-50">
                    <a href="#!">Kebijakan Privasi</a>
                    <span>&bull;</span>
                    <a href="#!">Syarat & Ketentuan</a>
                    <span>&bull;</span>
                    <a href="{{ route('login') }}">Akses Administrator</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6285864480331?text=Halo%20Admin%20RA%20Islam%20Al%20Futuh,%20saya%20ingin%20bertanya%20seputar%20informasi%20sekolah%20dan%20pendaftaran%20PPDB." target="_blank" class="floating-wa-btn" title="Hubungi Panitia via WhatsApp">
        <i class="bi-whatsapp"></i>
    </a>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Smooth shadow transition on scroll for navbar
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('mainNav');
            if (window.scrollY > 40) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
