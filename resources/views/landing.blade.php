<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trackivo — Enterprise Logistics & Cargo Intelligence</title>

    <!-- Google Fonts: Instrument Serif, Manrope & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Manrope:wght@300;400;500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        manrope: ['"Manrope"', 'sans-serif'],
                        serif: ['"Instrument Serif"', 'Georgia', 'serif'],
                    },
                    colors: {
                        'forest-deep': '#111C16',
                        'forest-surface': '#17251D',
                        'forest-charcoal': '#16231B',
                        'forest-muted': '#5A6B60',
                        'sage-sig': '#4E6E58',
                        'sage-sig-hover': '#3E5A47',
                        'sand-warm': '#F3EFE6',
                        'sand-ivory': '#FAF8F4',
                        'sand-gold': '#D8CBB3',
                        'ivory-warm': '#F5F2EB',
                    }
                }
            }
        }
    </script>

    <style>
        /* Hero headline typography */
        .hero-headline-manrope {
            font-family: 'Manrope', sans-serif;
            font-weight: 300;
            letter-spacing: -0.03em;
        }

        html, body {
            overflow-x: clip;
            max-width: 100%;
            background-color: #111C16;
            color: #F5F2EB;
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        /* Hilangkan horizontal scrollbar */
        * {
            box-sizing: border-box;
        }

        /* Subtle scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #111C16;
        }
        ::-webkit-scrollbar-thumb {
            background: #2D4233;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4E6E58;
        }

        /* Reveal on scroll */
        .editorial-reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1), transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }
        .editorial-reveal.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Preloader */
        #preloader-curtain {
            transition: transform 650ms cubic-bezier(0.77, 0, 0.175, 1);
            will-change: transform;
        }
        #preloader-logo-wrap {
            opacity: 0;
            transform: scale(0.92);
            transition: opacity 550ms cubic-bezier(0.25, 1, 0.5, 1), transform 550ms cubic-bezier(0.25, 1, 0.5, 1);
            will-change: opacity, transform;
        }
        #preloader-logo-wrap.is-visible {
            opacity: 1;
            transform: scale(1);
        }

        @media (prefers-reduced-motion: reduce) {
            .editorial-reveal {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            #preloader-curtain {
                display: none !important;
            }
            #truck-parallax-img,
            #truck-unit {
                transform: none !important;
                opacity: 1 !important;
            }
            #hero-tilt-wrapper {
                transform: none !important;
            }
        }

        /* Morphing Navbar Styling (Apple Liquid Glass) */
        #main-nav {
            transition: width 500ms cubic-bezier(0.22, 1, 0.36, 1),
                        height 500ms cubic-bezier(0.22, 1, 0.36, 1),
                        max-width 500ms cubic-bezier(0.22, 1, 0.36, 1),
                        padding 500ms cubic-bezier(0.22, 1, 0.36, 1),
                        border-radius 500ms cubic-bezier(0.22, 1, 0.36, 1),
                        background-color 300ms ease,
                        border-color 300ms ease,
                        color 300ms ease,
                        box-shadow 300ms ease;
            will-change: width, height, max-width, padding;
        }

        #main-nav[data-nav-collapsed="false"] {
            width: 100% !important;
            max-width: 56rem !important; /* max-w-4xl */
            height: 3.25rem !important;
            min-height: 3.25rem !important;
            padding-left: 0.75rem !important;
            padding-right: 1rem !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            border-radius: 9999px !important;
            justify-content: space-between !important;
        }

        @media (min-width: 640px) {
            #main-nav[data-nav-collapsed="false"] {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        @media (min-width: 1024px) {
            #main-nav[data-nav-collapsed="false"] {
                max-width: 64rem !important; /* max-w-5xl */
            }
        }

        #main-nav[data-nav-collapsed="true"] {
            width: 3rem !important;  /* 48px / w-12 */
            height: 3rem !important; /* h-12 / 48px */
            min-height: 3rem !important;
            max-height: 3rem !important;
            max-width: 3rem !important;
            padding: 0 !important;
            border-radius: 9999px !important;
            justify-content: center !important;
            cursor: pointer !important;
        }

        #main-nav .nav-hide-on-collapse {
            transition: opacity 200ms ease;
        }

        #main-nav[data-nav-collapsed="true"] .nav-hide-on-collapse {
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        #main-nav[data-nav-collapsed="true"] .nav-logo-link {
            padding: 0 !important;
            margin: 0 !important;
            justify-content: center !important;
        }

        #main-nav[data-nav-collapsed="false"] .nav-hide-on-collapse {
            opacity: 1;
        }

        #main-nav[data-nav-theme="dark"] {
            background-color: rgba(17, 28, 22, 0.35);
            border-color: rgba(255, 255, 255, 0.20);
            color: #F5F2EB;
        }

        #main-nav[data-nav-theme="light"] {
            background-color: rgba(250, 248, 244, 0.80);
            border-color: rgba(22, 35, 27, 0.15);
            color: #16231B;
        }

        /* ===================================================================== */
        /* NAVBAR THEME & PILL LINK STYLES (Mode Gelap vs Mode Terang)           */
        /* ===================================================================== */

        /* 1. Navbar Mode Gelap (Hero / seksi gelap) */
        #main-nav[data-nav-theme="dark"] .nav-pill-link,
        #main-nav[data-nav-theme="dark"] #nav-login-link {
            color: rgba(245, 242, 235, 0.80);
            transition: color 200ms ease, background-color 200ms ease;
        }

        #main-nav[data-nav-theme="dark"] .nav-pill-link:hover,
        #main-nav[data-nav-theme="dark"] .nav-pill-link:focus,
        #main-nav[data-nav-theme="dark"] #nav-login-link:hover,
        #main-nav[data-nav-theme="dark"] #nav-login-link:focus {
            color: #FFFFFF !important;
            background-color: rgba(255, 255, 255, 0.10) !important;
        }

        /* Item Aktif Mode Gelap: pil kaca terang dengan teks putih tajam */
        #main-nav[data-nav-theme="dark"] .nav-pill-link.nav-pill-active {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #FFFFFF !important;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }
        #main-nav[data-nav-theme="dark"] .nav-pill-link.nav-pill-active:hover {
            background-color: rgba(255, 255, 255, 0.22) !important;
            color: #FFFFFF !important;
        }

        /* 2. Navbar Mode Terang (Seksi #tentang dan seksi krem #F3EFE6 lainnya) */
        #main-nav[data-nav-theme="light"] .nav-pill-link,
        #main-nav[data-nav-theme="light"] #nav-login-link {
            color: rgba(22, 35, 27, 0.80);
            transition: color 200ms ease, background-color 200ms ease;
        }

        /* Hover pada mode terang: WAJIB tetap gelap/hijau tua, DILARANG berubah menjadi putih */
        #main-nav[data-nav-theme="light"] .nav-pill-link:hover,
        #main-nav[data-nav-theme="light"] .nav-pill-link:focus,
        #main-nav[data-nav-theme="light"] #nav-login-link:hover,
        #main-nav[data-nav-theme="light"] #nav-login-link:focus {
            color: #16231B !important;
            background-color: rgba(22, 35, 27, 0.07) !important;
        }

        /* Item Aktif Mode Terang (misal #tentang): latar solid Signature Forest Sage #4E6E58 text #F5F2EB */
        #main-nav[data-nav-theme="light"] .nav-pill-link.nav-pill-active {
            background-color: #4E6E58 !important;
            color: #F5F2EB !important;
        }

        #main-nav[data-nav-theme="light"] .nav-pill-link.nav-pill-active:hover {
            background-color: #3E5A47 !important;
            color: #F5F2EB !important;
        }

        /* Mobile Toggle Button Hover by Theme */
        #main-nav[data-nav-theme="dark"] #mobile-menu-toggle:hover {
            background-color: rgba(255, 255, 255, 0.10);
        }
        #main-nav[data-nav-theme="light"] #mobile-menu-toggle:hover {
            background-color: rgba(22, 35, 27, 0.07);
        }

        /* Tombol Auth Solid Sage (#4E6E58 -> hover #3E5A47) */
        .nav-btn-primary {
            background-color: #4E6E58;
            color: #F5F2EB;
            transition: background-color 200ms ease, color 200ms ease;
        }
        .nav-btn-primary:hover {
            background-color: #3E5A47;
            color: #F5F2EB;
        }

        /* Seksi #tentang Masking Foto Desktop Seamless (Zero-Seam Fade) */
        @media (min-width: 1024px) {
            .tentang-photo-mask {
                -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 12%, rgba(0,0,0,0.35) 38%, rgba(0,0,0,0.85) 65%, rgba(0,0,0,1) 88%);
                mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 12%, rgba(0,0,0,0.35) 38%, rgba(0,0,0,0.85) 65%, rgba(0,0,0,1) 88%);
            }
        }

        /* Custom hide scrollbar for carousel */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        @media (min-width: 1024px) {
            .layanan-photo-mask {
                -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 20%, rgba(0,0,0,0.85) 48%, rgba(0,0,0,0.35) 75%, rgba(0,0,0,0) 96%, rgba(0,0,0,0) 100%);
                mask-image: linear-gradient(to right, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 20%, rgba(0,0,0,0.85) 48%, rgba(0,0,0,0.35) 75%, rgba(0,0,0,0) 96%, rgba(0,0,0,0) 100%);
            }
        }

        /* Seksi #performa — Ambient floating mikro horizontal (menjaga sumbu Y tetap terkunci di titik belahan) */
        @keyframes planeAmbientFloat {
            0%, 100% {
                transform: translateX(0px) scale(1);
            }
            50% {
                transform: translateX(-3px) scale(1.004);
            }
        }
        .plane-ambient-float {
            animation: planeAmbientFloat 6s ease-in-out infinite;
        }
        @media (prefers-reduced-motion: reduce) {
            .plane-ambient-float {
                animation: none !important;
            }
        }

        .layanan-accordion-content {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 350ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .layanan-accordion-content.is-open {
            grid-template-rows: 1fr;
        }
        .layanan-accordion-inner {
            overflow: hidden;
            min-height: 0;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col selection:bg-[#4E6E58] selection:text-[#FAF8F4] overflow-x-clip">

    <!-- ===================================================================== -->
    <!-- A. SMOOTH PRELOADER LOGO REVEAL (Halus & Non-Blocking) -->
    <!-- ===================================================================== -->
    <div id="preloader-curtain" class="fixed inset-0 z-[100] bg-[#111C16] flex items-center justify-center">
        <div id="preloader-logo-wrap" class="flex items-center justify-center">
            <img src="{{ asset('images/landing/logo-white.png') }}" alt="Trackivo" class="h-16 sm:h-20 w-auto object-contain" />
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- B. FLOATING PILL GLASS NAVBAR BERGAYA APPLE -->
    <!-- ===================================================================== -->
    <header class="fixed top-4 inset-x-0 z-40 px-4 sm:px-6 pointer-events-none flex flex-col items-center">
        <div id="main-nav" data-nav-collapsed="false" data-nav-theme="dark" class="pointer-events-auto backdrop-blur-xl border shadow-[0_12px_32px_rgba(10,18,14,0.28)] flex items-center bg-[#111C16]/35 border-white/20 text-[#F5F2EB]">
            
            <!-- Kiri: Logo Trackivo (Ikon tetap muncul saat collapsed, teks disembunyikan) -->
            <a href="{{ url('/') }}" class="nav-logo-link flex items-center gap-2.5 shrink-0 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Trackivo" class="w-8 h-8 object-contain transition-transform group-hover:scale-105" />
                <span class="text-lg font-bold tracking-tight nav-hide-on-collapse" id="nav-brand-text">Trackivo</span>
            </a>

            <!-- Tengah: 5 Menu Navigasi Rapi (Desktop, Disembunyikan saat collapsed) -->
            <nav class="hidden lg:flex items-center gap-1 nav-hide-on-collapse" id="nav-pill-container">
                <a href="#tentang" class="nav-pill-link px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors" data-target="tentang">Tentang</a>
                <a href="#layanan" class="nav-pill-link px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors" data-target="layanan">Layanan</a>
                <a href="#solusi" class="nav-pill-link px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors" data-target="solusi">Solusi</a>
                <a href="#performa" class="nav-pill-link px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors" data-target="performa">Performa</a>
                <a href="#pelacakan" class="nav-pill-link px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors" data-target="pelacakan">Lacak Resi</a>
            </nav>

            <!-- Kanan: Tombol Auth Sisi Kanan (Desktop, Disembunyikan saat collapsed) -->
            <div class="hidden sm:flex items-center gap-2 shrink-0 nav-hide-on-collapse">
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-btn-primary px-4 py-2 rounded-full text-[#F5F2EB] text-xs font-semibold transition shadow-sm">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-medium transition rounded-full" id="nav-login-link">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="nav-btn-primary px-4 py-2 text-xs font-semibold rounded-full text-[#F5F2EB] transition shadow-sm">
                        Daftar
                    </a>
                @endauth
            </div>

            <!-- Tombol Menu Mobile (< 1024px, Disembunyikan saat collapsed) -->
            <div class="flex lg:hidden nav-hide-on-collapse">
                <button type="button" id="mobile-menu-toggle" aria-label="Buka Menu" class="p-2 rounded-full border border-current/20 text-current hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

        </div>

        <!-- Mobile Floating Menu Panel (< 1024px) -->
        <div id="mobile-menu-drawer" class="pointer-events-auto hidden lg:hidden w-full max-w-4xl lg:max-w-5xl mx-auto mt-2 rounded-3xl border border-white/15 bg-[#111C16]/95 backdrop-blur-2xl p-5 space-y-3 shadow-2xl text-[#F5F2EB]">
            <a href="#tentang" class="mobile-nav-link block px-3 py-2 rounded-xl text-sm font-medium hover:bg-white/10">Tentang</a>
            <a href="#layanan" class="mobile-nav-link block px-3 py-2 rounded-xl text-sm font-medium hover:bg-white/10">Layanan</a>
            <a href="#solusi" class="mobile-nav-link block px-3 py-2 rounded-xl text-sm font-medium hover:bg-white/10">Solusi</a>
            <a href="#performa" class="mobile-nav-link block px-3 py-2 rounded-xl text-sm font-medium hover:bg-white/10">Performa</a>
            <a href="#pelacakan" class="mobile-nav-link block px-3 py-2 rounded-xl text-sm font-medium hover:bg-white/10">Lacak Resi</a>
            <div class="pt-3 border-t border-white/10 flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full text-center py-2.5 rounded-full bg-[#4E6E58] text-[#FAF8F4] font-semibold text-xs">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center py-2 text-xs font-medium text-[#F5F2EB] hover:bg-white/5 rounded-full">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-full bg-[#4E6E58] text-[#FAF8F4] font-semibold text-xs">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ===================================================================== -->
    <!-- C. SEKSI 1 — FULL-BLEED EDITORIAL HERO (hero-crane.jpg Utuh + 3D Tilt Parallax) -->
    <!-- ===================================================================== -->
    <section id="hero" class="relative h-[100svh] min-h-[620px] max-h-[960px] overflow-hidden flex items-end pb-16 sm:pb-20 lg:items-center lg:pb-0 bg-[#111C16]" style="perspective: 1000px;">
        <!-- Lapisan Foto Hero Utuh dengan Pembungkus Sedikit Diperbesar & Efek 3D Cursor Tilt Parallax -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="w-full h-full scale-[1.04] overflow-hidden">
                <div id="hero-tilt-wrapper" class="w-full h-full will-change-transform" style="transform-style: preserve-3d; backface-visibility: hidden;">
                    <img src="{{ asset('images/landing/hero-crane.jpg') }}" alt="Trackivo Container Terminal & Logistics" class="w-full h-full object-cover object-[center_35%] lg:object-center select-none" />
                </div>
            </div>

            <!-- Lapisan Gradasi Kiri: Lembut Terarah Menjaga Keterbacaan Teks (Area Kontainer Tetap Terang Alami) -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#111C16]/75 via-[#111C16]/25 to-transparent pointer-events-none"></div>

            <!-- Lapisan Gradasi Bawah Khusus Mobile: Memastikan teks Hero di paruh bawah terbaca 100% tanpa menabrak kontainer -->
            <div class="lg:hidden absolute inset-x-0 bottom-0 h-[55%] bg-gradient-to-t from-[#111C16]/85 via-[#111C16]/45 to-transparent pointer-events-none"></div>
        </div>

        <!-- Kontainer Headline Hero: Bagian Bawah di Mobile & Pas di Tengah Vertikal di Desktop -->
        <div class="absolute inset-0 z-10 flex items-end pb-16 sm:pb-20 lg:items-center lg:pb-0 pointer-events-none">
            <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-14">
                <!-- Headline Editorial Display di Sisi Kiri (Tanpa Elemen Lain di Bawahnya) -->
                <div class="max-w-4xl editorial-reveal pointer-events-auto">
                    <h1 class="hero-headline-manrope text-4xl sm:text-5xl lg:text-[3.75rem] xl:text-[4.25rem] text-[#F5F2EB] leading-[1.06]" style="font-family: 'Manrope', sans-serif; font-weight: 300; letter-spacing: -0.03em;">
                        Menggerakkan<br>
                        bisnis maju.<br>
                        Satu <span class="italic font-serif font-normal text-[#D8CBB3] tracking-normal" style="font-family: 'Instrument Serif', Georgia, serif; font-weight: 400; font-style: italic; letter-spacing: normal;">pengiriman</span> setiap<br>
                        waktu.
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- D. SEKSI 2 — BRAND STORY FULL-BLEED EDITORIAL (#tentang, Warm Sand Cream #F3EFE6) -->
    <!-- ===================================================================== -->
    <section id="tentang" data-nav-light="true" class="relative overflow-hidden min-h-[85vh] flex items-center py-24 lg:py-32 bg-[#F3EFE6]">
        <!-- Foto Full-Bleed Sisi Kanan Meluas ke Tengah dengan Gradasi Peleburan Super Mulus (100% Seamless Zero-Seam Fade) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <img src="{{ asset('images/landing/warehouse-sorting.jpg') }}" alt="Trackivo Intelligence Center" loading="lazy" class="tentang-photo-mask absolute inset-y-0 right-0 w-full lg:w-[68%] h-full object-cover object-center" />
            
            <!-- Desktop: Lapisan Gradasi Horizontal Mulus dari Cream Trackivo Padat ke Transparan Bebas Garis Vertikal -->
            <div class="hidden lg:block absolute inset-0 pointer-events-none" style="background: linear-gradient(to right, #F3EFE6 0%, #F3EFE6 36%, rgba(243,239,230,0.82) 50%, rgba(243,239,230,0.35) 66%, rgba(243,239,230,0) 85%);"></div>
            <!-- Sentuhan Lembut di Tepi Atas & Bawah agar Menyatu Sempurna -->
            <div class="hidden lg:block absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-[#F3EFE6] to-transparent pointer-events-none"></div>
            <div class="hidden lg:block absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#F3EFE6] to-transparent pointer-events-none"></div>

            <!-- Mobile (< 1024px): Gradasi Vertikal Halus Tanpa Garis Batas agar Teks Tetap 100% Terbaca Tajam -->
            <div class="block lg:hidden absolute inset-0 pointer-events-none bg-gradient-to-t from-[#F3EFE6] via-[#F3EFE6]/90 to-[#F3EFE6]/40"></div>
        </div>

        <!-- Konten Teks di Sisi Kiri di Atas Area Cream Solid -->
        <div class="relative z-10 w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-14">
            <div class="max-w-xl lg:max-w-[560px] editorial-reveal">
                
                <!-- Label Atas: Filosofi & Presisi dengan Garis Aksen Tipis Elegan -->
                <div class="inline-flex items-center gap-3">
                    <span class="w-8 h-[1.5px] bg-[#4E6E58]"></span>
                    <span class="text-xs sm:text-sm font-semibold tracking-[0.14em] uppercase text-[#4E6E58]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Filosofi & Presisi</span>
                </div>

                <!-- Judul Utama: Manrope 300, leading-[1.24] lg:leading-[1.26], tracking-[-0.015em], aksen italic serif halus -->
                <h2 class="mt-4 text-3xl sm:text-4xl lg:text-[2.85rem] text-[#16231B] leading-[1.24] lg:leading-[1.26] tracking-[-0.015em]" style="font-family: 'Manrope', sans-serif; font-weight: 300; letter-spacing: -0.015em;">
                    Dibangun atas <span class="italic font-serif font-normal text-[#4E6E58] tracking-normal" style="font-family: 'Instrument Serif', Georgia, serif; font-weight: 400; font-style: italic; letter-spacing: normal;">presisi</span> untuk efisiensi logistik dan <span class="italic font-serif font-normal text-[#4E6E58] tracking-normal" style="font-family: 'Instrument Serif', Georgia, serif; font-weight: 400; font-style: italic; letter-spacing: normal;">transparansi</span> setiap pengiriman.
                </h2>

                <!-- Paragraf Deskripsi Lembut: text-[0.975rem] sm:text-[1.025rem] leading-[1.82] text-[#435349] max-w-[500px] -->
                <p class="mt-6 text-[0.975rem] sm:text-[1.025rem] leading-[1.82] text-[#435349] max-w-[500px] font-normal" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Trackivo merekayasa ulang interaksi rantai pasok global dengan menyatukan ruang kerja terpisah milik 
                    <strong class="font-semibold text-[#3D5B47]">Administrator</strong>, 
                    <strong class="font-semibold text-[#3D5B47]">Operator Lapangan</strong>, dan 
                    <strong class="font-semibold text-[#3D5B47]">Customer</strong> ke dalam satu arsitektur terpadu real-time. Setiap nomor resi, dokumen manifes, hingga pemutakhiran telemetri cuaca divalidasi langsung tanpa jeda.
                </p>

                <!-- 3 Poin Pilar: Editorial Numbers Tipis + Subjudul Bersih -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 mt-8 border-t border-[#16231B]/12">
                    <div class="space-y-1">
                        <span class="block font-light text-2xl text-[#4E6E58]" style="font-family: 'Manrope', sans-serif;">01</span>
                        <span class="block font-medium text-sm text-[#16231B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Multi-Peran Terintegrasi</span>
                    </div>
                    <div class="space-y-1">
                        <span class="block font-light text-2xl text-[#4E6E58]" style="font-family: 'Manrope', sans-serif;">02</span>
                        <span class="block font-medium text-sm text-[#16231B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Audit & Keamanan Ketat</span>
                    </div>
                    <div class="space-y-1">
                        <span class="block font-light text-2xl text-[#4E6E58]" style="font-family: 'Manrope', sans-serif;">03</span>
                        <span class="block font-medium text-sm text-[#16231B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Telemetri Real-Time</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- E. SEKSI 3 — LAYANAN LOGISTIK TERINTEGRASI (#layanan, Seksi Gelap #111C16 Full-Bleed) -->
    <!-- ===================================================================== -->
    <section id="layanan" class="relative overflow-hidden bg-[#111C16] min-h-[100svh] lg:h-[100svh] lg:max-h-[900px] flex items-center py-20 lg:py-0">
        <!-- Lapisan Foto Full-Bleed Sisi Kiri dengan Seamless Zero-Seam Fade ke Kanan -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="layanan-photo-mask absolute inset-y-0 left-0 w-full lg:w-[68%] h-full overflow-hidden">
                <div class="w-full h-full scale-x-[-1] [transform:scaleX(-1)]">
                    <img id="layanan-bg-photo" 
                         src="{{ asset('images/landing/trust-port.jpg') }}" 
                         alt="Operasional Pelabuhan & Terminal Kontainer Trackivo" 
                         loading="lazy" 
                         class="w-full h-full object-cover object-center transition-transform duration-700 ease-out scale-100 will-change-transform" />
                </div>
            </div>
            
            <!-- Desktop: Lapisan Gradasi Halus Hijau Tua #111C16 Padat ke Transparan Mulus (Zero-Seam Fade ke Kiri) -->
            <div class="hidden lg:block absolute inset-0 pointer-events-none" style="background: linear-gradient(to left, #111C16 0%, #111C16 36%, rgba(17,28,22,0.86) 52%, rgba(17,28,22,0.34) 70%, rgba(17,28,22,0.12) 100%);"></div>

            <!-- Mobile (< 1024px): Gradasi Vertikal Lembut agar Teks Tetap Sangat Tajam Terbaca -->
            <div class="block lg:hidden absolute inset-0 pointer-events-none bg-gradient-to-t from-[#111C16] via-[#111C16]/90 to-[#111C16]/45"></div>
        </div>

        <!-- Konten Teks di Sisi Kanan di Atas Area Hijau Tua Solid -->
        <div class="relative z-10 w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-14 flex lg:justify-end">
            <div class="w-full max-w-xl lg:max-w-[560px] text-left lg:text-right editorial-reveal">
                
                <!-- Label Atas: Layanan Terintegrasi dengan Garis Aksen Tipis (Plus Jakarta Sans) -->
                <div class="flex items-center gap-3 justify-start lg:justify-end">
                    <span class="text-xs sm:text-sm font-semibold tracking-[0.14em] uppercase text-[#8FB09A] order-2 lg:order-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Layanan Terintegrasi</span>
                    <span class="w-8 h-[1.5px] bg-[#8FB09A] order-1 lg:order-2"></span>
                </div>

                <!-- Judul Utama: Manrope 300, leading-[1.22], tracking-[-0.015em], aksen italic serif halus -->
                <h2 class="mt-4 text-3xl sm:text-4xl lg:text-[2.65rem] text-[#F5F2EB] leading-[1.22] tracking-[-0.015em] text-left lg:text-right" style="font-family: 'Manrope', sans-serif; font-weight: 300; letter-spacing: -0.015em;">
                    Logistik <span class="italic font-normal text-[#D8CBB3]" style="font-family: 'Instrument Serif', Georgia, serif;">terintegrasi</span> yang dirancang untuk bisnis Anda.
                </h2>

                <!-- Daftar Layanan Accordion Garis Tipis (Bebas Kartu & Pasti Muat 1 Layar) -->
                <div class="mt-8 space-y-0">
                    
                    <!-- Item 01: Peta Control Tower & Multi-Moda (Default Aktif) -->
                    <div class="layanan-accordion-item relative border-t border-white/12 transition-colors duration-300">
                        <button type="button" class="layanan-header relative w-full py-4 sm:py-4.5 flex items-center text-left lg:text-right cursor-pointer group focus:outline-none">
                            <span class="layanan-accent-line absolute -left-3 sm:-left-4 lg:left-auto lg:-right-4 top-1/2 -translate-y-1/2 w-[2px] h-6 bg-[#4E6E58] rounded-full transition-all duration-300 opacity-100 scale-y-100"></span>
                            <div class="flex items-center gap-4 sm:gap-5 flex-1 lg:flex-row-reverse lg:justify-start">
                                <span class="layanan-num font-light text-xl sm:text-2xl text-[#D8CBB3] transition-colors duration-300" style="font-family: 'Manrope', sans-serif;">01</span>
                                <h3 class="layanan-title font-light text-lg sm:text-xl lg:text-[1.28rem] text-[#F5F2EB] tracking-tight transition-colors duration-300 text-left lg:text-right" style="font-family: 'Manrope', sans-serif;">Peta Control Tower & Multi-Moda</h3>
                            </div>
                        </button>
                        <div class="layanan-accordion-content is-open">
                            <div class="layanan-accordion-inner">
                                <div class="pb-5 pt-0 pl-9 sm:pl-11 lg:pl-0 lg:pr-10 space-y-3.5 text-left lg:text-right">
                                    <p class="text-[0.875rem] sm:text-[0.925rem] leading-[1.65] text-[#F5F2EB]/75 max-w-[500px] lg:ml-auto text-left lg:text-right" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Pemantauan langsung jalur Ocean, Air, dan Land lengkap dengan posisi koordinat terkini, estimasi singgah hub, dan status muatan kontainer.
                                    </p>
                                    <div class="flex flex-wrap items-center gap-6 pt-1 lg:justify-end text-left lg:text-right">
                                        <div class="text-left lg:text-right">
                                            <span class="block text-base sm:text-lg font-light text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif;">3 Moda</span>
                                            <span class="block text-xs font-normal text-[#8FB09A]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Ocean, Air & Land</span>
                                        </div>
                                        <div class="h-7 border-l border-white/15"></div>
                                        <div class="text-left lg:text-right">
                                            <span class="block text-base sm:text-lg font-light text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif;">Global Hubs</span>
                                            <span class="block text-xs font-normal text-[#8FB09A]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Status Posisi Langsung</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 02: Kalkulator Tarif IDR & Struk QR -->
                    <div class="layanan-accordion-item relative border-t border-white/12 transition-colors duration-300">
                        <button type="button" class="layanan-header relative w-full py-4 sm:py-4.5 flex items-center text-left lg:text-right cursor-pointer group focus:outline-none">
                            <span class="layanan-accent-line absolute -left-3 sm:-left-4 lg:left-auto lg:-right-4 top-1/2 -translate-y-1/2 w-[2px] h-6 bg-[#4E6E58] rounded-full transition-all duration-300 opacity-0 scale-y-50"></span>
                            <div class="flex items-center gap-4 sm:gap-5 flex-1 lg:flex-row-reverse lg:justify-start">
                                <span class="layanan-num font-light text-xl sm:text-2xl text-[#F5F2EB]/55 group-hover:text-[#F5F2EB] transition-colors duration-300" style="font-family: 'Manrope', sans-serif;">02</span>
                                <h3 class="layanan-title font-light text-lg sm:text-xl lg:text-[1.28rem] text-[#F5F2EB]/55 group-hover:text-[#F5F2EB] tracking-tight transition-colors duration-300 text-left lg:text-right" style="font-family: 'Manrope', sans-serif;">Kalkulator Tarif IDR & Struk QR</h3>
                            </div>
                        </button>
                        <div class="layanan-accordion-content">
                            <div class="layanan-accordion-inner">
                                <div class="pb-5 pt-0 pl-9 sm:pl-11 lg:pl-0 lg:pr-10 space-y-3.5 text-left lg:text-right">
                                    <p class="text-[0.875rem] sm:text-[0.925rem] leading-[1.65] text-[#F5F2EB]/75 max-w-[500px] lg:ml-auto text-left lg:text-right" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Estimasi ongkos kirim Rupiah transparan berdasarkan berat, volume (CBM), moda, dan asuransi lengkap dengan bukti bayar serta struk ber-QR code.
                                    </p>
                                    <div class="flex flex-wrap items-center gap-6 pt-1 lg:justify-end text-left lg:text-right">
                                        <div class="text-left lg:text-right">
                                            <span class="block text-base sm:text-lg font-light text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif;">Tarif IDR</span>
                                            <span class="block text-xs font-normal text-[#8FB09A]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Berat, Volume & Asuransi</span>
                                        </div>
                                        <div class="h-7 border-l border-white/15"></div>
                                        <div class="text-left lg:text-right">
                                            <span class="block text-base sm:text-lg font-light text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif;">Struk QR</span>
                                            <span class="block text-xs font-normal text-[#8FB09A]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Pembayaran Terverifikasi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 03: Deteksi Delay & Telemetri Cuaca -->
                    <div class="layanan-accordion-item relative border-t border-b border-white/12 transition-colors duration-300">
                        <button type="button" class="layanan-header relative w-full py-4 sm:py-4.5 flex items-center text-left lg:text-right cursor-pointer group focus:outline-none">
                            <span class="layanan-accent-line absolute -left-3 sm:-left-4 lg:left-auto lg:-right-4 top-1/2 -translate-y-1/2 w-[2px] h-6 bg-[#4E6E58] rounded-full transition-all duration-300 opacity-0 scale-y-50"></span>
                            <div class="flex items-center gap-4 sm:gap-5 flex-1 lg:flex-row-reverse lg:justify-start">
                                <span class="layanan-num font-light text-xl sm:text-2xl text-[#F5F2EB]/55 group-hover:text-[#F5F2EB] transition-colors duration-300" style="font-family: 'Manrope', sans-serif;">03</span>
                                <h3 class="layanan-title font-light text-lg sm:text-xl lg:text-[1.28rem] text-[#F5F2EB]/55 group-hover:text-[#F5F2EB] tracking-tight transition-colors duration-300 text-left lg:text-right" style="font-family: 'Manrope', sans-serif;">Deteksi Delay & Telemetri Cuaca</h3>
                            </div>
                        </button>
                        <div class="layanan-accordion-content">
                            <div class="layanan-accordion-inner">
                                <div class="pb-5 pt-0 pl-9 sm:pl-11 lg:pl-0 lg:pr-10 space-y-3.5 text-left lg:text-right">
                                    <p class="text-[0.875rem] sm:text-[0.925rem] leading-[1.65] text-[#F5F2EB]/75 max-w-[500px] lg:ml-auto text-left lg:text-right" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Pemantauan cuaca lokasi kargo secara real-time dan pelaporan kendala rute (exceptions) dengan klasifikasi keparahan serta langkah mitigasi.
                                    </p>
                                    <div class="flex flex-wrap items-center gap-6 pt-1 lg:justify-end text-left lg:text-right">
                                        <div class="text-left lg:text-right">
                                            <span class="block text-base sm:text-lg font-light text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif;">Cuaca Live</span>
                                            <span class="block text-xs font-normal text-[#8FB09A]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Telemetri Lokasi Kargo</span>
                                        </div>
                                        <div class="h-7 border-l border-white/15"></div>
                                        <div class="text-left lg:text-right">
                                            <span class="block text-base sm:text-lg font-light text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif;">Mitigasi Cepat</span>
                                            <span class="block text-xs font-normal text-[#8FB09A]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Pelaporan Kendala Proaktif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- F. SEKSI 4 — SOLUSI TRANSPORTASI + TRUK TRANSPARAN & 3 KARTU METRIK KOMPAK (#solusi, Seksi Terang Warm Sand #F3EFE6) -->
    <!-- ===================================================================== -->
    <section id="solusi" data-nav-light="true" class="relative overflow-x-clip bg-[#F3EFE6] min-h-[100svh] lg:h-[100svh] lg:max-h-[900px] flex items-center pt-28 pb-16 lg:py-0 border-t border-[#D8CBB3]/50">
        <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-14 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            
            <!-- Kolom Kiri (7 Kolom): Header Editorial + Truk Transparan Melaju -->
            <div class="lg:col-span-7 flex flex-col justify-center editorial-reveal">
                <div class="pt-6 lg:pt-0 lg:mt-8">
                    <!-- Label Atas: Jaringan Armada Intermodal dengan Garis Aksen Tipis -->
                    <div class="inline-flex items-center gap-3">
                        <span class="w-8 h-[1.5px] bg-[#4E6E58]"></span>
                        <span class="text-xs sm:text-sm font-semibold tracking-[0.14em] uppercase text-[#4E6E58]" style="font-family: 'Plus Jakarta Sans', sans-serif;">JARINGAN ARMADA INTERMODAL</span>
                    </div>

                    <!-- Judul Utama: Manrope 300, leading-[1.32], tracking-[-0.015em] -->
                    <h2 class="mt-4 text-3xl sm:text-4xl lg:text-[2.75rem] text-[#16231B] leading-[1.32] tracking-[-0.015em] max-w-[500px]" style="font-family: 'Manrope', sans-serif; font-weight: 300; letter-spacing: -0.015em;">
                        Solusi transportasi yang<br class="hidden sm:block"> melaju <span class="italic font-serif font-normal text-[#4E6E58] tracking-normal" style="font-family: 'Instrument Serif', Georgia, serif; font-weight: 400; font-style: italic; letter-spacing: normal;">lebih jauh.</span>
                    </h2>

                    <!-- Subjudul / Deskripsi: Plus Jakarta Sans -->
                    <p class="mt-4 text-[0.975rem] leading-[1.75] text-[#435349] max-w-[520px]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Integrasi truk kargo jarak jauh, tongkang pesisir, dan penerbangan kargo terjadwal untuk menjamin ketepatan waktu pengiriman barang Anda.
                    </p>
                </div>

                <!-- Truk Transparan Tanpa Kartu Belakang + Bayangan Lantai Alami -->
                <div class="mt-4 lg:mt-5 mb-6 lg:mb-6 relative top-0 lg:top-7 select-none w-full max-w-[680px] xl:max-w-[740px] lg:-ml-4">
                    <div id="truck-unit" class="w-full will-change-transform transition-none" style="transform: translateX(-95%); opacity: 0.15;">
                        <img id="truck-parallax-img" 
                             src="{{ asset('images/landing/solusi-truck.png') }}" 
                             alt="Truk Kargo Trackivo Intermodal" 
                             loading="lazy" 
                             class="w-full max-w-[680px] xl:max-w-[740px] lg:-ml-4 h-auto object-contain select-none pointer-events-none will-change-transform" />
                        <div class="h-4 w-[84%] mx-auto -mt-3 rounded-full bg-[#16231B]/18 blur-md pointer-events-none"></div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan (5 Kolom): 3 Kartu Metrik Susunan Vertikal Editorial Rapi & Kompak -->
            <div class="lg:col-span-5 flex flex-col gap-4 lg:gap-3.5 max-w-[440px] lg:ml-auto w-full justify-center editorial-reveal">
                
                <!-- Kartu 1: Total Volume (Warm Alabaster) -->
                <div class="h-auto rounded-2xl p-6 pb-6 lg:px-7 lg:py-5 lg:pb-5 bg-[#FAF8F4] border border-[#16231B]/10 shadow-[inset_0_1px_0_rgba(255,255,255,0.9),0_12px_28px_-14px_rgba(22,35,27,0.14)] transition-all duration-300 hover:-translate-y-0.5">
                    <!-- Baris 1: Label Atas + Aksen Garis Tipis -->
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#4E6E58]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Total Volume</span>
                        <span class="w-5 h-[1.5px] bg-[#4E6E58]/40 rounded-full" aria-hidden="true"></span>
                    </div>

                    <!-- Baris 2: Angka Besar -->
                    <div class="mt-2.5 mb-3">
                        <span class="text-4xl lg:text-[2.85rem] font-light leading-none tracking-tight tabular-nums text-[#16231B]" style="font-family: 'Manrope', sans-serif; font-weight: 300;">
                            <span class="stat-number" data-target="{{ $totalShipments }}">{{ $totalShipments }}</span><span class="text-[#4E6E58] font-normal">+</span>
                        </span>
                    </div>

                    <!-- Baris 3: Judul & Deskripsi Ringkas -->
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        <h3 class="text-[0.95rem] font-semibold leading-snug text-[#16231B]">
                            Pengiriman Terdata
                        </h3>
                        <p class="text-xs leading-relaxed text-[#5A6B60] mt-0.5">
                            Seluruh kargo tervalidasi dalam basis data Trackivo.
                        </p>
                    </div>
                </div>

                <!-- Kartu 2: Jaringan Fasilitas (Deep Forest Sage) -->
                <div class="h-auto rounded-2xl p-6 pb-6 lg:px-7 lg:py-5 lg:pb-5 bg-gradient-to-br from-[#1B2B21] to-[#111C16] border border-white/10 shadow-[inset_0_1px_0_rgba(255,255,255,0.08),0_16px_32px_-14px_rgba(17,28,22,0.5)] transition-all duration-300 hover:-translate-y-0.5">
                    <!-- Baris 1: Label Atas + Aksen Garis Tipis -->
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#8FB09A]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Jaringan Fasilitas</span>
                        <span class="w-5 h-[1.5px] bg-[#8FB09A]/40 rounded-full" aria-hidden="true"></span>
                    </div>

                    <!-- Baris 2: Angka Besar -->
                    <div class="mt-2.5 mb-3">
                        <span class="text-4xl lg:text-[2.85rem] font-light leading-none tracking-tight tabular-nums text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif; font-weight: 300;">
                            <span class="stat-number" data-target="{{ $totalLocations }}">{{ $totalLocations }}</span><span class="text-[#D8CBB3] font-normal">+</span>
                        </span>
                    </div>

                    <!-- Baris 3: Judul & Deskripsi Ringkas -->
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        <h3 class="text-[0.95rem] font-semibold leading-snug text-[#F5F2EB]">
                            Hub & Mitra Carrier ({{ $totalCarriers }})
                        </h3>
                        <p class="text-xs leading-relaxed text-[#F5F2EB]/65 mt-0.5">
                            Hub & pelabuhan terhubung di seluruh koridor niaga.
                        </p>
                    </div>
                </div>

                <!-- Kartu 3: SLA Ketepatan (Signature Forest Sage) -->
                <div class="h-auto rounded-2xl p-6 pb-6 lg:px-7 lg:py-5 lg:pb-5 bg-gradient-to-br from-[#52745D] to-[#3E5A47] border border-white/15 shadow-[inset_0_1px_0_rgba(255,255,255,0.2),0_16px_32px_-14px_rgba(62,90,71,0.4)] transition-all duration-300 hover:-translate-y-0.5">
                    <!-- Baris 1: Label Atas + Aksen Garis Tipis -->
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#D8CBB3]" style="font-family: 'Plus Jakarta Sans', sans-serif;">SLA Ketepatan</span>
                        <span class="w-5 h-[1.5px] bg-[#D8CBB3]/40 rounded-full" aria-hidden="true"></span>
                    </div>

                    <!-- Baris 2: Angka Besar -->
                    <div class="mt-2.5 mb-3">
                        <span class="text-4xl lg:text-[2.85rem] font-light leading-none tracking-tight tabular-nums text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif; font-weight: 300;">
                            <span class="stat-number" data-target="{{ $onTimePercentage }}">{{ $onTimePercentage }}</span><span class="text-[#D8CBB3] font-normal">%</span>
                        </span>
                    </div>

                    <!-- Baris 3: Judul & Deskripsi Ringkas -->
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        <h3 class="text-[0.95rem] font-semibold leading-snug text-[#F5F2EB]">
                            Ketepatan Pengiriman
                        </h3>
                        <p class="text-xs leading-relaxed text-[#F5F2EB]/80 mt-0.5">
                            Kargo tiba sesuai estimasi ETA tanpa deviasi kritis.
                        </p>
                    </div>

                    <!-- Bilah Progres Super Tipis Elegan Terisi Sesuai Persentase Dinamis SLA -->
                    <div class="h-[3px] w-full rounded-full bg-white/15 mt-4 lg:mt-3 overflow-hidden">
                        <div class="h-full bg-[#F5F2EB] rounded-full transition-all duration-1000 ease-out" style="width: {{ min(100, max(0, (float)$onTimePercentage)) }}%;"></div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- G. SEKSI 5 — "DIBANGUN ATAS KEPERCAYAAN" V-PEEL OPENING (#performa, 1 Layar Penuh Anti-Kepotong) -->
    <!-- ===================================================================== -->
    <section id="performa" class="scroll-mt-0 relative overflow-hidden bg-[#F3EFE6] min-h-[100svh] lg:h-[100svh] lg:max-h-[900px] flex flex-col lg:flex-row items-center py-0">
        
        <!-- Bidang Hijau Solid #111C16 dengan Efek Belahan V-Peel Bézier Halus di Ekor Pesawat (Desktop lg:) -->
        <svg viewBox="0 0 1440 900" 
             preserveAspectRatio="none" 
             class="hidden lg:block absolute -top-1 -bottom-1 -left-1 w-[calc(100%+4px)] h-[calc(100%+8px)] pointer-events-none z-0" 
             aria-hidden="true">
            <defs>
                <!-- Filter Blur Halus Alami untuk Bayangan Lipatan (rgba(17,28,22,0.18)) -->
                <filter id="vPeelSmoothShadow" x="-10%" y="-10%" width="140%" height="130%">
                    <feGaussianBlur in="SourceAlpha" stdDeviation="22" />
                    <feFlood flood-color="#111C16" flood-opacity="0.18" result="color" />
                    <feComposite in2="SourceGraphic" in="color" operator="in" result="shadow" />
                    <feComposite in="SourceGraphic" in2="shadow" operator="over" />
                </filter>
            </defs>

            <!-- Lapisan Bayangan Lembut Alami di Atas Krem (#F3EFE6) digeser halus ke kanan -->
            <path d="M -10,-10 L 820,-10 C 745,165 685,392 580,450 C 685,508 745,735 820,910 L -10,910 Z" 
                  transform="translate(10, 0)"
                  fill="rgba(17,28,22,0.18)" 
                  filter="url(#vPeelSmoothShadow)" />

            <!-- Bidang Utama Hijau 100% Satu Warna Solid #111C16 dengan Kurva Bézier Halus Anggun -->
            <path d="M -10,-10 L 820,-10 C 745,165 685,392 580,450 C 685,508 745,735 820,910 L -10,910 Z" 
                  fill="#111C16" />

            <!-- Garis Tepi Stroke Super Tipis dan Halus di Sepanjang Kurva Bukaan -->
            <path d="M 820,-10 C 745,165 685,392 580,450 C 685,508 745,735 820,910" 
                  fill="none" 
                  stroke="rgba(255,255,255,0.07)" 
                  stroke-width="1.5" />
        </svg>

        <!-- Visual Pesawat Kargo Terkunci Presisi di Titik Belahan (Desktop lg: Absolute Lock 40.5%, 50%) -->
        <div class="hidden lg:block lg:absolute lg:top-1/2 lg:left-[40.5%] lg:-translate-x-full lg:-translate-y-1/2 lg:z-20 lg:w-[34vw] lg:max-w-[470px] pointer-events-none">
            <div id="performa-plane-anim-wrap" class="relative will-change-transform w-full">
                <div class="plane-ambient-float relative w-full flex justify-end">
                    <img 
                        id="performa-plane-img"
                        src="{{ asset('images/landing/performa-plane.png') }}" 
                        alt="Trackivo Air Cargo Cargo Aircraft" 
                        class="relative z-10 w-full h-auto object-contain select-none will-change-transform drop-shadow-[0_18px_28px_rgba(0,0,0,0.45)]"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>

        <!-- 2 Metrik Minimalis Terpisah di Pojok Kiri Bawah Area Hijau Solid #111C16 (Desktop lg:) -->
        <div class="hidden lg:flex lg:absolute lg:bottom-10 lg:left-10 xl:left-14 lg:z-20 w-auto items-center gap-8 pt-4 border-t border-white/15">
            <div>
                <span class="block font-light text-3xl sm:text-4xl text-[#F5F2EB] tracking-tight" style="font-family: 'Manrope', sans-serif; font-weight: 300;">98%</span>
                <span class="block text-xs text-[#F5F2EB]/70 mt-1 font-normal" style="font-family: 'Plus Jakarta Sans', sans-serif;">Akurasi Status Pengiriman</span>
            </div>
            <div class="w-px h-10 bg-white/15 shrink-0" aria-hidden="true"></div>
            <div>
                <span class="block font-light text-3xl sm:text-4xl text-[#F5F2EB] tracking-tight" style="font-family: 'Manrope', sans-serif; font-weight: 300;">24/7</span>
                <span class="block text-xs text-[#F5F2EB]/70 mt-1 font-normal" style="font-family: 'Plus Jakarta Sans', sans-serif;">Pemantauan Operasional Aktif</span>
            </div>
        </div>

        <!-- BLOK 1 MOBILE: Latar Hijau Solid #111C16 Berisi Pesawat & 2 Metrik (lg:contents agar transparan/menyatu di desktop) -->
        <div class="w-full bg-[#111C16] lg:bg-transparent lg:contents">
            <div class="w-full px-6 pt-24 pb-10 lg:p-0 flex lg:hidden flex-col justify-center items-center relative z-10 editorial-reveal mb-0">
                <!-- Wrapper Pesawat Kargo Mobile -->
                <div class="relative w-full flex justify-center">
                    <div class="relative will-change-transform w-full flex justify-center">
                        <div class="relative w-full flex justify-center">
                            <img 
                                src="{{ asset('images/landing/performa-plane.png') }}" 
                                alt="Trackivo Air Cargo Cargo Aircraft" 
                                class="relative z-10 w-[86%] sm:w-[78%] max-w-[420px] h-auto object-contain select-none drop-shadow-[0_18px_28px_rgba(0,0,0,0.45)]"
                                loading="lazy"
                            />
                        </div>
                    </div>
                </div>

                <!-- 2 Metrik Mobile -->
                <div class="mt-6 w-full max-w-[420px] flex items-center justify-between sm:justify-start sm:gap-10 pt-4 border-t border-white/15">
                    <div>
                        <span class="block font-light text-3xl sm:text-4xl text-[#F5F2EB] tracking-tight" style="font-family: 'Manrope', sans-serif; font-weight: 300;">98%</span>
                        <span class="block text-xs text-[#F5F2EB]/70 mt-1 font-normal" style="font-family: 'Plus Jakarta Sans', sans-serif;">Akurasi Status Pengiriman</span>
                    </div>
                    <div class="w-px h-10 bg-white/15 shrink-0" aria-hidden="true"></div>
                    <div>
                        <span class="block font-light text-3xl sm:text-4xl text-[#F5F2EB] tracking-tight" style="font-family: 'Manrope', sans-serif; font-weight: 300;">24/7</span>
                        <span class="block text-xs text-[#F5F2EB]/70 mt-1 font-normal" style="font-family: 'Plus Jakarta Sans', sans-serif;">Pemantauan Operasional Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Pemisah Bukaan V Full-Bleed Khusus Mobile (Menempel Rapi di Tepi Bawah Blok Hijau) -->
            <div class="block lg:hidden w-full overflow-hidden leading-none bg-[#111C16] -mb-px">
                <svg viewBox="0 0 100 12" preserveAspectRatio="none" class="block w-full h-9">
                    <path d="M0,0 L50,12 L100,0 L100,12 L0,12 Z" fill="#F3EFE6" />
                </svg>
            </div>
        </div>

        <!-- BLOK 2: Konten Teks Latar Krem Solid (#F3EFE6) Terpisah 100% di Bawah V-Pemisah di Mobile -->
        <div class="w-full bg-[#F3EFE6] px-6 pt-8 pb-16 lg:bg-transparent lg:p-0 relative z-10">
            <div class="w-full max-w-[1440px] mx-auto sm:px-10 lg:px-14 flex flex-col lg:flex-row items-center">
                <!-- Kolom Konten Kanan: Tetap Aman di Area Krem Sebelah Kanan Kurva (lg:ml-auto lg:w-[46%] xl:w-[45%] relative z-20) -->
                <div class="w-full lg:ml-auto lg:w-[46%] xl:w-[45%] relative z-20 p-0 editorial-reveal">
                    
                    <!-- Label Atas: KOMITMEN INTEGRITAS dengan Garis Aksen Tipis -->
                    <div class="flex items-center gap-3 lg:justify-end">
                        <span class="w-8 h-[1.5px] bg-[#4E6E58] lg:order-2"></span>
                        <span class="text-xs sm:text-sm font-semibold tracking-[0.14em] uppercase text-[#4E6E58] lg:order-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">KOMITMEN INTEGRITAS</span>
                    </div>

                    <!-- Judul Utama: Manrope font-light, leading-[1.32], tracking-[-0.01em] -->
                    <h2 class="mt-4 text-3xl sm:text-4xl lg:text-[2.45rem] xl:text-[2.6rem] text-[#16231B] leading-[1.32] tracking-[-0.01em] font-light text-left lg:text-right" style="font-family: 'Manrope', sans-serif; font-weight: 300;">
                        Dibangun atas kepercayaan.<br>
                        Digerakkan oleh <span class="italic font-serif font-normal text-[#4E6E58] tracking-normal" style="font-family: 'Instrument Serif', Georgia, serif; font-weight: 400; font-style: italic; letter-spacing: normal;">performa.</span>
                    </h2>

                    <!-- Subjudul / Deskripsi: mt-5, leading-[1.8], text-[0.94rem] text-[#435349] max-w-[490px] -->
                    <p class="mt-5 text-[0.94rem] leading-[1.8] text-[#435349] max-w-[490px] text-left lg:text-right lg:ml-auto" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Kami menjunjung transparansi mutlak di setiap etape distribusi logistik dengan pelaporan real-time yang dapat dipertanggungjawabkan.
                    </p>

                    <!-- 3 Poin Kompak, Rapi & 100% Muat 1 Layar Penuh: mt-7, gap-0 editorial minimalis -->
                    <div class="mt-7 flex flex-col">
                        
                        <!-- Poin 01: Default Aktif -->
                        <button type="button" class="performa-item group w-full text-left lg:text-right px-4 py-3 sm:px-5 sm:py-3.5 border-b border-[#16231B]/10 lg:border-r-2 border-[#4E6E58] bg-transparent transition-all duration-300 cursor-pointer" data-index="0">
                            <div class="flex items-start gap-4 lg:flex-row-reverse lg:text-right">
                                <span class="performa-num font-light text-base sm:text-lg text-[#4E6E58] shrink-0 transition-colors" style="font-family: 'Manrope', sans-serif; font-weight: 300;">01</span>
                                <div class="performa-body flex-1 transition-opacity">
                                    <h3 class="font-semibold text-[0.95rem] text-[#16231B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Visibilitas Real-Time</h3>
                                    <p class="mt-0.5 text-[0.84rem] leading-relaxed text-[#526358]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Jalur logistik bebas blind spot dengan pelacakan kontainer dan armada darat di setiap titik transit.
                                    </p>
                                </div>
                            </div>
                        </button>

                        <!-- Poin 02 -->
                        <button type="button" class="performa-item group w-full text-left lg:text-right px-4 py-3 sm:px-5 sm:py-3.5 border-b border-[#16231B]/10 lg:border-r-2 lg:border-r-transparent bg-transparent opacity-75 hover:opacity-100 transition-all duration-300 cursor-pointer" data-index="1">
                            <div class="flex items-start gap-4 lg:flex-row-reverse lg:text-right">
                                <span class="performa-num font-light text-base sm:text-lg text-[#4E6E58]/60 shrink-0 transition-colors" style="font-family: 'Manrope', sans-serif; font-weight: 300;">02</span>
                                <div class="performa-body flex-1 transition-opacity">
                                    <h3 class="font-semibold text-[0.95rem] text-[#16231B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Pengiriman Andal & Terukur</h3>
                                    <p class="mt-0.5 text-[0.84rem] leading-relaxed text-[#526358]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Keamanan muatan terstandardisasi dengan penjadwalan kapal dan pesawat kargo langsung dari terminal utama.
                                    </p>
                                </div>
                            </div>
                        </button>

                        <!-- Poin 03 -->
                        <button type="button" class="performa-item group w-full text-left lg:text-right px-4 py-3 sm:px-5 sm:py-3.5 border-b border-[#16231B]/10 lg:border-r-2 lg:border-r-transparent bg-transparent opacity-75 hover:opacity-100 transition-all duration-300 cursor-pointer" data-index="2">
                            <div class="flex items-start gap-4 lg:flex-row-reverse lg:text-right">
                                <span class="performa-num font-light text-base sm:text-lg text-[#4E6E58]/60 shrink-0 transition-colors" style="font-family: 'Manrope', sans-serif; font-weight: 300;">03</span>
                                <div class="performa-body flex-1 transition-opacity">
                                    <h3 class="font-semibold text-[0.95rem] text-[#16231B]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Manajemen Kontainer Fleksibel</h3>
                                    <p class="mt-0.5 text-[0.84rem] leading-relaxed text-[#526358]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Penataan depot kontainer 20ft dan 40ft High Cube yang terkoordinasi rapi untuk efisiensi bongkar muat.
                                    </p>
                                </div>
                            </div>
                        </button>

                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- H. SEKSI 6 — PELACAKAN PENGIRIMAN KARGO PUBLIK (#pelacakan, Warm Sand Cream #F3EFE6) -->
    <!-- ===================================================================== -->
    <section id="pelacakan" data-nav-light="true" class="relative bg-[#F3EFE6] min-h-[100svh] lg:h-[100svh] lg:max-h-[900px] flex items-center pt-24 pb-16 lg:py-0">
        <div class="w-full max-w-6xl mx-auto px-6 sm:px-8 lg:px-10">

            <!-- 1. KARTU BESAR HIJAU TUA TRACKIVO (#16231B) -->
            <div class="bg-[#16231B] rounded-[28px] px-6 py-10 sm:px-10 sm:py-12 lg:py-14 text-center shadow-[0_24px_60px_-20px_rgba(17,28,22,0.35)] editorial-reveal overflow-hidden">
                
                <!-- Pil Badge Atas -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/[0.06] border border-white/10 text-xs font-medium text-[#D8E2DC]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#6FA37F]"></span>
                    <span>Pelacakan Kargo Real-Time Tanpa Login</span>
                </div>

                <!-- Judul Utama -->
                <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.6rem] font-bold tracking-tight text-white">
                    Lacak Pengiriman Kargo Anda
                </h2>

                <!-- Subjudul -->
                <p class="mt-3 max-w-2xl mx-auto text-sm sm:text-[0.95rem] leading-relaxed text-[#C2CEC6]">
                    Pantau posisi geospasial aktual, riwayat checkpoint kargo, dan estimasi waktu ketibaan dengan akurat melalui nomor resi pengiriman.
                </p>

                <!-- Form Pencarian Resi -->
                <form action="{{ url('/track') }}" method="GET" class="mt-7 max-w-xl mx-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-2 p-2.5 sm:p-2 rounded-2xl bg-black/25 border border-white/10 focus-within:border-[#6FA37F]/60 transition">
                    <div class="flex items-center flex-1 min-w-0">
                        <svg class="w-5 h-5 text-[#8FA396] ml-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input 
                            type="text" 
                            name="tracking_number" 
                            id="tracking_number" 
                            placeholder="Masukkan nomor resi (contoh: TRK-2026-JKT-RTM-001)" 
                            required
                            autocomplete="off"
                            class="w-full min-w-0 bg-transparent border-0 text-sm text-[#F5F2EB] placeholder-[#8FA396] focus:ring-0 focus:outline-none px-2 py-2.5 font-mono">
                    </div>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto py-3 sm:py-2.5 px-6 rounded-xl bg-[#EFECE3] hover:bg-[#F7F5EE] text-[#16231B] font-semibold text-sm transition cursor-pointer shrink-0">
                        Lacak Resi
                    </button>
                </form>

                <!-- Baris Contoh Resi Aktif -->
                <div class="mt-4 text-xs text-[#9FB1A5]">
                    <span>Contoh resi aktif:</span>
                    <a href="{{ url('/track?tracking_number=' . urlencode($sampleTrackingNumber ?? 'TRK-2026-JKT-RTM-001')) }}" 
                       onclick="event.preventDefault(); var inp = document.getElementById('tracking_number'); if(inp){ inp.value='{{ $sampleTrackingNumber ?? 'TRK-2026-JKT-RTM-001' }}'; inp.focus(); } else { window.location.href=this.href; }"
                       class="underline underline-offset-4 text-[#E6ECE8] hover:text-white font-mono transition">
                        {{ $sampleTrackingNumber ?? 'TRK-2026-JKT-RTM-001' }}
                    </a>
                </div>

            </div>

            <!-- 2. 3 KARTU FITUR ALABASTER DI BAWAH KARTU HIJAU TUA -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6 editorial-reveal">
                
                <!-- Kartu 1: Pelacakan Geospasial Interaktif -->
                <div class="bg-[#FAF9F6] rounded-2xl p-6 border border-[#16231B]/[0.06] shadow-[0_8px_24px_-12px_rgba(22,35,27,0.08)] text-left">
                    <div class="w-10 h-10 rounded-xl bg-[#E7F0E9] text-[#4E6E58] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-[0.95rem] font-bold text-[#16231B]">Pelacakan Geospasial Interaktif</h3>
                    <p class="mt-2 text-xs sm:text-[0.82rem] leading-relaxed text-[#5C6B61]">
                        Pantau perjalanan kargo laut, udara, dan darat secara visual di atas peta interaktif dari titik awal pengiriman hingga pelabuhan/bandara tujuan.
                    </p>
                </div>

                <!-- Kartu 2: Estimasi Kedatangan (ETA) Cerdas -->
                <div class="bg-[#FAF9F6] rounded-2xl p-6 border border-[#16231B]/[0.06] shadow-[0_8px_24px_-12px_rgba(22,35,27,0.08)] text-left">
                    <div class="w-10 h-10 rounded-xl bg-[#F6EFE1] text-[#B88435] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-[0.95rem] font-bold text-[#16231B]">Estimasi Kedatangan (ETA) Cerdas</h3>
                    <p class="mt-2 text-xs sm:text-[0.82rem] leading-relaxed text-[#5C6B61]">
                        Ketahui status ketepatan waktu kargo secara transparan lengkap dengan deteksi awal potensi keterlambatan (delay) rute transit.
                    </p>
                </div>

                <!-- Kartu 3: Privasi & Akses Publik Terjamin -->
                <div class="bg-[#FAF9F6] rounded-2xl p-6 border border-[#16231B]/[0.06] shadow-[0_8px_24px_-12px_rgba(22,35,27,0.08)] text-left">
                    <div class="w-10 h-10 rounded-xl bg-[#E9F1EC] text-[#4E6E58] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-[0.95rem] font-bold text-[#16231B]">Privasi & Akses Publik Terjamin</h3>
                    <p class="mt-2 text-xs sm:text-[0.82rem] leading-relaxed text-[#5C6B61]">
                        Akses informasi status dan checkpoint publik tanpa login, dengan proteksi ketat data pribadi dan kerahasiaan pelanggan kargo.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- J. FOOTER EDITORIAL RAPI (#111C16) -->
    <!-- ===================================================================== -->
    <footer class="bg-[#111C16] text-[#F5F2EB] border-t border-white/[0.06] pt-16 pb-10 lg:pt-20 lg:pb-12" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-14">
            <!-- Grid Utama Atas (12 Kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12 pb-12 lg:pb-14 border-b border-white/10">
                
                <!-- Kolom Kiri: Identitas Brand (md:col-span-5 lg:col-span-5) -->
                <div class="md:col-span-5 lg:col-span-5">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <img src="{{ asset('images/landing/logo-white.png') }}" alt="Trackivo" class="h-6 w-auto object-contain" />
                        <span class="font-semibold text-xl tracking-tight text-[#F5F2EB]" style="font-family: 'Manrope', sans-serif;">Trackivo</span>
                    </a>
                    <p class="mt-4 max-w-sm text-sm leading-[1.75] text-[#F5F2EB]/65 font-normal">
                        Platform visibilitas rantai pasok dan logistik kargo enterprise dengan keakuratan data real-time antar pelabuhan niaga dunia.
                    </p>
                    <div class="mt-6 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-[#8FB09A] mr-1">Peran Terintegrasi:</span>
                        <span class="px-2.5 py-1 rounded-full bg-white/[0.05] border border-white/10 text-xs font-medium text-[#F5F2EB]/80">Administrator</span>
                        <span class="px-2.5 py-1 rounded-full bg-white/[0.05] border border-white/10 text-xs font-medium text-[#F5F2EB]/80">Operator</span>
                        <span class="px-2.5 py-1 rounded-full bg-white/[0.05] border border-white/10 text-xs font-medium text-[#F5F2EB]/80">Customer</span>
                    </div>
                </div>

                <!-- Kolom Tengah: Navigasi (md:col-span-3 lg:col-span-3 lg:col-start-7) -->
                <div class="md:col-span-3 lg:col-span-3 lg:col-start-7">
                    <h3 class="font-semibold text-xs uppercase tracking-[0.14em] text-[#8FB09A] mb-5">Navigasi</h3>
                    <ul class="space-y-3 text-sm text-[#F5F2EB]/75">
                        <li>
                            <a href="#tentang" class="hover:text-[#F5F2EB] transition-colors">Tentang Kami</a>
                        </li>
                        <li>
                            <a href="#layanan" class="hover:text-[#F5F2EB] transition-colors">Layanan Terintegrasi</a>
                        </li>
                        <li>
                            <a href="#solusi" class="hover:text-[#F5F2EB] transition-colors">Solusi Transportasi</a>
                        </li>
                        <li>
                            <a href="#performa" class="hover:text-[#F5F2EB] transition-colors">Komitmen Performa</a>
                        </li>
                        <li>
                            <a href="#pelacakan" class="hover:text-[#F5F2EB] transition-colors">Lacak Resi</a>
                        </li>
                    </ul>
                </div>

                <!-- Kolom Kanan: Portal & Akses Resmi (md:col-span-4 lg:col-span-3 lg:col-start-10) -->
                <div class="md:col-span-4 lg:col-span-3 lg:col-start-10">
                    <h3 class="font-semibold text-xs uppercase tracking-[0.14em] text-[#8FB09A] mb-5">Portal & Akses</h3>
                    <ul class="space-y-3 text-sm text-[#F5F2EB]/75">
                        <li>
                            <a href="{{ url('/track') }}" class="hover:text-[#F5F2EB] transition-colors">Pelacakan Resi Publik</a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ url('/dashboard') }}" class="hover:text-[#F5F2EB] transition-colors">Buka Dashboard</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ url('/login') }}" class="hover:text-[#F5F2EB] transition-colors">Masuk ke Ruang Kerja</a>
                            </li>
                        @endauth
                        @guest
                            <li>
                                <a href="{{ url('/register') }}" class="hover:text-[#F5F2EB] transition-colors">Registrasi Akun Baru</a>
                            </li>
                        @endguest
                    </ul>
                </div>

            </div>

            <!-- Baris Bawah: Copyright & Info Platform -->
            <div class="pt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs text-[#F5F2EB]/45">
                <p>&copy; 2026 Trackivo Logistics Intelligence. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-4">
                    <span>Versi Platform 2.6</span>
                    <span class="w-1 h-1 rounded-full bg-white/25" aria-hidden="true"></span>
                    <span>Waktu Server (WIB)</span>
                </div>
            </div>

        </div>
    </footer>

    <!-- ===================================================================== -->
    <!-- JAVASCRIPT: PRELOADER, ADAPTIVE NAVBAR, PARALLAX, TIMELINE, CAROUSEL -->
    <!-- ===================================================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // -----------------------------------------------------------------
            // 1. SMOOTH PRELOADER LOGO REVEAL
            // -----------------------------------------------------------------
            const preloader = document.getElementById('preloader-curtain');
            const preloaderLogo = document.getElementById('preloader-logo-wrap');

            // Cek prefers-reduced-motion
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (prefersReducedMotion || !preloader) {
                if (preloader) {
                    preloader.style.display = 'none';
                    preloader.style.pointerEvents = 'none';
                }
            } else {
                // Munculkan logo secara perlahan (~550ms)
                setTimeout(() => {
                    if (preloaderLogo) {
                        preloaderLogo.classList.add('is-visible');
                    }
                }, 60);

                // Setelah jeda singkat (~350ms setelah animasi logo), tirai terangkat mulus ke atas (durasi 650ms)
                setTimeout(() => {
                    preloader.style.transform = 'translateY(-100%)';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                        preloader.style.pointerEvents = 'none';
                    }, 650);
                }, 950);
            }

            // -----------------------------------------------------------------
            // 1.5. HERO SECTION — 3D CURSOR TILT & DEPTH PARALLAX
            // -----------------------------------------------------------------
            const heroSection = document.getElementById('hero');
            const heroTiltWrapper = document.getElementById('hero-tilt-wrapper');

            if (heroSection && heroTiltWrapper && !prefersReducedMotion) {
                let targetX = 0;
                let targetY = 0;
                let targetRotX = 0;
                let targetRotY = 0;

                let currentX = 0;
                let currentY = 0;
                let currentRotX = 0;
                let currentRotY = 0;

                const maxTranslate = 14; // ±14px
                const maxRotate = 1.2;   // ±1.2deg
                const damping = 0.08;    // Linear interpolation / damping factor

                heroSection.addEventListener('mousemove', (e) => {
                    const rect = heroSection.getBoundingClientRect();
                    // Normalisasi posisi kursor -1 hingga 1 dari titik tengah
                    const normX = ((e.clientX - rect.left) / Math.max(rect.width, 1) - 0.5) * 2;
                    const normY = ((e.clientY - rect.top) / Math.max(rect.height, 1) - 0.5) * 2;

                    const clampedX = Math.max(-1, Math.min(1, normX));
                    const clampedY = Math.max(-1, Math.min(1, normY));

                    targetX = clampedX * maxTranslate;
                    targetY = clampedY * maxTranslate;
                    targetRotX = -clampedY * maxRotate;
                    targetRotY = clampedX * maxRotate;
                });

                heroSection.addEventListener('mouseleave', () => {
                    targetX = 0;
                    targetY = 0;
                    targetRotX = 0;
                    targetRotY = 0;
                });

                function stepHeroTilt() {
                    currentX += (targetX - currentX) * damping;
                    currentY += (targetY - currentY) * damping;
                    currentRotX += (targetRotX - currentRotX) * damping;
                    currentRotY += (targetRotY - currentRotY) * damping;

                    heroTiltWrapper.style.transform = `translate3d(${currentX.toFixed(3)}px, ${currentY.toFixed(3)}px, 0) rotateX(${currentRotX.toFixed(3)}deg) rotateY(${currentRotY.toFixed(3)}deg)`;

                    requestAnimationFrame(stepHeroTilt);
                }

                requestAnimationFrame(stepHeroTilt);
            }

            // -----------------------------------------------------------------
            // 2. MORPHING STICKY ADAPTIVE NAVBAR (Collapse to Logo on Scroll)
            // -----------------------------------------------------------------
            const navbar = document.getElementById('main-nav');
            const navPillLinks = document.querySelectorAll('.nav-pill-link');
            const sections = document.querySelectorAll('section[id]');
            const lightSections = document.querySelectorAll('.bg-\\[\\#F3EFE6\\]:not(#performa), [data-nav-light="true"], #tentang, #solusi, #pelacakan');
            const mobileToggle = document.getElementById('mobile-menu-toggle');
            const mobileDrawer = document.getElementById('mobile-menu-drawer');

            let isNavHovered = false;

            function updateNavbarTheme() {
                if (!navbar) return;
                let isOverLightSection = false;
                lightSections.forEach(sec => {
                    if (sec.id === 'performa') return;
                    const rect = sec.getBoundingClientRect();
                    if (rect.top <= 60 && rect.bottom >= 60) {
                        isOverLightSection = true;
                    }
                });

                if (isOverLightSection) {
                    navbar.setAttribute('data-nav-theme', 'light');
                    navbar.classList.remove('bg-[#111C16]/35', 'border-white/20', 'text-[#F5F2EB]');
                    navbar.classList.add('bg-[#FAF8F4]/80', 'border-[#16231B]/15', 'text-[#16231B]');
                } else {
                    navbar.setAttribute('data-nav-theme', 'dark');
                    navbar.classList.remove('bg-[#FAF8F4]/80', 'border-[#16231B]/15', 'text-[#16231B]');
                    navbar.classList.add('bg-[#111C16]/35', 'border-white/20', 'text-[#F5F2EB]');
                }
            }

            function updateNavbarState() {
                if (!navbar) return;
                const scrollY = window.scrollY;

                // Morphing state: collapsed jika scroll > 40 dan tidak sedang di-hover
                if (scrollY > 40) {
                    if (!isNavHovered) {
                        navbar.setAttribute('data-nav-collapsed', 'true');
                        if (mobileDrawer) mobileDrawer.classList.add('hidden');
                    }
                } else {
                    navbar.setAttribute('data-nav-collapsed', 'false');
                }

                updateNavbarTheme();

                // Update active pill link
                let currentSectionId = '';
                sections.forEach(sec => {
                    const top = sec.offsetTop - 120;
                    const height = sec.offsetHeight;
                    if (scrollY >= top && scrollY < top + height) {
                        currentSectionId = sec.getAttribute('id');
                    }
                });

                navPillLinks.forEach(link => {
                    const target = link.getAttribute('data-target');
                    if (target && target === currentSectionId) {
                        link.classList.add('nav-pill-active');
                    } else if (target) {
                        link.classList.remove('nav-pill-active');
                    }
                });
            }

            if (navbar) {
                navbar.addEventListener('mouseenter', () => {
                    isNavHovered = true;
                    navbar.setAttribute('data-nav-collapsed', 'false');
                });

                navbar.addEventListener('mouseleave', () => {
                    isNavHovered = false;
                    if (window.scrollY > 40) {
                        navbar.setAttribute('data-nav-collapsed', 'true');
                        if (mobileDrawer) mobileDrawer.classList.add('hidden');
                    }
                });

                navbar.addEventListener('click', (e) => {
                    if (navbar.getAttribute('data-nav-collapsed') === 'true') {
                        isNavHovered = true;
                        navbar.setAttribute('data-nav-collapsed', 'false');
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!navbar.contains(e.target) && window.scrollY > 40) {
                        isNavHovered = false;
                        navbar.setAttribute('data-nav-collapsed', 'true');
                        if (mobileDrawer) mobileDrawer.classList.add('hidden');
                    }
                });
            }

            window.addEventListener('scroll', updateNavbarState, { passive: true });
            updateNavbarState();

            // Mobile menu drawer toggle
            if (mobileToggle && mobileDrawer) {
                mobileToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    mobileDrawer.classList.toggle('hidden');
                });
                document.querySelectorAll('.mobile-nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        mobileDrawer.classList.add('hidden');
                    });
                });
            }



            // -----------------------------------------------------------------
            // 4. SEKSI 3 — LAYANAN LOGISTIK ACCORDION & FOTO PARALLAX ZOOM
            // -----------------------------------------------------------------
            const layananItems = document.querySelectorAll('.layanan-accordion-item');
            const layananBgPhoto = document.getElementById('layanan-bg-photo');

            const photoConfigs = [
                { transform: 'scale(1)', objectPosition: '50% 50%' },
                { transform: 'scale(1.04)', objectPosition: '58% 46%' },
                { transform: 'scale(1.07)', objectPosition: '44% 54%' }
            ];

            function activateLayananItem(index) {
                layananItems.forEach((item, idx) => {
                    const content = item.querySelector('.layanan-accordion-content');
                    const num = item.querySelector('.layanan-num');
                    const title = item.querySelector('.layanan-title');
                    const accent = item.querySelector('.layanan-accent-line');

                    if (idx === index) {
                        if (content) content.classList.add('is-open');
                        if (num) {
                            num.classList.remove('text-[#F5F2EB]/55');
                            num.classList.add('text-[#D8CBB3]');
                        }
                        if (title) {
                            title.classList.remove('text-[#F5F2EB]/55');
                            title.classList.add('text-[#F5F2EB]');
                        }
                        if (accent) {
                            accent.classList.remove('opacity-0', 'scale-y-50');
                            accent.classList.add('opacity-100', 'scale-y-100');
                        }
                    } else {
                        if (content) content.classList.remove('is-open');
                        if (num) {
                            num.classList.remove('text-[#D8CBB3]');
                            num.classList.add('text-[#F5F2EB]/55');
                        }
                        if (title) {
                            title.classList.remove('text-[#F5F2EB]');
                            title.classList.add('text-[#F5F2EB]/55');
                        }
                        if (accent) {
                            accent.classList.remove('opacity-100', 'scale-y-100');
                            accent.classList.add('opacity-0', 'scale-y-50');
                        }
                    }
                });

                if (layananBgPhoto && photoConfigs[index]) {
                    layananBgPhoto.style.transform = photoConfigs[index].transform;
                    layananBgPhoto.style.objectPosition = photoConfigs[index].objectPosition;
                }
            }

            layananItems.forEach((item, idx) => {
                const trigger = item.querySelector('.layanan-header');
                if (trigger) {
                    trigger.addEventListener('click', () => {
                        activateLayananItem(idx);
                    });
                }
            });

            // -----------------------------------------------------------------
            // 5. SEKSI 4 — TRUK INTERMODAL SCROLL-DRIVEN & COUNT-UP METRIK
            // -----------------------------------------------------------------
            const truckUnit = document.getElementById('truck-unit');
            const solusiSection = document.getElementById('solusi');

            let currentTruckProgress = 0;
            let targetTruckProgress = 0;
            let isTruckLoopRunning = false;

            function calculateTruckTargetProgress() {
                if (!solusiSection) return 0;
                const rect = solusiSection.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                // Mulai melaju saat seksi mulai memasuki viewport dari bawah (0.98)
                const enterStart = windowHeight * 0.98;
                // Berhenti sempurna tepat saat seksi terpusat penuh di layar (0.04)
                const enterEnd = windowHeight * 0.04;

                const rawProgress = (enterStart - rect.top) / (enterStart - enterEnd);
                const clampedProgress = Math.max(0, Math.min(1, rawProgress));
                // Kurva perlambatan easeOutCubic
                return 1 - Math.pow(1 - clampedProgress, 3);
            }

            function runTruckLoop() {
                if (!truckUnit) return;
                if (prefersReducedMotion) {
                    truckUnit.style.transform = 'translateX(0%)';
                    truckUnit.style.opacity = '1';
                    return;
                }

                targetTruckProgress = calculateTruckTargetProgress();
                // Interpolasi lerp damping lebih pelan (0.045 per frame)
                currentTruckProgress += (targetTruckProgress - currentTruckProgress) * 0.045;

                if (Math.abs(targetTruckProgress - currentTruckProgress) < 0.0005) {
                    currentTruckProgress = targetTruckProgress;
                }

                // Truk melaju dari translateX(-95%) ke translateX(0%) dengan opacity 0.15 ke 1
                const currentTranslateX = -95 * (1 - currentTruckProgress);
                const currentOpacity = 0.15 + (0.85 * currentTruckProgress);

                truckUnit.style.transform = `translateX(${currentTranslateX.toFixed(2)}%)`;
                truckUnit.style.opacity = currentOpacity.toFixed(3);

                if (Math.abs(targetTruckProgress - currentTruckProgress) >= 0.0005) {
                    requestAnimationFrame(runTruckLoop);
                } else {
                    isTruckLoopRunning = false;
                }
            }

            function requestTruckUpdate() {
                if (prefersReducedMotion) return;
                if (!isTruckLoopRunning) {
                    isTruckLoopRunning = true;
                    requestAnimationFrame(runTruckLoop);
                }
            }

            if (truckUnit) {
                if (prefersReducedMotion) {
                    truckUnit.style.transform = 'translateX(0%)';
                    truckUnit.style.opacity = '1';
                } else {
                    requestTruckUpdate();
                }
            }

            window.addEventListener('scroll', requestTruckUpdate, { passive: true });
            window.addEventListener('resize', requestTruckUpdate, { passive: true });

            document.querySelectorAll('a[href="#solusi"]').forEach(link => {
                link.addEventListener('click', () => {
                    requestTruckUpdate();
                    const interval = setInterval(requestTruckUpdate, 50);
                    setTimeout(() => clearInterval(interval), 1200);
                });
            });

            // Count-up numbers observer
            const statNumbers = document.querySelectorAll('.stat-number');
            let statsCounted = false;

            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !statsCounted) {
                        statsCounted = true;
                        statNumbers.forEach(elem => {
                            const target = parseInt(elem.getAttribute('data-target'), 10) || 0;
                            const duration = 1200;
                            const start = 0;
                            const startTime = performance.now();

                            function step(now) {
                                const elapsed = now - startTime;
                                const progress = Math.min(elapsed / duration, 1);
                                // Ease-out quad
                                const current = Math.floor(start + (target - start) * (1 - Math.pow(1 - progress, 3)));
                                elem.textContent = current;
                                if (progress < 1) {
                                    requestAnimationFrame(step);
                                } else {
                                    elem.textContent = target;
                                }
                            }
                            requestAnimationFrame(step);
                        });
                    }
                });
            }, { threshold: 0.2 });

            if (solusiSection) statsObserver.observe(solusiSection);

            // -----------------------------------------------------------------
            // 6. SEKSI 5 — PERFORMA INTERACTIVE POINTS & PLANE ANIMATION
            // -----------------------------------------------------------------
            const performaItems = document.querySelectorAll('.performa-item');
            function activatePerformaItem(activeItem) {
                performaItems.forEach(item => {
                    const num = item.querySelector('.performa-num');
                    if (item === activeItem) {
                        item.classList.remove('opacity-75', 'lg:border-r-transparent');
                        item.classList.add('opacity-100', 'lg:border-r-[#4E6E58]');
                        if (num) {
                            num.classList.remove('text-[#4E6E58]/60');
                            num.classList.add('text-[#4E6E58]');
                        }
                    } else {
                        item.classList.remove('opacity-100', 'lg:border-r-[#4E6E58]');
                        item.classList.add('opacity-75', 'lg:border-r-transparent');
                        if (num) {
                            num.classList.remove('text-[#4E6E58]');
                            num.classList.add('text-[#4E6E58]/60');
                        }
                    }
                });
            }

            performaItems.forEach(item => {
                item.addEventListener('click', () => activatePerformaItem(item));
                item.addEventListener('mouseenter', () => activatePerformaItem(item));
            });

            // Animasi Scroll Glide Pesawat Kargo Trackivo (#performa)
            const planeUnit = document.getElementById('performa-plane-anim-wrap');
            const performaSection = document.getElementById('performa');

            let currentPlaneProgress = 0;
            let targetPlaneProgress = 0;
            let isPlaneLoopRunning = false;

            function calculatePlaneTargetProgress() {
                if (!performaSection) return 0;
                const rect = performaSection.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                // Mulai meluncur saat seksi memasuki viewport (0.95)
                const enterStart = windowHeight * 0.95;
                // Posisi optimal tercapai saat seksi berada di viewport atas (0.05)
                const enterEnd = windowHeight * 0.05;

                const rawProgress = (enterStart - rect.top) / (enterStart - enterEnd);
                const clampedProgress = Math.max(0, Math.min(1, rawProgress));
                return 1 - Math.pow(1 - clampedProgress, 3);
            }

            function runPlaneLoop() {
                if (!planeUnit) return;
                if (prefersReducedMotion) {
                    planeUnit.style.transform = 'translate3d(0, 0, 0) scale(1)';
                    planeUnit.style.opacity = '1';
                    return;
                }

                targetPlaneProgress = calculatePlaneTargetProgress();
                currentPlaneProgress += (targetPlaneProgress - currentPlaneProgress) * 0.045;

                if (Math.abs(targetPlaneProgress - currentPlaneProgress) < 0.0005) {
                    currentPlaneProgress = targetPlaneProgress;
                }

                // Gerak meluncur mikro yang lembut pada sumbu X (menjaga sumbu Y tetap terkunci presisi di titik belahan)
                const currentTranslateX = 8 * (1 - currentPlaneProgress);
                const currentScale = 0.985 + (0.015 * currentPlaneProgress);
                const currentOpacity = 0.75 + (0.25 * currentPlaneProgress);

                planeUnit.style.transform = `translate3d(${currentTranslateX.toFixed(2)}px, 0px, 0) scale(${currentScale.toFixed(3)})`;
                planeUnit.style.opacity = currentOpacity.toFixed(3);

                if (Math.abs(targetPlaneProgress - currentPlaneProgress) >= 0.0005) {
                    requestAnimationFrame(runPlaneLoop);
                } else {
                    isPlaneLoopRunning = false;
                }
            }

            function requestPlaneUpdate() {
                if (prefersReducedMotion) return;
                if (!isPlaneLoopRunning) {
                    isPlaneLoopRunning = true;
                    requestAnimationFrame(runPlaneLoop);
                }
            }

            if (planeUnit) {
                if (prefersReducedMotion) {
                    planeUnit.style.transform = 'translate3d(0, 0, 0) scale(1)';
                    planeUnit.style.opacity = '1';
                } else {
                    requestPlaneUpdate();
                }
            }

            window.addEventListener('scroll', requestPlaneUpdate, { passive: true });
            window.addEventListener('resize', requestPlaneUpdate, { passive: true });

            document.querySelectorAll('a[href="#performa"]').forEach(link => {
                link.addEventListener('click', () => {
                    requestPlaneUpdate();
                    const interval = setInterval(requestPlaneUpdate, 50);
                    setTimeout(() => clearInterval(interval), 1200);
                });
            });

            // -----------------------------------------------------------------
            // 7. SEKSI 6 — INTERACTIVE SHIPMENT TIMELINE BOARD
            // -----------------------------------------------------------------
            const timelineButtons = document.querySelectorAll('.timeline-shipment-select');
            const boardSelectedResi = document.getElementById('board-selected-resi');
            const boardSelectedMode = document.getElementById('board-selected-mode');
            const boardDelayBadge = document.getElementById('board-delay-badge');
            const boardCarrierBadge = document.getElementById('board-carrier-badge');
            const boardCargoDesc = document.getElementById('board-cargo-desc');
            const boardOriginLoc = document.getElementById('board-origin-loc');
            const boardOriginCode = document.getElementById('board-origin-code');
            const boardDestLoc = document.getElementById('board-dest-loc');
            const boardDestCode = document.getElementById('board-dest-code');
            const timelineEtaDay = document.getElementById('timeline-eta-day');
            const timelineEtaYear = document.getElementById('timeline-eta-year');
            const boardDirectLink = document.getElementById('board-direct-link');

            const stageDot1 = document.getElementById('stage-dot-1');
            const stageDot2 = document.getElementById('stage-dot-2');
            const stageDot3 = document.getElementById('stage-dot-3');
            const stageDot4 = document.getElementById('stage-dot-4');

            timelineButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    timelineButtons.forEach(b => {
                        b.className = 'timeline-shipment-select w-full text-left px-4 py-3 rounded-xl transition-all font-mono text-sm flex items-center justify-between bg-[#17251D] text-[#F5F2EB]/80 hover:bg-white/5';
                    });
                    btn.className = 'timeline-shipment-select w-full text-left px-4 py-3 rounded-xl transition-all font-mono text-sm flex items-center justify-between bg-[#4E6E58] text-[#FAF8F4]';

                    const resi = btn.getAttribute('data-resi');
                    const mode = btn.getAttribute('data-mode');
                    const carrier = btn.getAttribute('data-carrier');
                    const cargo = btn.getAttribute('data-cargo');
                    const origin = btn.getAttribute('data-origin');
                    const origincode = btn.getAttribute('data-origincode');
                    const dest = btn.getAttribute('data-dest');
                    const destcode = btn.getAttribute('data-destcode');
                    const status = btn.getAttribute('data-status');
                    const delay = parseInt(btn.getAttribute('data-delay'), 10) || 0;
                    const eta = btn.getAttribute('data-eta');
                    const etayear = btn.getAttribute('data-etayear');

                    if (boardSelectedResi) boardSelectedResi.textContent = resi;
                    if (boardSelectedMode) boardSelectedMode.textContent = mode + ' FREIGHT';
                    if (boardCarrierBadge) boardCarrierBadge.textContent = carrier;
                    if (boardCargoDesc) boardCargoDesc.textContent = 'Kargo: ' + cargo;
                    if (boardOriginLoc) boardOriginLoc.textContent = origin;
                    if (boardOriginCode) boardOriginCode.textContent = origincode;
                    if (boardDestLoc) boardDestLoc.textContent = dest;
                    if (boardDestCode) boardDestCode.textContent = destcode;
                    if (timelineEtaDay) timelineEtaDay.textContent = eta;
                    if (timelineEtaYear) timelineEtaYear.textContent = etayear;

                    if (boardDelayBadge) {
                        if (delay <= 0) {
                            boardDelayBadge.textContent = 'On-Time';
                            boardDelayBadge.className = 'px-3 py-1 rounded-full text-xs font-mono bg-[#4E6E58] text-[#FAF8F4]';
                        } else {
                            boardDelayBadge.textContent = '+' + delay + 'h Delay';
                            boardDelayBadge.className = 'px-3 py-1 rounded-full text-xs font-mono bg-[#B34040] text-[#FAF8F4]';
                        }
                    }

                    // Direct Link Update
                    if (boardDirectLink) {
                        boardDirectLink.href = '{{ route('tracking.public') }}?tracking_number=' + encodeURIComponent(resi);
                    }

                    // Progress Stage Dots
                    if (stageDot1 && stageDot2 && stageDot3 && stageDot4) {
                        stageDot1.className = 'w-3 h-3 rounded-full bg-[#4E6E58]';
                        stageDot2.className = 'w-3 h-3 rounded-full bg-white/20';
                        stageDot3.className = 'w-3 h-3 rounded-full bg-white/20';
                        stageDot4.className = 'w-3 h-3 rounded-full bg-white/20';

                        if (status === 'PICKED_UP') {
                            stageDot2.className = 'w-3 h-3 rounded-full bg-[#4E6E58]';
                        } else if (status === 'IN_TRANSIT' || status === 'OUT_FOR_DELIVERY') {
                            stageDot2.className = 'w-3 h-3 rounded-full bg-[#4E6E58]';
                            stageDot3.className = 'w-3 h-3 rounded-full bg-[#4E6E58]';
                        } else if (status === 'DELIVERED') {
                            stageDot2.className = 'w-3 h-3 rounded-full bg-[#4E6E58]';
                            stageDot3.className = 'w-3 h-3 rounded-full bg-[#4E6E58]';
                            stageDot4.className = 'w-3 h-3 rounded-full bg-[#4E6E58]';
                        }
                    }
                });
            });



            // -----------------------------------------------------------------
            // 9. EDITORIAL SCROLL REVEAL OBSERVER
            // -----------------------------------------------------------------
            const revealElements = document.querySelectorAll('.editorial-reveal');
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-revealed');
                    }
                });
            }, { threshold: 0.1 });

            revealElements.forEach(el => revealObserver.observe(el));

        });
    </script>
</body>
</html>
