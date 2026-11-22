@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Breadcrumb & Back -->
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 bg-[#0e1117] text-white">
            <span class="inline-block px-2.5 py-0.5 rounded-full bg-[#d4f93a]/20 text-[#d4f93a] text-xs font-semibold uppercase tracking-wider mb-2">Master Data</span>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Tambah Pengguna Baru</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Daftarkan akun staf operasional, administrator, atau klien kargo baru.</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="p-6 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="user@company.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Peran (Role) -->
                <div>
                    <label for="role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select name="role" id="role" required
                            class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('role') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
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
                    <label for="company_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Perusahaan</label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" placeholder="PT Sumber Logistik Nusantara"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('company_name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('company_name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+62 812-3456-7890"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('phone')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-sm font-bold shadow-sm transition">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
