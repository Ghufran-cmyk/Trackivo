@extends('layouts.app')

@section('title', 'Edit Carrier - ' . $carrier->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Back -->
    <div>
        <a href="{{ route('carriers.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-[#6F756C] hover:text-[#1C1E1B] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Carrier</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        <div class="p-6 sm:p-8 bg-gradient-to-r from-[#7B9581] to-[#556E5C] text-white">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold uppercase tracking-wider mb-2">Edit Master Data</span>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Edit Carrier: {{ $carrier->name }}</h1>
            <p class="text-xs sm:text-sm text-white/90 mt-1">Perbarui data profil operasional carrier mitra Trackivo.</p>
        </div>

        <form method="POST" action="{{ route('carriers.update', $carrier) }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kode Carrier -->
                <div>
                    <label for="code" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Kode Carrier <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code', $carrier->code) }}" required
                           class="w-full px-3.5 py-2.5 rounded-2xl border {{ $errors->has('code') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DFE1D8] bg-[#FAF9F5]/50' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C] uppercase font-mono">
                    @error('code')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Carrier -->
                <div>
                    <label for="name" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Nama Carrier <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $carrier->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-2xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DFE1D8] bg-[#FAF9F5]/50' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Kontak -->
                <div>
                    <label for="contact_email" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">Email Kontak</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $carrier->contact_email) }}"
                           class="w-full px-3.5 py-2.5 rounded-2xl border {{ $errors->has('contact_email') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DFE1D8] bg-[#FAF9F5]/50' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                    @error('contact_email')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon Kontak -->
                <div>
                    <label for="contact_phone" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">No. Telepon Kontak</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $carrier->contact_phone) }}"
                           class="w-full px-3.5 py-2.5 rounded-2xl border {{ $errors->has('contact_phone') ? 'border-rose-400 bg-rose-50/30' : 'border-[#DFE1D8] bg-[#FAF9F5]/50' }} text-[#1C1E1B] text-sm focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                    @error('contact_phone')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Aktif -->
            <div class="pt-4 border-t border-[#DFE1D8]/60 flex items-center space-x-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $carrier->is_active) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-[#DFE1D8] text-[#556E5C] focus:ring-[#556E5C]">
                <label for="is_active" class="text-sm font-semibold text-[#1C1E1B]">Carrier aktif dan siap digunakan untuk penugasan kargo</label>
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t border-[#DFE1D8]/60 flex items-center justify-end space-x-3">
                <a href="{{ route('carriers.index') }}" class="px-5 py-2.5 rounded-full border border-[#DFE1D8] bg-[#F5F6F1] text-[#1C1E1B] text-sm font-semibold hover:bg-[#E2E4DC] transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-sm font-bold shadow-xs transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
