@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('users.index') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-[#6F756C] hover:text-[#1C1E1B] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        <div class="p-6 sm:p-8 bg-gradient-to-br from-[#7B9581] via-[#556E5C] to-[#4E6756] text-white">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-[11px] font-semibold uppercase tracking-wider mb-2">Master Data</span>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Tambah Pengguna Baru</h1>
            <p class="text-xs sm:text-sm text-white/80 mt-1">Daftarkan akun staf operasional, administrator, atau klien kargo baru.</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="p-6 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                           class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DCE0D6] bg-white' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="user@company.com"
                           class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DCE0D6] bg-white' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DCE0D6] bg-white' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Peran (Role) -->
                <div>
                    <label for="role" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select name="role" id="role" required
                            class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('role') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DCE0D6] bg-white' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                        <option value="">-- Pilih Peran Pengguna --</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer (Pelanggan / Pemilik Kargo)</option>
                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator (Petugas Operasional Logistik)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Administrator Sistem)</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Perusahaan -->
                <div>
                    <label for="company_name" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Nama Perusahaan</label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" placeholder="PT Sumber Logistik Nusantara"
                           class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('company_name') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DCE0D6] bg-white' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                    @error('company_name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+62 812-3456-7890"
                           class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DCE0D6] bg-white' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                    @error('phone')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t border-[#DFE1D8]/70 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-full border border-[#DCE0D6] text-[#6F756C] text-sm font-semibold hover:bg-[#F5F6F1] transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-sm font-bold shadow-sm transition">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
