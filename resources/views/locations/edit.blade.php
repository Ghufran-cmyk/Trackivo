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
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        <div class="p-6 sm:p-8 bg-gradient-to-r from-[#7B9581] to-[#556E5C] text-white">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold uppercase tracking-wider mb-2">Edit Master Data</span>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Edit Lokasi: {{ $location->name }}</h1>
            <p class="text-xs sm:text-sm text-white/90 mt-1">Perbarui kode, nama, tipe titik, atau koordinat geospasial.</p>
        </div>

        <form method="POST" action="{{ route('locations.update', $location) }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kode Lokasi -->
                <div>
                    <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Lokasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code', $location->code) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('code') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] uppercase font-mono">
                    @error('code')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lokasi -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lokasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Negara -->
                <div>
                    <label for="country" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Negara <span class="text-rose-500">*</span></label>
                    <input type="text" name="country" id="country" value="{{ old('country', $location->country) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('country') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                    @error('country')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Lokasi -->
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Lokasi <span class="text-rose-500">*</span></label>
                    <select name="type" id="type" required
                            class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('type') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                        <option value="PORT" {{ old('type', $location->type) == 'PORT' ? 'selected' : '' }}>PORT (Pelabuhan Laut)</option>
                        <option value="AIRPORT" {{ old('type', $location->type) == 'AIRPORT' ? 'selected' : '' }}>AIRPORT (Bandara Udara)</option>
                        <option value="WAREHOUSE" {{ old('type', $location->type) == 'WAREHOUSE' ? 'selected' : '' }}>WAREHOUSE (Gudang Transit)</option>
                        <option value="CHECKPOINT" {{ old('type', $location->type) == 'CHECKPOINT' ? 'selected' : '' }}>CHECKPOINT (Titik Pantau)</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Geocoding Search Box (Spans 2 columns) -->
                <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center space-x-2">
                            <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                            <label for="geo_search_input" class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Cari Koordinat Otomatis (OpenStreetMap Geocoding)
                            </label>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono">Nominatim API</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-3">
                        Ketik nama pelabuhan, bandara, atau kota (contoh: <em>Port of Rotterdam</em>, <em>Soekarno-Hatta</em>, <em>Port of Singapore</em>) untuk mencari dan memperbarui koordinat.
                    </p>
                    
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" id="geo_search_input" placeholder="Ketik nama pelabuhan, bandara, atau kota..."
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] transition">
                        </div>
                        <button type="button" id="btn_geocode_search" class="px-5 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs font-bold shadow-xs transition inline-flex items-center space-x-1.5 shrink-0">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Cari Lokasi</span>
                        </button>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="geo_loading" class="hidden mt-3 p-3 rounded-xl bg-[#F4F7F4] border border-[#DFE1D8] text-[#35473A] text-xs flex items-center space-x-2">
                        <svg class="animate-spin h-4 w-4 text-[#556E5C]" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Menghubungi OpenStreetMap Geocoding API...</span>
                    </div>

                    <!-- Error Alert -->
                    <div id="geo_error" class="hidden mt-3 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                        <span id="geo_error_message"></span>
                    </div>

                    <!-- Success Notice / Selection Info -->
                    <div id="geo_success_notice" class="hidden mt-3 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
                        <span id="geo_success_message">Koordinat berhasil disalin ke form.</span>
                    </div>

                    <!-- Results Container -->
                    <div id="geo_results" class="hidden mt-3 space-y-2 max-h-56 overflow-y-auto pt-1">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Latitude -->
                <div>
                    <label for="latitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latitude (-90 s.d. 90) <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $location->latitude) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('latitude') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] font-mono">
                    @error('latitude')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label for="longitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Longitude (-180 s.d. 180) <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $location->longitude) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('longitude') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] font-mono">
                    @error('longitude')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t border-[#DFE1D8]/60 flex items-center justify-end space-x-3">
                <a href="{{ route('locations.index') }}" class="px-5 py-2.5 rounded-full border border-[#DFE1D8] bg-[#F5F6F1] text-[#1C1E1B] text-sm font-semibold hover:bg-[#E2E4DC] transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-sm font-bold shadow-xs transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('geo_search_input');
    const searchBtn = document.getElementById('btn_geocode_search');
    const loadingEl = document.getElementById('geo_loading');
    const errorEl = document.getElementById('geo_error');
    const errorMsgEl = document.getElementById('geo_error_message');
    const successEl = document.getElementById('geo_success_notice');
    const successMsgEl = document.getElementById('geo_success_message');
    const resultsEl = document.getElementById('geo_results');

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const countryInput = document.getElementById('country');

    function performGeocode() {
        const query = searchInput.value.trim();
        if (query.length < 3) {
            showError('Masukkan minimal 3 karakter nama kota, pelabuhan, atau bandara.');
            return;
        }

        // Reset display state
        errorEl.classList.add('hidden');
        successEl.classList.add('hidden');
        resultsEl.classList.add('hidden');
        resultsEl.innerHTML = '';
        loadingEl.classList.remove('hidden');

        fetch(`{{ route('locations.geocode') }}?q=${encodeURIComponent(query)}&limit=5`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Gagal menghubungi server (HTTP ${response.status})`);
            }
            return response.json();
        })
        .then(data => {
            loadingEl.classList.add('hidden');
            if (!data.success && data.error) {
                showError(data.error);
                return;
            }

            if (!data.results || data.results.length === 0) {
                showError(`Tidak ditemukan koordinat untuk "${query}". Coba kata kunci yang lebih spesifik.`);
                return;
            }

            renderResults(data.results);
        })
        .catch(err => {
            loadingEl.classList.add('hidden');
            showError(err.message || 'Terjadi kesalahan saat memproses pencarian koordinat.');
        });
    }

    function showError(msg) {
        errorMsgEl.textContent = msg;
        errorEl.classList.remove('hidden');
    }

    function renderResults(items) {
        resultsEl.innerHTML = '';
        items.forEach((item) => {
            const card = document.createElement('div');
            card.className = 'p-3 rounded-xl bg-white border border-slate-200 hover:border-slate-800 hover:bg-slate-50/80 transition cursor-pointer flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs shadow-xs';
            card.innerHTML = `
                <div class="pr-2">
                    <div class="font-bold text-slate-900 leading-snug">${escapeHtml(item.display_name)}</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">
                        <span class="inline-block px-1.5 py-0.2 rounded bg-slate-100 text-slate-700 font-semibold uppercase text-[10px]">${escapeHtml(item.type)}</span>
                        ${item.country ? ` &bull; <span>${escapeHtml(item.country)}</span>` : ''}
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-1 rounded text-[11px] inline-block">
                        ${item.latitude.toFixed(4)}°, ${item.longitude.toFixed(4)}°
                    </span>
                    <span class="block text-[10px] text-emerald-600 font-bold mt-1">Gunakan Koordinat &rarr;</span>
                </div>
            `;

            card.addEventListener('click', function() {
                latInput.value = item.latitude;
                lngInput.value = item.longitude;
                if (countryInput && (!countryInput.value || countryInput.value.trim() === '')) {
                    if (item.country) {
                        countryInput.value = item.country;
                    }
                }
                successMsgEl.textContent = `Koordinat ${item.latitude.toFixed(4)}°, ${item.longitude.toFixed(4)}° berhasil diterapkan ke formulir.`;
                successEl.classList.remove('hidden');
                latInput.focus();
            });

            resultsEl.appendChild(card);
        });
        resultsEl.classList.remove('hidden');
    }

    function escapeHtml(str) {
        if (!str) return '';
        const p = document.createElement('p');
        p.textContent = str;
        return p.innerHTML;
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', performGeocode);
    }
    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performGeocode();
            }
        });
    }
});
</script>
@endpush
@endsection
