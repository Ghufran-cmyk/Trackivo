@extends('layouts.app')

@section('title', 'Pengaturan Batas Keterlambatan')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1C1E1B] tracking-tight">Pengaturan Batas Keterlambatan</h1>
            <p class="text-sm text-[#6F756C] mt-1">
                Atur ambang batas durasi delay secara deterministik.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Form: Ubah Pengaturan Threshold -->
        <div class="lg:col-span-6 space-y-6">
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6 sm:p-7">
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-[#DFE1D8]/60">
                    <div>
                        <h2 class="text-base font-bold text-[#1C1E1B]">Form Konfigurasi Ambang Batas</h2>
                        <p class="text-xs text-[#6F756C] mt-0.5">Masukkan durasi batas dalam satuan jam bulat (positif).</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-[#F1F2EC] text-[#1C1E1B] text-xs font-bold font-mono">RULE-BASED</span>
                </div>

                <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Input 1: delay_low_max_hours -->
                    <div>
                        <label for="delay_low_max_hours" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">
                            Batas Maksimal Delay LOW (<span class="font-mono text-[#9A6721]">delay_low_max_hours</span>) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="delay_low_max_hours" 
                                   id="delay_low_max_hours" 
                                   min="1" 
                                   max="168"
                                   value="{{ old('delay_low_max_hours', $lowMax) }}" 
                                   required
                                   class="w-full pl-4 pr-16 py-2.5 rounded-2xl border border-[#DCE0D6] text-[#1C1E1B] text-sm font-semibold focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                            <span class="absolute right-4 top-2.5 text-xs font-bold text-[#6F756C] uppercase">Jam</span>
                        </div>
                        <p class="text-xs text-[#6F756C] mt-1.5">
                            Keterlambatan 1 jam s.d. nilai ini akan dikategorikan sebagai <strong class="text-[#9A6721]">LOW</strong>. Default standar: 6 jam.
                        </p>
                        @error('delay_low_max_hours')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input 2: delay_high_min_hours -->
                    <div>
                        <label for="delay_high_min_hours" class="block text-xs font-bold text-[#1C1E1B] uppercase tracking-wider mb-1.5">
                            Batas Minimal Delay HIGH (<span class="font-mono text-[#B84242]">delay_high_min_hours</span>) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="delay_high_min_hours" 
                                   id="delay_high_min_hours" 
                                   min="2" 
                                   max="720"
                                   value="{{ old('delay_high_min_hours', $highMin) }}" 
                                   required
                                   class="w-full pl-4 pr-16 py-2.5 rounded-2xl border border-[#DCE0D6] text-[#1C1E1B] text-sm font-semibold focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                            <span class="absolute right-4 top-2.5 text-xs font-bold text-[#6F756C] uppercase">Jam</span>
                        </div>
                        <p class="text-xs text-[#6F756C] mt-1.5">
                            Keterlambatan di atas nilai ini akan dikategorikan sebagai <strong class="text-[#B84242]">HIGH</strong>. Rentang di antara batas LOW dan batas ini otomatis menjadi <strong class="text-[#9A6721]">MEDIUM</strong>.
                        </p>
                        @error('delay_high_min_hours')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Peringatan Rekalkulasi -->
                    <div class="p-4 rounded-2xl bg-[#F7EFE1] border border-[#F7EFE1] text-[#9A6721] text-xs leading-relaxed flex items-start space-x-3">
                        <svg class="h-5 w-5 text-[#9A6721] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <strong class="font-semibold block mb-0.5">Otomatisasi Sinkronisasi Langsung:</strong>
                            Menyimpan konfigurasi baru ini akan langsung memicu service kalkulasi <code class="font-mono font-bold bg-[#F4EFE6] px-1 py-0.5 rounded">EtaService::recalculateActiveShipments()</code> untuk menghitung ulang status keterlambatan seluruh pengiriman aktif yang belum selesai.
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-6 py-3 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white font-bold text-sm shadow-sm transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Simpan & Hitung Ulang Pengiriman Aktif</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Tabel Aturan & Preview Dinamis -->
        <div class="lg:col-span-6 space-y-6">

            <!-- Card Aturan Aktif -->
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
                <div class="p-6 border-b border-[#DFE1D8]/60 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-[#1C1E1B]">Tabel Klasifikasi Keterlambatan Aktif</h2>
                        <p class="text-xs text-[#6F756C] mt-0.5">Struktur logika deterministik yang saat ini berlaku di sistem Trackivo.</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#E6F0E9] text-[#2F6846]">
                        Aktif
                    </span>
                </div>

                <div class="divide-y divide-[#DFE1D8]/50">
                    
                    <!-- Rule NONE (On-Time) -->
                    <div class="p-4 flex items-center justify-between hover:bg-[#F5F6F1]/70 transition">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-10 w-10 rounded-full bg-[#E6F0E9] text-[#2F6846] flex items-center justify-center font-bold text-xs">
                                ✓
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-[#1C1E1B]">Tepat Waktu (On-Time)</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E6F0E9] text-[#2F6846] uppercase">NONE</span>
                                </div>
                                <p class="text-xs text-[#6F756C] mt-0.5">Estimasi tiba &le; Jadwal target awal kedatangan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono text-[#2F6846] bg-[#E6F0E9] px-2.5 py-1 rounded-full">
                                0 Jam
                            </span>
                        </div>
                    </div>

                    <!-- Rule LOW -->
                    <div class="p-4 flex items-center justify-between hover:bg-[#F5F6F1]/70 transition">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-10 w-10 rounded-full bg-[#F7EFE1] text-[#9A6721] flex items-center justify-center font-bold text-xs">
                                1
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-[#1C1E1B]">Keterlambatan Rendah</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F7EFE1] text-[#9A6721] uppercase">LOW</span>
                                </div>
                                <p class="text-xs text-[#6F756C] mt-0.5">Selisih 1 jam s.d. ambang batas low_max</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono text-[#9A6721] bg-[#F7EFE1] px-2.5 py-1 rounded-full">
                                1 – {{ $lowMax }} Jam
                            </span>
                        </div>
                    </div>

                    <!-- Rule MEDIUM -->
                    <div class="p-4 flex items-center justify-between hover:bg-[#F5F6F1]/70 transition">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-10 w-10 rounded-full bg-[#F4EFE6] text-[#9A6721] flex items-center justify-center font-bold text-xs">
                                2
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-[#1C1E1B]">Keterlambatan Sedang</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F4EFE6] text-[#9A6721] uppercase">MEDIUM</span>
                                </div>
                                <p class="text-xs text-[#6F756C] mt-0.5">Selisih di atas low_max s.d. ambang batas high_min</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono text-[#9A6721] bg-[#F4EFE6] px-2.5 py-1 rounded-full">
                                > {{ $lowMax }} – {{ $highMin }} Jam
                            </span>
                        </div>
                    </div>

                    <!-- Rule HIGH -->
                    <div class="p-4 flex items-center justify-between hover:bg-[#F5F6F1]/70 transition">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-10 w-10 rounded-full bg-[#F9E6E6] text-[#B84242] flex items-center justify-center font-bold text-xs">
                                3
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-[#1C1E1B]">Keterlambatan Kritis / Tinggi</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F9E6E6] text-[#B84242] uppercase">HIGH</span>
                                </div>
                                <p class="text-xs text-[#6F756C] mt-0.5">Selisih melampaui ambang batas high_min</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono text-[#B84242] bg-[#F9E6E6] px-2.5 py-1 rounded-full">
                                > {{ $highMin }} Jam
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Dokumentasi Rumus Deterministik -->
            <div class="bg-[#556E5C] rounded-[24px] p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-white mb-3">Cara Perhitungan Keterlambatan</h3>
                <div class="space-y-3">
                    <div class="bg-black/15 border border-white/15 rounded-xl p-3 text-xs font-mono text-white/95 leading-relaxed">
                        Selisih Menit = Estimasi Tiba − Jadwal Awal<br>
                        Durasi Delay  = Pembulatan ke atas (Selisih Menit ÷ 60), minimal 1 jam
                    </div>
                    <p class="text-xs text-white/85 font-sans leading-relaxed">
                        Jika estimasi tiba melewati jadwal awal, sistem otomatis membulatkan selisih keterlambatan ke atas dalam satuan jam. Perhitungan berbasis aturan tetap ini memastikan status delay selalu konsisten dan mudah diverifikasi.
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
