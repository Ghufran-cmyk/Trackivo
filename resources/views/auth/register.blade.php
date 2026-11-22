<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Customer - Trackivo (Global Shipment Visibility & Tracking Platform)</title>

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

    <div class="w-full max-w-6xl rounded-3xl bg-[#0e121a] border border-white/10 shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[680px]">
        
        <!-- Left Showcase Side -->
        <div class="lg:col-span-5 relative bg-gradient-to-br from-[#131824] via-[#0c1017] to-[#07090e] p-8 sm:p-12 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-white/10 overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Brand Logo Area -->
            <div class="relative z-10">
                <div class="inline-flex items-center bg-black/40 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 shadow-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="Trackivo" class="h-9 w-auto object-contain">
                </div>
            </div>

            <!-- Central Message -->
            <div class="relative z-10 my-10">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#d4f93a]/10 border border-[#d4f93a]/20 text-[#d4f93a] text-xs font-semibold uppercase tracking-wider mb-4">
                    <span>Portal Pelanggan Global</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight leading-tight">
                    Bergabung dengan Jaringan Logistik Global Trackivo.
                </h1>
                <p class="mt-4 text-sm text-slate-400 font-normal leading-relaxed">
                    Dapatkan visibilitas penuh terhadap seluruh kargo Anda. Pantau status pengiriman kargo laut, udara, dan darat dengan deteksi keterlambatan otomatis berbasis aturan (rule-based).
                </p>

                <!-- Value Proposition Highlights -->
                <div class="mt-8 space-y-3 text-xs text-slate-300">
                    <div class="flex items-center space-x-2.5">
                        <div class="h-5 w-5 rounded-full bg-[#d4f93a]/20 text-[#d4f93a] flex items-center justify-center font-bold">✓</div>
                        <span>Pemantauan status & riwayat nomor resi kargo global</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <div class="h-5 w-5 rounded-full bg-[#d4f93a]/20 text-[#d4f93a] flex items-center justify-center font-bold">✓</div>
                        <span>Notifikasi otomatis keterlambatan & inspeksi bea cukai</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <div class="h-5 w-5 rounded-full bg-[#d4f93a]/20 text-[#d4f93a] flex items-center justify-center font-bold">✓</div>
                        <span>Akses laporan manifest kargo dan riwayat checkpoint</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Footnote -->
            <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-slate-500">
                <span>Registrasi Akun Customer</span>
                <span>Trackivo Security</span>
            </div>
        </div>

        <!-- Right Form Side -->
        <div class="lg:col-span-7 bg-[#0a0d14] p-8 sm:p-12 flex flex-col justify-center">
            <div class="max-w-lg w-full mx-auto">
                
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-white tracking-tight">Buat Akun Customer</h2>
                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#d4f93a]/15 text-[#d4f93a] border border-[#d4f93a]/30">
                            Role: Customer
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Lengkapi data di bawah ini untuk memulai pemantauan pengiriman Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 bg-rose-950/60 border border-rose-500/30 text-rose-300 text-xs px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Register Form -->
                <form action="{{ route('register.attempt') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               placeholder="Contoh: Budi Santoso" 
                               required 
                               autocomplete="name"
                               class="w-full bg-[#131722] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#d4f93a] focus:ring-1 focus:ring-[#d4f93a] transition duration-200">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                                Alamat Email <span class="text-rose-400">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com" 
                                   required 
                                   autocomplete="email"
                                   class="w-full bg-[#131722] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#d4f93a] focus:ring-1 focus:ring-[#d4f93a] transition duration-200">
                        </div>
                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                                Nomor Telepon / HP
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="+62 812-3456-7890" 
                                   autocomplete="tel"
                                   class="w-full bg-[#131722] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#d4f93a] focus:ring-1 focus:ring-[#d4f93a] transition duration-200">
                        </div>
                    </div>

                    <div>
                        <label for="company_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Nama Perusahaan / Organisasi
                        </label>
                        <input type="text" 
                               name="company_name" 
                               id="company_name" 
                               value="{{ old('company_name') }}"
                               placeholder="Contoh: PT Global Forwarding Indonesia" 
                               autocomplete="organization"
                               class="w-full bg-[#131722] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#d4f93a] focus:ring-1 focus:ring-[#d4f93a] transition duration-200">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                                Kata Sandi <span class="text-rose-400">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   placeholder="Minimal 8 karakter" 
                                   required 
                                   autocomplete="new-password"
                                   class="w-full bg-[#131722] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#d4f93a] focus:ring-1 focus:ring-[#d4f93a] transition duration-200">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-rose-400">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   placeholder="Ulangi kata sandi" 
                                   required 
                                   autocomplete="new-password"
                                   class="w-full bg-[#131722] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#d4f93a] focus:ring-1 focus:ring-[#d4f93a] transition duration-200">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-[#d4f93a] hover:bg-[#c2e72f] text-black font-semibold text-sm py-3.5 px-4 rounded-xl shadow-lg shadow-[#d4f93a]/15 transition duration-200 transform active:scale-[0.99] flex items-center justify-center space-x-2">
                            <span>Daftar Akun Customer Sekarang</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center text-xs text-slate-400">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-semibold text-[#d4f93a] hover:underline ml-1">
                        Masuk ke Portal
                    </a>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
