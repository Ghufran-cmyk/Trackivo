@extends('layouts.app')

@section('title', 'Edit Lokasi - ' . $location->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Breadcrumb & Back -->
    <div class="mb-6">
        <a href="{{ route('locations.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Lokasi</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 bg-[#0e1117] text-white">
            <span class="inline-block px-2.5 py-0.5 rounded-full bg-[#d4f93a]/20 text-[#d4f93a] text-xs font-semibold uppercase tracking-wider mb-2">Edit Master Data</span>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Edit Lokasi: {{ $location->name }}</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Perbarui kode, nama, tipe titik, atau koordinat geospasial.</p>
        </div>

        <form method="POST" action="{{ route('locations.update', $location) }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kode Lokasi -->
                <div>
                    <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Lokasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code', $location->code) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('code') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a] uppercase font-mono">
                    @error('code')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lokasi -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lokasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Negara -->
                <div>
                    <label for="country" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Negara <span class="text-rose-500">*</span></label>
                    <input type="text" name="country" id="country" value="{{ old('country', $location->country) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('country') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                    @error('country')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Lokasi -->
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Lokasi <span class="text-rose-500">*</span></label>
                    <select name="type" id="type" required
                            class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('type') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="PORT" {{ old('type', $location->type) == 'PORT' ? 'selected' : '' }}>PORT (Pelabuhan Laut)</option>
                        <option value="AIRPORT" {{ old('type', $location->type) == 'AIRPORT' ? 'selected' : '' }}>AIRPORT (Bandara Udara)</option>
                        <option value="WAREHOUSE" {{ old('type', $location->type) == 'WAREHOUSE' ? 'selected' : '' }}>WAREHOUSE (Gudang Transit)</option>
                        <option value="CHECKPOINT" {{ old('type', $location->type) == 'CHECKPOINT' ? 'selected' : '' }}>CHECKPOINT (Titik Pantau)</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Latitude -->
                <div>
                    <label for="latitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latitude (-90 s.d. 90) <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $location->latitude) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('latitude') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a] font-mono">
                    @error('latitude')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label for="longitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Longitude (-180 s.d. 180) <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $location->longitude) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('longitude') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#d4f93a] font-mono">
                    @error('longitude')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('locations.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-sm font-bold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
