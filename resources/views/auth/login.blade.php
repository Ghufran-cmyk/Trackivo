<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Akun - Trackivo (Global Shipment Visibility & Tracking Platform)</title>

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
                            dark: '#0a0d13',
                            surface: '#11151e',
                            input: '#161b26',
                            border: 'rgba(255, 255, 255, 0.08)',
                            accent: '#d4f93a',
                            accentHover: '#c1e530',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #080a0f;
        }
    </style>
</head>
<body class="min-h-screen text-slate-100 flex items-center justify-center p-3 sm:p-6 lg:p-10">

    <div class="w-full max-w-6xl rounded-3xl bg-[#0e121a] border border-white/10 shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
        
        <!-- Left Showcase Side (Inspirasi desain arsitektural & visibilitas kargo) -->
        <div class="lg:col-span-6 relative bg-gradient-to-br from-[#131824] via-[#0c1017] to-[#07090e] p-8 sm:p-12 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-white/10 overflow-hidden">
            <!-- Subtle geometric grid pattern & glowing orbs -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#d4f93a]/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Brand Logo Area (Latar belakang gelap kontras untuk logo putih) -->
            <div class="relative z-10">
                <div class="inline-flex items-center space-x-3 bg-black/40 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 shadow-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="Trackivo Logo" class="h-8 w-auto object-contain">
                    <span class="text-lg font-bold tracking-tight text-white">Trackivo</span>
                </div>
            </div>

            <!-- Central Hero Message -->
            <div class="relative z-10 my-12">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#d4f93a]/10 border border-[#d4f93a]/20 text-[#d4f93a] text-xs font-semibold uppercase tracking-wider mb-4">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#d4f93a] animate-pulse"></span>
                    <span>Global Shipment Visibility</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Real-time Tracking Across Oceans, Skies & Terrains.
                </h1>
                <p class="mt-4 text-sm sm:text-base text-slate-400 font-normal leading-relaxed">
                    Sistem pemantauan pengiriman global end-to-end terintegrasi dengan deteksi delay prediktif dan manajemen anomali logistik lintas benua.
                </p>
            </div>

            <!-- Bottom Footnote -->
            <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-slate-500">
                <span>Trackivo Intelligence Hub</span>
                <span>Asia/Jakarta (WIB) Active</span>
            </div>
        </div>

        <!-- Right Form Side (Autentikasi Bersih & Minimalis) -->
        <div class="lg:col-span-6 bg-[#0a0d14] p-8 sm:p-12 flex flex-col justify-center">
            <div class="max-w-md w-full mx-auto">
                
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Selamat Datang Kembali</h2>
                    <p class="mt-1 text-sm text-slate-400">Masukkan kredensial Anda untuk mengakses portal Trackivo.</p>
                </div>

                <!-- Session / Flash Status -->
                @if (session('info'))
                    <div class="mb-5 bg-sky-950/60 border border-sky-500/30 text-sky-300 text-xs px-4 py-3 rounded-xl flex items-center space-x-2">
                        <svg class="h-4 w-4 shrink-0 text-sky-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 bg-rose-950/60 border border-rose-500/30 text-rose-300 text-xs px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Alamat Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               placeholder="nama@perusahaan.com" 
                               required 
                               autocomplete="email"
                               class="w-full bg-[#131722] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#d4f93a] focus:ring-1 focus:ring-[#d4f93a] transition duration-200">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                Kata Sandi
                            </label>
                        </div>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               placeholder="••••••••••••" 
                               required 
                               autocomplete="current-password"
                               class="w-full bg-[#131722] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#d4f93a] focus:ring-1 focus:ring-[#d4f93a] transition duration-200">
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center space-x-2 text-slate-400 hover:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded bg-slate-800 border-white/20 text-[#d4f93a] focus:ring-0 focus:ring-offset-0">
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-[#d4f93a] hover:bg-[#c2e72f] text-black font-semibold text-sm py-3.5 px-4 rounded-xl shadow-lg shadow-[#d4f93a]/15 transition duration-200 transform active:scale-[0.99] flex items-center justify-center space-x-2">
                        <span>Masuk ke Akun</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Demo Account Fast Switcher (Memudahkan Pengujian 3 Role) -->
                <div class="mt-8 pt-6 border-t border-white/10">
                    <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2.5">
                        Kredensial Demo Cepat (Password: password)
                    </span>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button type="button" 
                                onclick="fillDemo('admin@trackivo.com')"
                                class="bg-slate-900 hover:bg-slate-800 border border-white/10 hover:border-purple-500/40 rounded-lg p-2 text-left transition flex items-center justify-between group">
                            <div>
                                <div class="font-bold text-white group-hover:text-purple-300">Admin</div>
                                <div class="text-[10px] text-slate-400">admin@trackivo.com</div>
                            </div>
                        </button>
                        <button type="button" 
                                onclick="fillDemo('operator@trackivo.com')"
                                class="bg-slate-900 hover:bg-slate-800 border border-white/10 hover:border-amber-500/40 rounded-lg p-2 text-left transition flex items-center justify-between group">
                            <div>
                                <div class="font-bold text-white group-hover:text-amber-300">Operator</div>
                                <div class="text-[10px] text-slate-400">operator@trackivo.com</div>
                            </div>
                        </button>
                        <button type="button" 
                                onclick="fillDemo('customer1@trackivo.com')"
                                class="bg-slate-900 hover:bg-slate-800 border border-white/10 hover:border-emerald-500/40 rounded-lg p-2 text-left transition flex items-center justify-between group">
                            <div>
                                <div class="font-bold text-white group-hover:text-emerald-300">Customer 1</div>
                                <div class="text-[10px] text-slate-400">PT Nusantara Logistik</div>
                            </div>
                        </button>
                        <button type="button" 
                                onclick="fillDemo('customer2@trackivo.com')"
                                class="bg-slate-900 hover:bg-slate-800 border border-white/10 hover:border-emerald-500/40 rounded-lg p-2 text-left transition flex items-center justify-between group">
                            <div>
                                <div class="font-bold text-white group-hover:text-emerald-300">Customer 2</div>
                                <div class="text-[10px] text-slate-400">Pacific Trade Co.</div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Link to Register -->
                <div class="mt-6 text-center text-xs text-slate-400">
                    Belum memiliki akun Trackivo? 
                    <a href="{{ route('register') }}" class="font-semibold text-[#d4f93a] hover:underline ml-1">
                        Daftar Akun Customer
                    </a>
                </div>

            </div>
        </div>

    </div>

    <script>
        function fillDemo(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
