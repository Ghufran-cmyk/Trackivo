<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') - Trackivo</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        canvas: '#E9EAE4',
                        charcoal: '#1C1E1B',
                        muted: '#6F756C',
                        sage: {
                            50: '#F4F7F4',
                            100: '#E6F0E9',
                            200: '#C8DACD',
                            500: '#556E5C',
                            600: '#425848',
                            700: '#35473A',
                            800: '#2A382E',
                        },
                        sand: {
                            50: '#FAF9F5',
                            100: '#F5F6F1',
                            200: '#E9EAE4',
                            300: '#DFE1D8',
                            400: '#C9CCC0',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E9EAE4;
            color: #1C1E1B;
            top: 0 !important;
            position: static !important;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(111, 117, 108, 0.25);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background-color: transparent;
        }
        /* Sembunyikan Google Translate Toolbar & Banner Bawaan */
        .goog-te-banner-frame.skiptranslate,
        iframe.skiptranslate,
        .goog-te-gadget,
        #goog-gt-tt,
        .goog-te-balloon-frame,
        #goog-gt-vt,
        .goog-tooltip {
            display: none !important;
        }
        .goog-text-highlight {
            background: transparent !important;
            box-shadow: none !important;
        }
        #google_translate_element {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#E9EAE4] text-[#1C1E1B] antialiased min-h-screen selection:bg-[#556E5C] selection:text-white">

    @auth
        @php
            $role = auth()->user()->role;
        @endphp

        <!-- Desktop Top Left Logo (Sejajar horizontal dengan sidebar left-5 w-[68px] dan sejajar vertikal dengan Hello, Trackivo!) -->
        <div class="hidden md:flex fixed left-5 top-6 w-[68px] items-center justify-center z-40">
            <a href="{{ route('dashboard') }}" class="h-14 w-14 flex items-center justify-center hover:scale-105 transition-transform shrink-0" title="Trackivo Dashboard">
                <img src="{{ asset('images/logo.png') }}" alt="Trackivo" class="h-14 w-14 object-contain">
            </a>
        </div>

        <!-- Fixed Rail Wrapper for Left Sidebar (Desktop: Diam Terpaku di Sisi Kiri Layar) -->
        <aside class="hidden md:flex fixed left-5 top-[104px] bottom-5 w-[68px] items-center justify-center z-40 pointer-events-none">
            <nav class="pointer-events-auto overflow-visible w-[68px] h-full max-h-[660px] min-h-[500px] bg-white rounded-full py-5 px-2 shadow-[0_8px_28px_rgba(28,30,27,0.07)] border border-[#E2E4DC]/80 flex flex-col items-center justify-between">
                
                <!-- Menu Bagian Atas -->
                <div class="flex flex-col items-center gap-2.5 w-full">
                    
                    <!-- Dashboard (Common) -->
                    <div class="relative group flex items-center justify-center">
                        <a href="{{ route('dashboard') }}" 
                           class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('dashboard') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                            <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                            </svg>
                        </a>
                        <!-- Tooltip -->
                        <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                            Dashboard
                        </div>
                    </div>

                    <!-- Shipments / My Shipments -->
                    <div class="relative group flex items-center justify-center">
                        <a href="{{ route('shipments.index') }}" 
                           class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('shipments.*') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                            <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </a>
                        <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                            {{ $role === 'customer' ? 'My Shipments' : 'Shipments' }}
                        </div>
                    </div>

                    @if ($role === 'admin' || $role === 'operator')
                        <!-- Exceptions -->
                        <div class="relative group flex items-center justify-center">
                            <a href="{{ route('exceptions.index') }}" 
                               class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('exceptions.*') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                                <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </a>
                            <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                                Exceptions
                            </div>
                        </div>
                    @endif

                    @if ($role === 'admin')
                        <!-- Carriers -->
                        <div class="relative group flex items-center justify-center">
                            <a href="{{ route('carriers.index') }}" 
                               class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('carriers.*') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                                <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 17h4V5H2v12h3"></path>
                                    <path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h1"></path>
                                    <circle cx="7.5" cy="17.5" r="2.5"></circle>
                                    <circle cx="17.5" cy="17.5" r="2.5"></circle>
                                </svg>
                            </a>
                            <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                                Carriers
                            </div>
                        </div>

                        <!-- Locations -->
                        <div class="relative group flex items-center justify-center">
                            <a href="{{ route('locations.index') }}" 
                               class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('locations.*') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                                <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </a>
                            <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                                Locations
                            </div>
                        </div>

                        <!-- Users -->
                        <div class="relative group flex items-center justify-center">
                            <a href="{{ route('users.index') }}" 
                               class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('users.*') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                                <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </a>
                            <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                                Users
                            </div>
                        </div>

                        <!-- Audit Logs -->
                        <div class="relative group flex items-center justify-center">
                            <a href="{{ route('audit-logs.index') }}" 
                               class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('audit-logs.*') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                                <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </a>
                            <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                                Audit Logs
                            </div>
                        </div>
                    @endif

                    @if ($role === 'customer')
                        <!-- Notifications Menu for Customer in Sidebar -->
                        <div class="relative group flex items-center justify-center">
                            <a href="{{ route('notifications.index') }}" 
                               class="w-10 h-10 rounded-full flex items-center justify-center transition-all relative {{ request()->routeIs('notifications.*') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                                <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                                </svg>
                                @if(($unreadNotificationsCount ?? 0) > 0)
                                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                                @endif
                            </a>
                            <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                                Notifications ({{ $unreadNotificationsCount ?? 0 }})
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Menu Bagian Bawah: Settings, Logout, Avatar -->
                <div class="mt-auto pt-4 border-t border-[#ECEEE6] flex flex-col items-center gap-2.5 w-full">
                    
                    @if ($role === 'admin')
                        <!-- Settings -->
                        <div class="relative group flex items-center justify-center">
                            <a href="{{ route('settings.index') }}" 
                               class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ request()->routeIs('settings.*') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#7C8279] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
                                <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
                            <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                                Settings
                            </div>
                        </div>
                    @endif

                    <!-- Logout -->
                    <div class="relative group flex items-center justify-center">
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit" 
                                    class="w-10 h-10 rounded-full text-[#7C8279] hover:text-[#B84242] hover:bg-[#F9E6E6] flex items-center justify-center transition-all"
                                    title="Logout">
                                <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                            </button>
                        </form>
                        <div class="absolute left-full ml-3 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                            Logout
                        </div>
                    </div>

                    <!-- User Avatar -->
                    <div class="relative group flex items-center justify-center">
                        <a href="{{ route('profile.edit') }}" 
                           class="w-10 h-10 rounded-full bg-[#556E5C] text-white text-sm font-semibold flex items-center justify-center overflow-hidden transition-all hover:ring-2 hover:ring-[#556E5C] {{ request()->routeIs('profile.*') ? 'ring-2 ring-[#556E5C] shadow-sm' : 'ring-2 ring-[#E9EAE4]' }}"
                           title="{{ auth()->user()->name }} - Pengaturan Akun">
                            @if (auth()->user()->avatarUrl())
                                <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </a>
                        <div class="absolute left-full ml-3 px-3 py-2 bg-[#1C1E1B] text-white text-xs rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                            <div class="font-bold">{{ auth()->user()->name }}</div>
                            <div class="text-[11px] text-[#A2A9A0]">Pengaturan Akun &bull; Portal {{ ucfirst($role) }}</div>
                        </div>
                    </div>

                </div>

            </nav>
        </aside>

        <!-- Main Content Container (Identik batas lebar dan horizontal padding antara Header dan Konten) -->
        <div class="min-h-screen flex flex-col w-full md:pl-[112px] md:pr-8 px-4 pt-6 pb-8">
            
            <!-- Mobile Header Top Bar (< md) -->
            <div class="md:hidden flex items-center justify-between bg-white rounded-2xl p-3.5 shadow-sm border border-[#E2E4DC]/80 mb-4">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Trackivo" class="h-8 w-8 object-contain">
                    <span class="font-bold text-base tracking-tight text-[#1C1E1B] notranslate">Trackivo</span>
                </a>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('notifications.index') }}" 
                       class="w-9 h-9 rounded-full bg-[#F5F6F1] text-[#1C1E1B] flex items-center justify-center relative">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                        </svg>
                        @if(($unreadNotificationsCount ?? 0) > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500"></span>
                        @endif
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="w-9 h-9 rounded-full bg-[#F5F6F1] text-[#7C8279] hover:text-[#B84242] flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Desktop Top Header Bar (Rata Kanan Sejajar Konten) -->
            <header class="w-full flex flex-wrap lg:flex-nowrap items-center justify-between gap-6 mb-6">
                
                <!-- Sisi Kiri: Blok Judul Hello, Trackivo! dan Subjudul Role Bahasa Indonesia -->
                <div class="min-w-0">
                    <h1 class="text-2xl md:text-3xl font-bold text-[#1C1E1B] tracking-tight">
                        Hello, <span class="notranslate">Trackivo</span>!
                    </h1>
                    <p class="text-xs sm:text-sm text-[#6F756C] mt-0.5">
                        @if ($role === 'admin')
                            Pantau analitik, informasi, dan aktivitas operasional pengiriman global
                        @elseif ($role === 'operator')
                            Kelola rute kargo aktif, perbarui pelacakan, dan tangani kendala pengiriman
                        @else
                            Lacak pengiriman aktif Anda dan pantau progres pengiriman secara langsung
                        @endif
                    </p>
                </div>

                <!-- Sisi Kanan: Kotak Pencarian Kapsul + Tombol Translate + Tombol Notifikasi + Pill Role -->
                <div class="flex items-center gap-3 shrink-0 ml-auto">
                    
                    <!-- Search Box Kapsul -->
                    <form method="GET" action="{{ route('shipments.index') }}" class="bg-white rounded-full pl-5 pr-1.5 py-1.5 shadow-sm border border-[#E2E4DC]/80 flex items-center">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search tracking number..." 
                               class="bg-transparent text-xs sm:text-sm text-[#1C1E1B] placeholder-[#9EABA2] focus:outline-none w-64 xl:w-72">
                        <button type="submit" 
                                class="w-8 h-8 rounded-full bg-[#1C1E1B] text-white flex items-center justify-center hover:bg-black transition-colors shrink-0" 
                                title="Search">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </form>

                    <!-- Multi-Country Translate Dropdown Button -->
                    <div class="relative" id="translate-dropdown-container">
                        <button type="button" 
                                id="translate-dropdown-btn" 
                                class="w-11 h-11 rounded-full bg-white shadow-sm border border-[#E2E4DC]/80 flex items-center justify-center text-[#1C1E1B] hover:bg-[#F5F6F1] transition-all relative"
                                title="Translate">
                            <svg class="w-5 h-5 text-[#1C1E1B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                            <!-- Indikator Kode Bahasa Kecil -->
                            <span id="current-lang-code" class="absolute -bottom-1 -right-1 px-1 text-[9px] font-bold rounded-full bg-[#1C1E1B] text-white uppercase notranslate leading-tight">
                                ID
                            </span>
                        </button>

                        <!-- Floating Dropdown Menu (Natural Sage & Sand) -->
                        <div id="translate-dropdown-menu" 
                             class="hidden absolute right-0 top-full mt-2 rounded-2xl bg-white shadow-lg border border-[#E2E4DC] p-2 w-48 z-50 notranslate">
                            <div class="px-3 py-1.5 text-[11px] font-semibold text-[#6F756C] uppercase tracking-wider border-b border-[#E2E4DC]/60 mb-1">
                                Pilih Bahasa
                            </div>
                            <div class="space-y-0.5 max-h-72 overflow-y-auto custom-scrollbar">
                                <button type="button" data-lang="id" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">ID</span><span>Indonesia</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="en" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">EN</span><span>English</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="zh-CN" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">ZH</span><span>中文</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="ja" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">JA</span><span>日本語</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="ko" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">KO</span><span>한국어</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="de" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">DE</span><span>Deutsch</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="fr" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">FR</span><span>Français</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="es" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">ES</span><span>Español</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="nl" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">NL</span><span>Nederlands</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                                <button type="button" data-lang="ar" class="lang-select-btn w-full text-left px-3 py-1.5 rounded-xl text-xs font-medium flex items-center justify-between hover:bg-[#F5F6F1] text-[#1C1E1B] transition-colors">
                                    <span class="flex items-center space-x-2"><span class="w-6 text-[10px] font-bold text-[#556E5C]">AR</span><span>العربية</span></span>
                                    <span class="lang-check text-[#556E5C] text-xs font-bold hidden">&check;</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Round Button -->
                    <div class="relative group">
                        <a href="{{ route('notifications.index') }}" 
                           class="w-11 h-11 rounded-full bg-white shadow-sm border border-[#E2E4DC]/80 flex items-center justify-center text-[#1C1E1B] hover:bg-[#F1F2EC] transition-colors relative"
                           title="Notifications">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                            </svg>
                            @if(($unreadNotificationsCount ?? 0) > 0)
                                <span class="absolute top-2.5 right-2.5 w-2.5 h-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                            @endif
                        </a>
                        <div class="absolute right-0 top-full mt-2 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                            Notifications ({{ $unreadNotificationsCount ?? 0 }})
                        </div>
                    </div>

                    <!-- Active Role Pill -->
                    <div class="relative group">
                        <div class="h-11 px-4 rounded-full bg-white shadow-sm border border-[#E2E4DC]/80 flex items-center space-x-2 text-xs font-bold text-[#1C1E1B] cursor-default">
                            <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                            <span class="uppercase tracking-wider">PORTAL {{ strtoupper($role) }}</span>
                        </div>
                        <div class="absolute right-0 top-full mt-2 px-3 py-1.5 bg-[#1C1E1B] text-white text-xs font-medium rounded-xl shadow-lg whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 z-50">
                            {{ auth()->user()->company_name ?? auth()->user()->email }}
                        </div>
                    </div>

                </div>

            </header>

                <!-- Flash Notifications Alert -->
                @if (session('success'))
                    <div class="mb-5 bg-[#E6F0E9] border border-[#C8DACD] text-[#2F6846] px-5 py-3.5 rounded-[20px] flex items-center justify-between shadow-xs">
                        <div class="flex items-center space-x-3">
                            <svg class="h-5 w-5 text-[#2F6846] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-[#2F6846] hover:opacity-75 text-lg font-bold ml-3">&times;</button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-5 bg-[#EDF1EC] border border-[#DFE1D8] text-[#35473A] px-5 py-3.5 rounded-[20px] flex items-center justify-between shadow-xs">
                        <div class="flex items-center space-x-3">
                            <svg class="h-5 w-5 text-[#556E5C] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs sm:text-sm font-semibold">{{ session('info') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-[#35473A] hover:opacity-75 text-lg font-bold ml-3">&times;</button>
                    </div>
                @endif

                @if (session('error') || (isset($errors) && $errors->any()))
                    <div class="mb-5 bg-[#F9E6E6] border border-[#F0C9C9] text-[#B84242] px-5 py-3.5 rounded-[20px] flex items-center justify-between shadow-xs">
                        <div class="flex items-center space-x-3">
                            <svg class="h-5 w-5 text-[#B84242] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-xs sm:text-sm font-semibold">
                                {{ session('error') ?? 'Terjadi kesalahan pada input data Anda.' }}
                            </div>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-[#B84242] hover:opacity-75 text-lg font-bold ml-3">&times;</button>
                    </div>
                @endif

                <!-- Main Page Yield -->
                <main class="flex-1 pb-16 md:pb-10">
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="pt-6 pb-2 text-xs text-[#6F756C] flex flex-col sm:flex-row items-center justify-between border-t border-[#DFE1D8]/60 gap-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-[#1C1E1B] notranslate">Trackivo</span>
                        <span>&bull;</span>
                        <span>Global Shipment Visibility & Tracking Platform</span>
                    </div>
                    <div>
                        <span>Standard Time: Asia/Jakarta (WIB) &bull; &copy; {{ date('Y') }} <span class="notranslate">Trackivo</span> System.</span>
                    </div>
                </footer>

        </div>

        <!-- Mobile Bottom Floating Navigation (< md) -->
        <nav class="md:hidden fixed bottom-3 left-3 right-3 bg-white/95 backdrop-blur-md rounded-full shadow-[0_8px_30px_rgba(28,30,27,0.12)] border border-[#E2E4DC] py-2 px-4 flex items-center justify-around z-50">
            <a href="{{ route('dashboard') }}" class="p-2 rounded-full {{ request()->routeIs('dashboard') ? 'bg-[#1C1E1B] text-white' : 'text-[#7C8279]' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                </svg>
            </a>

            <a href="{{ route('shipments.index') }}" class="p-2 rounded-full {{ request()->routeIs('shipments.*') ? 'bg-[#1C1E1B] text-white' : 'text-[#7C8279]' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                </svg>
            </a>

            @if ($role === 'admin' || $role === 'operator')
                <a href="{{ route('exceptions.index') }}" class="p-2 rounded-full {{ request()->routeIs('exceptions.*') ? 'bg-[#1C1E1B] text-white' : 'text-[#7C8279]' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </a>
            @endif

            @if ($role === 'admin')
                <a href="{{ route('settings.index') }}" class="p-2 rounded-full {{ request()->routeIs('settings.*') ? 'bg-[#1C1E1B] text-white' : 'text-[#7C8279]' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                </a>
            @endif

            <a href="{{ route('notifications.index') }}" class="p-2 rounded-full relative {{ request()->routeIs('notifications.*') ? 'bg-[#1C1E1B] text-white' : 'text-[#7C8279]' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                </svg>
                @if(($unreadNotificationsCount ?? 0) > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500"></span>
                @endif
            </a>
        </nav>

    @else
        <div class="min-h-screen flex flex-col justify-center items-center p-4">
            @yield('content')
        </div>
    @endauth

    <!-- Client-Side Google Translate Hidden Container -->
    <div id="google_translate_element" class="hidden"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            try {
                new google.translate.TranslateElement({
                    pageLanguage: 'id',
                    includedLanguages: 'id,en,zh-CN,ja,ko,de,fr,es,nl,ar',
                    autoDisplay: false
                }, 'google_translate_element');
            } catch (err) {
                // Silently fallback if blocked or offline
            }
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer onerror="/* safe fallback */"></script>

    <!-- Multi-Country Translate Controller -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownBtn = document.getElementById('translate-dropdown-btn');
            const dropdownMenu = document.getElementById('translate-dropdown-menu');
            const currentLangCode = document.getElementById('current-lang-code');
            const langButtons = document.querySelectorAll('.lang-select-btn');

            function getCookie(name) {
                const match = document.cookie.match(new RegExp('(^|;\\s*)' + name + '=([^;]+)'));
                return match ? decodeURIComponent(match[2]) : null;
            }

            function getActiveLang() {
                const cookieVal = getCookie('googtrans');
                if (cookieVal) {
                    const parts = cookieVal.split('/');
                    if (parts.length >= 3 && parts[2]) {
                        return parts[2];
                    }
                }
                return 'id';
            }

            function syncDropdownUI(activeLang) {
                if (!activeLang) activeLang = 'id';
                if (currentLangCode) {
                    currentLangCode.textContent = activeLang.split('-')[0].toUpperCase();
                }
                langButtons.forEach(function (btn) {
                    const lang = btn.getAttribute('data-lang');
                    const check = btn.querySelector('.lang-check');
                    if (lang === activeLang || (activeLang === 'id' && lang === 'id')) {
                        btn.classList.add('bg-[#F5F6F1]', 'text-[#556E5C]', 'font-bold');
                        if (check) check.classList.remove('hidden');
                    } else {
                        btn.classList.remove('bg-[#F5F6F1]', 'text-[#556E5C]', 'font-bold');
                        if (check) check.classList.add('hidden');
                    }
                });
            }

            function setLanguage(lang) {
                const domain = window.location.hostname;
                if (!lang || lang === 'id') {
                    document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + domain + ";";
                    document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=." + domain + ";";
                } else {
                    const val = "/auto/" + lang;
                    document.cookie = "googtrans=" + val + "; path=/;";
                    document.cookie = "googtrans=" + val + "; path=/; domain=" + domain + ";";
                    document.cookie = "googtrans=" + val + "; path=/; domain=." + domain + ";";
                }

                const combo = document.querySelector('.goog-te-combo');
                if (combo) {
                    combo.value = lang;
                    combo.dispatchEvent(new Event('change'));
                }
                window.location.reload();
            }

            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }

            langButtons.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const lang = this.getAttribute('data-lang');
                    setLanguage(lang);
                });
            });

            syncDropdownUI(getActiveLang());
        });
    </script>

    <!-- GSAP & ScrollTrigger Ringan CDN -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

    <!-- GSAP + ScrollTrigger Inisialisasi Ringan -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof gsap === 'undefined') return;

            const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReducedMotion) return;

            if (typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
            }

            // 1. Top Header: Fade-in Halus Cepat
            const topHeader = document.querySelector('header');
            if (topHeader) {
                gsap.from(topHeader, {
                    y: 10,
                    opacity: 0,
                    duration: 0.45,
                    ease: 'power2.out',
                    clearProps: 'transform,opacity'
                });
            }

            // Kapsul Sidebar: Fade-in Opacity Saja (Tanpa y/transform) agar Fixed Terkunci Sempurna
            const sidebarCapsule = document.querySelector('aside nav');
            if (sidebarCapsule) {
                gsap.from(sidebarCapsule, {
                    opacity: 0,
                    duration: 0.4,
                    ease: 'power2.out',
                    clearProps: 'all'
                });
            }

            // 2. Dashboard Animasi Khusus vs Halaman Lainnya
            const kpiGrid = document.getElementById('dashboard-kpi-grid');
            if (kpiGrid) {
                // 4 Kartu KPI: Staggered Fade-Up
                const kpiCards = kpiGrid.querySelectorAll('.kpi-card');
                if (kpiCards.length > 0) {
                    gsap.from(kpiCards, {
                        y: 20,
                        opacity: 0,
                        stagger: 0.08,
                        duration: 0.5,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                }

                // Count-up Animasi Halus (0.7s) pada Angka Utama KPI
                const countUpEls = document.querySelectorAll('.kpi-count-up');
                countUpEls.forEach(function (el) {
                    const targetVal = parseFloat(el.getAttribute('data-target')) || 0;
                    if (targetVal === 0) return;
                    const counterObj = { val: 0 };
                    gsap.to(counterObj, {
                        val: targetVal,
                        duration: 0.7,
                        ease: 'power2.out',
                        onUpdate: function () {
                            el.textContent = Math.round(counterObj.val).toLocaleString('id-ID');
                        },
                        onComplete: function () {
                            el.textContent = Math.round(targetVal).toLocaleString('id-ID');
                        }
                    });
                });

                // Kartu Grafik Chart.js Reveal dengan ScrollTrigger
                const chartCards = document.querySelectorAll('.chart-reveal-card');
                chartCards.forEach(function (card) {
                    gsap.from(card, {
                        scrollTrigger: {
                            trigger: card,
                            start: 'top 90%',
                            once: true
                        },
                        y: 24,
                        opacity: 0,
                        duration: 0.55,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                });

                // Kartu Peta Sebaran Pengiriman (Leaflet) Reveal dengan ScrollTrigger
                const mapCard = document.getElementById('dashboard-map-card');
                if (mapCard) {
                    gsap.from(mapCard, {
                        scrollTrigger: {
                            trigger: mapCard,
                            start: 'top 90%',
                            once: true
                        },
                        y: 24,
                        opacity: 0,
                        duration: 0.55,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity',
                        onComplete: function () {
                            window.dispatchEvent(new Event('resize'));
                        }
                    });
                }
            } else {
                // Halaman Lainnya (Shipments, Exceptions, Carriers, Locations, Users, Audit Logs, Settings)
                const mainContentCards = document.querySelectorAll('main > div, main .main-content-card');
                if (mainContentCards.length > 0) {
                    gsap.from(mainContentCards, {
                        y: 12,
                        opacity: 0,
                        duration: 0.35,
                        ease: 'power2.out',
                        clearProps: 'transform,opacity'
                    });
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
