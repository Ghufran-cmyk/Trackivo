@extends('layouts.app')

@section('title', 'Pengaturan Batas Keterlambatan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-slate-900 text-[#d4f93a] text-xs font-semibold mb-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d4f93a]"></span>
                <span>Konfigurasi Operasional Global (Administrator)</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Batas Keterlambatan (Delay Thresholds)</h1>
            <p class="text-sm text-slate-500 mt-1">
                Atur ambang batas durasi delay secara deterministik. Nilai konfigurasi ini berlaku global untuk mengklasifikasikan tingkat keparahan risiko keterlambatan kargo.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Form: Ubah Pengaturan Threshold -->
        <div class="lg:col-span-6 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-7">
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Form Konfigurasi Ambang Batas</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Masukkan durasi batas dalam satuan jam bulat (positif).</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold font-mono">RULE-BASED</span>
                </div>

                <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Input 1: delay_low_max_hours -->
                    <div>
                        <label for="delay_low_max_hours" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Batas Maksimal Delay LOW (<span class="font-mono text-amber-600">delay_low_max_hours</span>) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="delay_low_max_hours" 
                                   id="delay_low_max_hours" 
                                   min="1" 
                                   max="168"
                                   value="{{ old('delay_low_max_hours', $lowMax) }}" 
                                   required
                                   class="w-full pl-4 pr-16 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#d4f93a] focus:border-slate-900 transition">
                            <span class="absolute right-4 top-2.5 text-xs font-bold text-slate-400 uppercase">Jam</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5">
                            Keterlambatan 1 jam s.d. nilai ini akan dikategorikan sebagai <strong class="text-amber-700">LOW</strong>. Default standar: 6 jam.
                        </p>
                        @error('delay_low_max_hours')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input 2: delay_high_min_hours -->
                    <div>
                        <label for="delay_high_min_hours" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Batas Minimal Delay HIGH (<span class="font-mono text-rose-600">delay_high_min_hours</span>) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="delay_high_min_hours" 
                                   id="delay_high_min_hours" 
                                   min="2" 
                                   max="720"
                                   value="{{ old('delay_high_min_hours', $highMin) }}" 
                                   required
                                   class="w-full pl-4 pr-16 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#d4f93a] focus:border-slate-900 transition">
                            <span class="absolute right-4 top-2.5 text-xs font-bold text-slate-400 uppercase">Jam</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5">
                            Keterlambatan di atas nilai ini akan dikategorikan sebagai <strong class="text-rose-700">HIGH</strong>. Rentang di antara batas LOW dan batas ini otomatis menjadi <strong class="text-orange-700">MEDIUM</strong>.
                        </p>
                        @error('delay_high_min_hours')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Peringatan Rekalkulasi -->
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs leading-relaxed flex items-start space-x-3">
                        <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <strong class="font-semibold block mb-0.5">Otomatisasi Sinkronisasi Langsung:</strong>
                            Menyimpan konfigurasi baru ini akan langsung memicu service kalkulasi <code class="font-mono font-bold bg-amber-100/70 px-1 py-0.5 rounded">EtaService::recalculateActiveShipments()</code> untuk menghitung ulang status keterlambatan seluruh pengiriman aktif yang belum selesai.
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-5 py-3 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] font-bold text-sm shadow-md transition">
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
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Tabel Klasifikasi Keterlambatan Aktif</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Struktur logika deterministik yang saat ini berlaku di sistem Trackivo.</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        Aktif
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    
                    <!-- Rule NONE (On-Time) -->
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                ✓
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-slate-800">Tepat Waktu (On-Time)</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">NONE</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Estimasi tiba &le; Jadwal target awal kedatangan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-200/60">
                                0 Jam
                            </span>
                        </div>
                    </div>

                    <!-- Rule LOW -->
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-10 w-10 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-600 flex items-center justify-center font-bold text-xs">
                                1
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-slate-800">Keterlambatan Rendah</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">LOW</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Selisih 1 jam s.d. ambang batas low_max</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200/60">
                                1 – {{ $lowMax }} Jam
                            </span>
                        </div>
                    </div>

                    <!-- Rule MEDIUM -->
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-10 w-10 rounded-xl bg-orange-50 border border-orange-200/80 text-orange-600 flex items-center justify-center font-bold text-xs">
                                2
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-slate-800">Keterlambatan Sedang</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-800 uppercase">MEDIUM</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Selisih di atas low_max s.d. ambang batas high_min</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono text-orange-700 bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-200/60">
                                > {{ $lowMax }} – {{ $highMin }} Jam
                            </span>
                        </div>
                    </div>

                    <!-- Rule HIGH -->
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-10 w-10 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-600 flex items-center justify-center font-bold text-xs">
                                3
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-slate-800">Keterlambatan Kritis / Tinggi</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 uppercase">HIGH</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Selisih melampaui ambang batas high_min</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold font-mono text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200/60">
                                > {{ $highMin }} Jam
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Dokumentasi Rumus Deterministik -->
            <div class="bg-[#0f1219] text-white rounded-2xl p-6 shadow-sm border border-slate-800">
                <div class="flex items-center space-x-2.5 mb-3">
                    <div class="h-2 w-2 rounded-full bg-[#d4f93a]"></div>
                    <h3 class="text-sm font-bold text-[#d4f93a] uppercase tracking-wider">Rumus Deterministik EtaService</h3>
                </div>
                <div class="space-y-3 text-xs text-slate-300 leading-relaxed font-mono">
                    <p class="bg-slate-900/90 p-2.5 rounded-xl border border-slate-800">
                        diff_minutes = expected_arrival.diffInMinutes(estimated_arrival)<br>
                        delay_hours  = max(1, ceil(diff_minutes / 60))
                    </p>
                    <p class="text-slate-400 font-sans text-xs">
                        Algoritma ini sepenuhnya transparan dan deterministik tanpa ketergantungan pada model probabilistik eksternal, memudahkan verifikasi dan audit kepatuhan SLA logistik antar-negara.
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
