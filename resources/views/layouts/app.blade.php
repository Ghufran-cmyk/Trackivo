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
                        trackivo: {
                            50: '#f6f8fb',
                            100: '#edf1f7',
                            200: '#dbe2ee',
                            800: '#151922',
                            900: '#0d1017',
                            dark: '#0f1219',
                            card: '#151921',
                            accent: '#d4f93a',
                            accentHover: '#c4e72f',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.4);
            border-radius: 9999px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#f0f3f8] text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Dark Header / Navbar (Latar belakang gelap kontras agar logo putih terlihat tajam) -->
    <header class="bg-[#0e1117] border-b border-slate-800 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left: Logo & Brand Name -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="p-1 rounded-xl bg-slate-900/80 border border-slate-700/60 shadow-inner flex items-center justify-center">
                            <img src="{{ asset('images/logo.png') }}" alt="Trackivo" class="h-9 w-auto object-contain transition duration-200 group-hover:scale-105">
                        </div>
                    </a>
                </div>

                <!-- Center: Role-Based Navigation Menu -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-1.5 flex-nowrap shrink-0">
                    @auth
                        @php
                            $role = auth()->user()->role;
                        @endphp

                        <!-- Common: Dashboard -->
                        <a href="{{ route('dashboard') }}" 
                           class="px-2.5 py-1.5 lg:px-3 lg:py-2 rounded-xl text-xs lg:text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('dashboard') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
                            Dashboard
                        </a>

                        @if ($role === 'admin')
                            <!-- Admin Menu: Dashboard, Shipments, Exceptions, Carriers, Locations, Users, Audit Logs, Settings -->
                            <a href="{{ route('shipments.index') }}" class="px-2.5 py-1.5 lg:px-3 lg:py-2 rounded-xl text-xs lg:text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('shipments.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">Shipments</a>
                            <a href="{{ route('exceptions.index') }}" class="px-2.5 py-1.5 lg:px-3 lg:py-2 rounded-xl text-xs lg:text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('exceptions.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">Exceptions</a>
                            <a href="{{ route('carriers.index') }}" class="px-2.5 py-1.5 lg:px-3 lg:py-2 rounded-xl text-xs lg:text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('carriers.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">Carriers</a>
                            <a href="{{ route('locations.index') }}" class="px-2.5 py-1.5 lg:px-3 lg:py-2 rounded-xl text-xs lg:text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('locations.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">Locations</a>
                            <a href="{{ route('users.index') }}" class="px-2.5 py-1.5 lg:px-3 lg:py-2 rounded-xl text-xs lg:text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('users.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">Users</a>
                            <a href="#" class="px-2.5 py-1.5 lg:px-3 lg:py-2 rounded-xl text-xs lg:text-sm font-medium whitespace-nowrap text-slate-300 hover:text-white hover:bg-slate-800/80 transition">Audit Logs</a>
                            <a href="{{ route('settings.index') }}" class="px-2.5 py-1.5 lg:px-3 lg:py-2 rounded-xl text-xs lg:text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('settings.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">Settings</a>
                        @elseif ($role === 'operator')
                            <!-- Operator Menu: Dashboard, Shipments, Exceptions -->
                            <a href="{{ route('shipments.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('shipments.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">Shipments</a>
                            <a href="{{ route('exceptions.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('exceptions.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">Exceptions</a>
                        @elseif ($role === 'customer')
                            <!-- Customer Menu: Dashboard, My Shipments, Notifications -->
                            <a href="{{ route('shipments.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition {{ request()->routeIs('shipments.*') ? 'bg-[#d4f93a] text-black font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">My Shipments</a>
                            <a href="#" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap text-slate-300 hover:text-white hover:bg-slate-800/80 transition">Notifications</a>
                        @endif
                    @endauth
                </nav>

                <!-- Right: User Info & Actions -->
                <div class="flex items-center space-x-3">
                    @auth
                        <!-- User Capsule Profile -->
                        <div class="flex items-center bg-slate-900/90 border border-slate-700/80 rounded-full pl-3 pr-1.5 py-1 text-sm">
                            <div class="flex flex-col text-right mr-3 hidden sm:block">
                                <span class="text-xs font-semibold text-white leading-tight">{{ auth()->user()->name }}</span>
                                <div class="flex items-center justify-end space-x-1.5">
                                    <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded-full 
                                        {{ auth()->user()->role === 'admin' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 
                                          (auth()->user()->role === 'operator' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 
                                          'bg-[#d4f93a]/20 text-[#d4f93a] border border-[#d4f93a]/30') }}">
                                        {{ auth()->user()->role }}
                                    </span>
                                    @if(auth()->user()->company_name)
                                        <span class="text-[10px] text-slate-400 font-medium truncate max-w-[100px]">{{ auth()->user()->company_name }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Avatar circle -->
                            <div class="h-8 w-8 rounded-full bg-slate-800 text-slate-200 font-bold flex items-center justify-center text-xs border border-slate-600">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}" class="ml-2">
                                @csrf
                                <button type="submit" 
                                        title="Keluar dari akun"
                                        class="h-8 w-8 rounded-full bg-slate-800/90 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 flex items-center justify-center transition border border-slate-700 hover:border-rose-500/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white px-3 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-[#d4f93a] hover:bg-[#c4e72f] text-black px-4 py-2 rounded-xl transition">Daftar</a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Mobile Sub-Menu (for mobile viewports) -->
    @auth
        <div class="md:hidden bg-[#131722] border-b border-slate-800 px-4 py-2.5 overflow-x-auto flex space-x-2 text-xs">
            <a href="{{ route('dashboard') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ Route::currentRouteName() == 'dashboard' ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Dashboard</a>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('shipments.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('shipments.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Shipments</a>
                <a href="{{ route('exceptions.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('exceptions.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Exceptions</a>
                <a href="{{ route('carriers.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('carriers.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Carriers</a>
                <a href="{{ route('locations.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('locations.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Locations</a>
                <a href="{{ route('users.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('users.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Users</a>
                <a href="#" class="px-2.5 py-1 rounded-lg whitespace-nowrap text-slate-300">Audit Logs</a>
                <a href="{{ route('settings.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('settings.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Settings</a>
            @elseif (auth()->user()->role === 'operator')
                <a href="{{ route('shipments.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('shipments.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Shipments</a>
                <a href="{{ route('exceptions.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('exceptions.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">Exceptions</a>
            @elseif (auth()->user()->role === 'customer')
                <a href="{{ route('shipments.index') }}" class="px-2.5 py-1 rounded-lg whitespace-nowrap {{ request()->routeIs('shipments.*') ? 'bg-[#d4f93a] text-black font-semibold' : 'text-slate-300' }}">My Shipments</a>
                <a href="#" class="px-2.5 py-1 rounded-lg whitespace-nowrap text-slate-300">Notifications</a>
            @endif
        </div>
    @endauth

    <!-- Flash Alert Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
        @if (session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-lg">&times;</button>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-4 bg-sky-50 border border-sky-200 text-sky-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-sky-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-700 text-lg">&times;</button>
            </div>
        @endif

        @if (session('error') || (isset($errors) && $errors->any()))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm font-medium">
                        {{ session('error') ?? 'Terjadi kesalahan pada input data Anda.' }}
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-lg">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 pb-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-3">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-slate-800">Trackivo</span>
                <span>&bull;</span>
                <span>Global Shipment Visibility & Tracking Platform</span>
            </div>
            <div>
                <span>Standard Time: Asia/Jakarta (WIB) &bull; &copy; {{ date('Y') }} Trackivo System.</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
