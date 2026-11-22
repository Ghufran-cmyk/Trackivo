@extends('layouts.app')

@section('title', 'Tambah Carrier')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Breadcrumb & Back -->
    <div class="mb-6">
        <a href="{{ route('carriers.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Carrier</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 bg-[#0e1117] text-white">
            <span class="inline-block px-2.5 py-0.5 rounded-full bg-[#d4f93a]/20 text-[#d4f93a] text-xs font-semibold uppercase tracking-wider mb-2">Master Data</span>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Tambah Carrier Baru</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Daftarkan mitra pengangkut kargo baru ke dalam jaringan visibilitas Trackivo.</p>
        </div>

        <form method="POST" action="{{ route('carriers.store') }}" class="p-6 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kode Carrier -->
                <div>
                    <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Carrier <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required placeholder="Contoh: MAEU, MSC, SQ"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('code') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a] uppercase font-mono">
                    @error('code')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Carrier -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Carrier <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Maersk Line, Singapore Airlines Cargo"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Kontak -->
                <div>
                    <label for="contact_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Kontak</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" placeholder="ops@carrier.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('contact_email') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('contact_email')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon Kontak -->
                <div>
                    <label for="contact_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. Telepon Kontak</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" placeholder="+62 21 555 1234"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('contact_phone') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('contact_phone')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Aktif -->
            <div class="pt-4 border-t border-slate-100 flex items-center space-x-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-[#d4f93a]">
                <label for="is_active" class="text-sm font-semibold text-slate-700">Aktifkan carrier ini untuk pilihan pembuatan shipment baru</label>
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('carriers.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-sm font-bold shadow-sm transition">
                    Simpan Carrier
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
