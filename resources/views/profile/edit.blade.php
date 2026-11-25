@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
@php
    $initialTab = ($errors->updatePassword->any() || session('password_status')) ? 'password' : 'profile';
@endphp

<div class="w-full">

    <!-- Header Halaman Rata Kiri Penuh (Sejajar Topbar & Footer) -->
    <div class="w-full mb-5">
        <h1 class="text-2xl font-bold text-[#1C2820] tracking-tight">Pengaturan Akun</h1>
        <p class="text-sm text-[#556E5C] mt-1">
            Kelola informasi profil, foto, keamanan kata sandi, dan detail akses akun Anda.
        </p>
    </div>

    <!-- SATU Kartu Terpadu 2 Panel Menyamping (Full-Width Split Settings Card) -->
    <div class="w-full bg-white rounded-[28px] border border-[#DFE1D8]/90 shadow-[0_8px_30px_rgba(36,51,41,0.05)] overflow-hidden grid grid-cols-1 lg:grid-cols-12 lg:min-h-[420px]">
        
        <!-- Panel Kiri: Ringkasan Profil & Navigasi Tab Vertikal -->
        <div class="lg:col-span-4 bg-[#243329] text-white p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <!-- Bagian Atas — Ringkasan Profil -->
                <div class="flex items-center gap-4">
                    <!-- Avatar Bulat -->
                    <div class="w-16 h-16 rounded-full overflow-hidden ring-2 ring-[#C8D3BE]/35 bg-[#556E5C] shrink-0 flex items-center justify-center">
                        @if ($user->avatarUrl())
                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>

                    <!-- Identitas Akun & Role Badge -->
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-semibold text-white leading-snug truncate">{{ $user->name }}</h2>
                        <p class="text-sm text-[#C8D3BE]/85 font-sans truncate mt-0.5">{{ $user->email }}</p>
                        <div class="mt-2">
                            @if ($user->isAdmin())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#C8D3BE] text-[#1C2820]">Administrator</span>
                            @elseif ($user->isOperator())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#DDD4C4] text-[#2B261E]">Logistics Operator</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/15 text-[#E8EEE4] border border-white/20">Customer</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Garis Pemisah Halus -->
                <div class="border-t border-white/10 my-4"></div>

                <!-- Menu Navigasi Tab Vertikal (3 Item Interaktif) -->
                <nav class="space-y-2">
                    <!-- Item 1: profile -->
                    <button type="button" 
                            onclick="switchTab('profile')" 
                            id="tab-btn-profile" 
                            class="w-full text-left p-3.5 rounded-2xl flex items-center gap-3.5 transition cursor-pointer {{ $initialTab === 'profile' ? 'bg-[#C8D3BE] text-[#1C2820] shadow-sm' : 'text-white/85 hover:bg-white/10' }}">
                        <div id="tab-icon-wrap-profile" class="w-10 h-10 rounded-xl {{ $initialTab === 'profile' ? 'bg-[#1C2820]/10 text-[#1C2820]' : 'bg-white/10 text-white' }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 id="tab-title-profile" class="text-sm font-semibold {{ $initialTab === 'profile' ? 'text-[#1C2820]' : 'text-white' }}">Informasi Profil & Foto</h3>
                            <p id="tab-sub-profile" class="text-xs {{ $initialTab === 'profile' ? 'text-[#1C2820]/75' : 'text-[#C8D3BE]/70' }} mt-0.5">Foto profil, nama, dan email</p>
                        </div>
                    </button>

                    <!-- Item 2: password -->
                    <button type="button" 
                            onclick="switchTab('password')" 
                            id="tab-btn-password" 
                            class="w-full text-left p-3.5 rounded-2xl flex items-center gap-3.5 transition cursor-pointer {{ $initialTab === 'password' ? 'bg-[#C8D3BE] text-[#1C2820] shadow-sm' : 'text-white/85 hover:bg-white/10' }}">
                        <div id="tab-icon-wrap-password" class="w-10 h-10 rounded-xl {{ $initialTab === 'password' ? 'bg-[#1C2820]/10 text-[#1C2820]' : 'bg-white/10 text-white' }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 id="tab-title-password" class="text-sm font-semibold {{ $initialTab === 'password' ? 'text-[#1C2820]' : 'text-white' }}">Keamanan & Kata Sandi</h3>
                            <p id="tab-sub-password" class="text-xs {{ $initialTab === 'password' ? 'text-[#1C2820]/75' : 'text-[#C8D3BE]/70' }} mt-0.5">Ubah kata sandi akun Anda</p>
                        </div>
                    </button>

                    <!-- Item 3: details -->
                    <button type="button" 
                            onclick="switchTab('details')" 
                            id="tab-btn-details" 
                            class="w-full text-left p-3.5 rounded-2xl flex items-center gap-3.5 transition cursor-pointer {{ $initialTab === 'details' ? 'bg-[#C8D3BE] text-[#1C2820] shadow-sm' : 'text-white/85 hover:bg-white/10' }}">
                        <div id="tab-icon-wrap-details" class="w-10 h-10 rounded-xl {{ $initialTab === 'details' ? 'bg-[#1C2820]/10 text-[#1C2820]' : 'bg-white/10 text-white' }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18"></path>
                                <path d="M5 21V7l8-4v18"></path>
                                <path d="M19 21V11l-6-3"></path>
                                <path d="M9 9v.01"></path>
                                <path d="M9 12v.01"></path>
                                <path d="M9 15v.01"></path>
                                <path d="M9 18v.01"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 id="tab-title-details" class="text-sm font-semibold {{ $initialTab === 'details' ? 'text-[#1C2820]' : 'text-white' }}">Detail Akun & Hak Akses</h3>
                            <p id="tab-sub-details" class="text-xs {{ $initialTab === 'details' ? 'text-[#1C2820]/75' : 'text-[#C8D3BE]/70' }} mt-0.5">Info perusahaan & otoritas peran</p>
                        </div>
                    </button>
                </nav>
            </div>

            <!-- Bagian Bawah Panel Kiri -->
            <div class="pt-4 border-t border-white/10 mt-4">
                <p class="text-xs text-[#C8D3BE]/65">
                    Bergabung sejak {{ $user->created_at?->translatedFormat('d M Y') ?? $user->created_at?->format('d M Y') }}
                </p>
            </div>
        </div>

        <!-- Panel Kanan: Konten Pengaturan Sesuai Tab Aktif -->
        <div class="lg:col-span-8 bg-white p-6 sm:p-7 flex flex-col">
            
            <!-- Notifikasi Flash Alert -->
            @if (session('profile_status'))
                <div class="bg-[#EFF4EC] border border-[#C8D3BE] text-[#243329] rounded-2xl px-4 py-3 mb-6 text-sm flex items-center justify-between font-medium">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#3E5245] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('profile_status') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-[#243329] hover:opacity-75 text-base font-bold ml-2 leading-none">&times;</button>
                </div>
            @endif

            @if (session('password_status'))
                <div class="bg-[#EFF4EC] border border-[#C8D3BE] text-[#243329] rounded-2xl px-4 py-3 mb-6 text-sm flex items-center justify-between font-medium">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#3E5245] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('password_status') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-[#243329] hover:opacity-75 text-base font-bold ml-2 leading-none">&times;</button>
                </div>
            @endif

            <!-- PANEL 1: profile (Informasi Profil & Foto) -->
            <div id="panel-profile" class="{{ $initialTab === 'profile' ? '' : 'hidden' }} flex-1 flex flex-col">
                <div class="border-b border-[#ECEEE8] pb-4 mb-5">
                    <h2 class="text-lg font-bold text-[#1C2820]">Informasi Profil & Foto</h2>
                    <p class="text-xs text-[#556E5C] mt-1">Kelola foto profil, nama lengkap, dan alamat email akun Anda.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex-1 flex flex-col justify-between">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        <!-- Baris Foto Profil -->
                        <div class="bg-[#F6F7F3] border border-[#DFE1D8]/80 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                            <div class="flex items-center gap-4">
                                <!-- Preview Avatar -->
                                <div class="relative w-16 h-16 rounded-full overflow-hidden bg-[#556E5C] text-white flex items-center justify-center font-bold text-xl ring-2 ring-[#DFE1D8] shadow-xs shrink-0" id="avatar-preview">
                                    <img id="avatar-preview-img" 
                                         src="{{ $user->avatarUrl() ?? '' }}" 
                                         alt="Preview" 
                                         class="w-full h-full object-cover {{ $user->avatarUrl() ? '' : 'hidden' }}">
                                    <span id="avatar-preview-fallback" class="{{ $user->avatarUrl() ? 'hidden' : '' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold text-[#1C2820]">Foto Profil</h3>
                                    <p class="text-xs text-[#556E5C] mt-0.5">JPG, PNG, atau WEBP maksimal 2 MB</p>
                                    <p id="avatar-file-name" class="text-xs text-[#556E5C] mt-1 truncate max-w-[220px]">Belum ada file dipilih</p>
                                </div>
                            </div>

                            <!-- Tombol Aksi Avatar -->
                            <div class="flex items-center gap-2 shrink-0">
                                <input type="file" 
                                       name="avatar" 
                                       id="avatar-input" 
                                       accept="image/png,image/jpeg,image/webp" 
                                       class="hidden">
                                <label for="avatar-input" 
                                       class="bg-white border border-[#DFE1D8] hover:bg-[#EFF4EC] text-[#243329] rounded-xl px-4 py-2 text-sm font-medium cursor-pointer inline-flex items-center gap-2 transition shadow-xs">
                                    <svg class="w-4 h-4 text-[#556E5C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                    <span>Pilih Foto Baru</span>
                                </label>
                                @if ($user->avatar)
                                    <button type="button" 
                                            onclick="if(confirm('Apakah Anda yakin ingin menghapus foto profil ini?')) document.getElementById('delete-avatar-form').submit();" 
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium text-[#8C3A36] border border-[#DFE1D8] hover:bg-[#F8F5F5] transition">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                        <span>Hapus Foto</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        @error('avatar')
                            <p class="text-xs text-[#A23B38] font-medium -mt-4 mb-4">{{ $message }}</p>
                        @enderror

                        <!-- Grid Input 2 Kolom -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-xs font-bold text-[#243329] uppercase tracking-wider mb-2">
                                    Nama Lengkap <span class="text-[#A23B38]">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required 
                                       class="w-full rounded-xl border border-[#DFE1D8] bg-[#FAFBF9] px-4 py-2.5 text-sm text-[#1C2820] focus:border-[#556E5C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 transition @error('name') border-[#D98E8A] @enderror">
                                @error('name')
                                    <p class="text-xs text-[#A23B38] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-[#243329] uppercase tracking-wider mb-2">
                                    Alamat Email <span class="text-[#A23B38]">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required 
                                       class="w-full rounded-xl border border-[#DFE1D8] bg-[#FAFBF9] px-4 py-2.5 text-sm text-[#1C2820] focus:border-[#556E5C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 transition @error('email') border-[#D98E8A] @enderror">
                                @error('email')
                                    <p class="text-xs text-[#A23B38] mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Footer Aksi -->
                    <div class="border-t border-[#ECEEE8] mt-6 pt-4 flex justify-end">
                        <button type="submit" 
                                class="bg-[#243329] hover:bg-[#1C2820] text-white rounded-xl px-6 py-2.5 text-sm font-medium transition shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                <!-- Form Hapus Avatar Terpisah (Di luar tag form update) -->
                @if ($user->avatar)
                    <form id="delete-avatar-form" method="POST" action="{{ route('profile.avatar.destroy') }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>

            <!-- PANEL 2: password (Keamanan & Kata Sandi) -->
            <div id="panel-password" class="{{ $initialTab === 'password' ? '' : 'hidden' }} flex-1 flex flex-col">
                <div class="border-b border-[#ECEEE8] pb-4 mb-5">
                    <h2 class="text-lg font-bold text-[#1C2820]">Keamanan & Kata Sandi</h2>
                    <p class="text-xs text-[#556E5C] mt-1">Perbarui kata sandi akun Anda secara berkala untuk menjaga keamanan akses.</p>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}" class="flex-1 flex flex-col justify-between">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5 max-w-xl">
                        <!-- Kata Sandi Saat Ini -->
                        <div>
                            <label for="current_password" class="block text-xs font-bold text-[#243329] uppercase tracking-wider mb-2">
                                Kata Sandi Saat Ini <span class="text-[#A23B38]">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="current_password" 
                                       id="current_password" 
                                       required 
                                       class="w-full rounded-xl border border-[#DFE1D8] bg-[#FAFBF9] pl-4 pr-11 py-2.5 text-sm text-[#1C2820] focus:border-[#556E5C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 transition {{ $errors->updatePassword->has('current_password') ? 'border-[#D98E8A]' : '' }}">
                                <button type="button" 
                                        data-target="current_password" 
                                        class="toggle-password-btn absolute right-3.5 top-2.5 text-[#556E5C] hover:text-[#243329] transition p-0.5"
                                        aria-label="Tampilkan kata sandi">
                                    <svg class="w-4 h-4 eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="w-4 h-4 eye-closed hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->updatePassword->has('current_password'))
                                <p class="text-xs text-[#A23B38] mt-1 font-medium">
                                    {{ $errors->updatePassword->first('current_password') }}
                                </p>
                            @endif
                        </div>

                        <!-- Kata Sandi Baru -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-[#243329] uppercase tracking-wider mb-2">
                                Kata Sandi Baru <span class="text-[#A23B38]">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       required 
                                       class="w-full rounded-xl border border-[#DFE1D8] bg-[#FAFBF9] pl-4 pr-11 py-2.5 text-sm text-[#1C2820] focus:border-[#556E5C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 transition {{ $errors->updatePassword->has('password') ? 'border-[#D98E8A]' : '' }}">
                                <button type="button" 
                                        data-target="password" 
                                        class="toggle-password-btn absolute right-3.5 top-2.5 text-[#556E5C] hover:text-[#243329] transition p-0.5"
                                        aria-label="Tampilkan kata sandi">
                                    <svg class="w-4 h-4 eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="w-4 h-4 eye-closed hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-[#556E5C] mt-1.5">Minimal 8 karakter.</p>
                            @if ($errors->updatePassword->has('password'))
                                <p class="text-xs text-[#A23B38] mt-1 font-medium">
                                    {{ $errors->updatePassword->first('password') }}
                                </p>
                            @endif
                        </div>

                        <!-- Konfirmasi Kata Sandi Baru -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-[#243329] uppercase tracking-wider mb-2">
                                Konfirmasi Kata Sandi Baru <span class="text-[#A23B38]">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       required 
                                       class="w-full rounded-xl border border-[#DFE1D8] bg-[#FAFBF9] pl-4 pr-11 py-2.5 text-sm text-[#1C2820] focus:border-[#556E5C] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 transition">
                                <button type="button" 
                                        data-target="password_confirmation" 
                                        class="toggle-password-btn absolute right-3.5 top-2.5 text-[#556E5C] hover:text-[#243329] transition p-0.5"
                                        aria-label="Tampilkan kata sandi">
                                    <svg class="w-4 h-4 eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="w-4 h-4 eye-closed hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Aksi -->
                    <div class="border-t border-[#ECEEE8] mt-6 pt-4 flex justify-end">
                        <button type="submit" 
                                class="bg-[#243329] hover:bg-[#1C2820] text-white rounded-xl px-6 py-2.5 text-sm font-medium transition shadow-sm">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

            <!-- PANEL 3: details (Detail Akun & Hak Akses) -->
            <div id="panel-details" class="{{ $initialTab === 'details' ? '' : 'hidden' }} flex-1 flex flex-col">
                <div class="border-b border-[#ECEEE8] pb-4 mb-5">
                    <h2 class="text-lg font-bold text-[#1C2820]">Detail Akun & Hak Akses</h2>
                    <p class="text-xs text-[#556E5C] mt-1">Info perusahaan, kontak, tanggal bergabung, dan otoritas peran akun Anda.</p>
                </div>

                <div class="space-y-6">
                    <!-- Grid Kartu Informasi Read-Only -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="bg-[#F6F7F3] border border-[#DFE1D8]/80 rounded-2xl p-4">
                            <span class="block text-[11px] font-semibold tracking-wider text-[#556E5C] uppercase">Peran Akun</span>
                            <span class="block text-sm font-semibold text-[#1C2820] mt-1">
                                {{ $user->isAdmin() ? 'Administrator' : ($user->isOperator() ? 'Logistics Operator' : 'Customer') }}
                            </span>
                        </div>
                        <div class="bg-[#F6F7F3] border border-[#DFE1D8]/80 rounded-2xl p-4">
                            <span class="block text-[11px] font-semibold tracking-wider text-[#556E5C] uppercase">Perusahaan</span>
                            <span class="block text-sm font-semibold text-[#1C2820] mt-1">
                                {{ $user->company_name ?? '-' }}
                            </span>
                        </div>
                        <div class="bg-[#F6F7F3] border border-[#DFE1D8]/80 rounded-2xl p-4">
                            <span class="block text-[11px] font-semibold tracking-wider text-[#556E5C] uppercase">Nomor Telepon</span>
                            <span class="block text-sm font-semibold text-[#1C2820] mt-1">
                                {{ $user->phone ?? '-' }}
                            </span>
                        </div>
                        <div class="bg-[#F6F7F3] border border-[#DFE1D8]/80 rounded-2xl p-4">
                            <span class="block text-[11px] font-semibold tracking-wider text-[#556E5C] uppercase">Bergabung Sejak</span>
                            <span class="block text-sm font-semibold text-[#1C2820] mt-1">
                                {{ $user->created_at ? ($user->created_at->translatedFormat('d M Y') ?? $user->created_at->format('d M Y')) : '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Kotak Penjelasan Otoritas & Hak Akses Sesuai Role -->
                    <div class="bg-[#F6F7F3] border border-[#DFE1D8]/80 rounded-2xl p-5">
                        <div class="flex items-center gap-2.5 font-semibold text-[#243329] text-sm mb-2.5">
                            <svg class="w-5 h-5 text-[#243329]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <span>Otoritas & Hak Akses ({{ $user->isAdmin() ? 'Administrator' : ($user->isOperator() ? 'Logistics Operator' : 'Customer') }})</span>
                        </div>
                        <p class="text-[#556E5C] text-sm leading-relaxed">
                            @if ($user->isAdmin())
                                Memiliki otoritas penuh mengelola seluruh pengguna, master data carrier dan lokasi, konfigurasi delay threshold, serta audit log sistem.
                            @elseif ($user->isOperator())
                                Bertanggung jawab memperbarui mutasi data logistik, checkpoint pelacakan pengiriman, dan pelaporan kendala (exceptions).
                            @else
                                Memiliki akses pelacakan pengiriman mandiri, riwayat status logistik kargo, dan penerimaan notifikasi operasional.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Vanilla JS untuk Navigasi Tab 2 Panel, Live Pratinjau Foto, & Toggle Password -->
<script>
    function switchTab(tabName) {
        const tabs = ['profile', 'password', 'details'];
        tabs.forEach(function (tab) {
            const btn = document.getElementById('tab-btn-' + tab);
            const panel = document.getElementById('panel-' + tab);
            const iconWrap = document.getElementById('tab-icon-wrap-' + tab);
            const title = document.getElementById('tab-title-' + tab);
            const subtitle = document.getElementById('tab-sub-' + tab);

            if (tab === tabName) {
                if (panel) panel.classList.remove('hidden');
                if (btn) {
                    btn.className = 'w-full text-left p-3.5 rounded-2xl flex items-center gap-3.5 transition cursor-pointer bg-[#C8D3BE] text-[#1C2820] shadow-sm';
                }
                if (iconWrap) {
                    iconWrap.className = 'w-10 h-10 rounded-xl bg-[#1C2820]/10 text-[#1C2820] flex items-center justify-center shrink-0';
                }
                if (title) {
                    title.className = 'text-sm font-semibold text-[#1C2820]';
                }
                if (subtitle) {
                    subtitle.className = 'text-xs text-[#1C2820]/75 mt-0.5';
                }
            } else {
                if (panel) panel.classList.add('hidden');
                if (btn) {
                    btn.className = 'w-full text-left p-3.5 rounded-2xl flex items-center gap-3.5 transition cursor-pointer text-white/85 hover:bg-white/10';
                }
                if (iconWrap) {
                    iconWrap.className = 'w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center shrink-0';
                }
                if (title) {
                    title.className = 'text-sm font-semibold text-white';
                }
                if (subtitle) {
                    subtitle.className = 'text-xs text-[#C8D3BE]/70 mt-0.5';
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Smart Initial State: otomatis aktifkan tab sesuai error validasi atau session status
        @if ($errors->updatePassword->any() || session('password_status'))
            switchTab('password');
        @else
            switchTab('profile');
        @endif

        // 1. Live Preview Unggah Foto Profil
        const avatarInput = document.getElementById('avatar-input');
        const avatarPreviewImg = document.getElementById('avatar-preview-img');
        const avatarPreviewFallback = document.getElementById('avatar-preview-fallback');
        const avatarFileName = document.getElementById('avatar-file-name');

        if (avatarInput) {
            avatarInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    if (avatarFileName) {
                        avatarFileName.textContent = file.name;
                    }
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        if (avatarPreviewImg) {
                            avatarPreviewImg.src = event.target.result;
                            avatarPreviewImg.classList.remove('hidden');
                        }
                        if (avatarPreviewFallback) {
                            avatarPreviewFallback.classList.add('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // 2. Toggle Show/Hide Password
        document.querySelectorAll('.toggle-password-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const eyeOpen = this.querySelector('.eye-open');
                const eyeClosed = this.querySelector('.eye-closed');

                if (input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        if (eyeOpen) eyeOpen.classList.add('hidden');
                        if (eyeClosed) eyeClosed.classList.remove('hidden');
                    } else {
                        input.type = 'password';
                        if (eyeOpen) eyeOpen.classList.remove('hidden');
                        if (eyeClosed) eyeClosed.classList.add('hidden');
                    }
                }
            });
        });
    });
</script>
@endsection
