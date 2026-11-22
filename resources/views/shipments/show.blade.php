@extends('layouts.app')

@section('title', 'Detail Shipment - ' . $shipment->tracking_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">

    <!-- Breadcrumb & Top Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('shipments.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Daftar Pengiriman</span>
            </a>
        </div>

        <div class="flex items-center space-x-2">
            @if((auth()->user()->isAdmin() || auth()->user()->isOperator()) && !in_array($shipment->status, ['DELIVERED', 'CANCELLED']))
                <a href="{{ route('shipments.edit', $shipment) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-sm transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Informasi</span>
                </a>
            @endif

            @if(auth()->user()->isAdmin() && in_array($shipment->status, ['CREATED', 'CANCELLED']))
                <form method="POST" action="{{ route('shipments.destroy', $shipment) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus shipment {{ $shipment->tracking_number }}? Seluruh riwayat tracking dan item akan terhapus.');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-700 text-xs font-bold transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Hapus Shipment</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Header Card -->
    <div class="bg-gradient-to-r from-[#0e1117] via-[#141923] to-[#1c2333] rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800 mb-8 relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#d4f93a]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex flex-wrap items-center gap-2.5 mb-3">
                    <!-- Status Badge -->
                    @php
                        $statusStyles = [
                            'CREATED'    => 'bg-slate-800 text-slate-200 border-slate-700',
                            'BOOKED'     => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                            'PICKED_UP'  => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                            'IN_TRANSIT' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                            'DELIVERED'  => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                            'CANCELLED'  => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                        ];
                    @endphp
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusStyles[$shipment->status] ?? 'bg-slate-800 text-slate-300' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ in_array($shipment->status, ['DELIVERED', 'IN_TRANSIT']) ? 'bg-emerald-400' : ($shipment->status === 'CANCELLED' ? 'bg-rose-400' : 'bg-[#d4f93a]') }}"></span>
                        <span>STATUS: {{ $shipment->status }}</span>
                    </span>

                    <!-- Transport Mode -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 text-white border border-white/10">
                        {{ $shipment->transport_mode }}
                    </span>

                    <!-- Carrier -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/5 text-slate-300 border border-white/5">
                        {{ $shipment->carrier?->name }} ({{ $shipment->carrier?->code }})
                    </span>

                    <!-- Delay status badge if delayed -->
                    @if($delayAnalysis['is_delayed'])
                        <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30">
                            <span>DELAY {{ $delayAnalysis['delay_severity'] }} (+{{ $delayAnalysis['delay_hours'] }} Jam)</span>
                        </span>
                    @else
                        <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span>ON-TIME</span>
                        </span>
                    @endif
                </div>

                <div class="flex items-baseline space-x-3">
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight font-mono text-white">
                        {{ $shipment->tracking_number }}
                    </h1>
                </div>

                <p class="text-sm text-slate-300 mt-2 flex flex-wrap items-center gap-x-2 gap-y-1">
                    <span class="font-medium text-white">{{ $shipment->cargo_type }}</span>
                    <span class="text-slate-500">&bull;</span>
                    <span>Total Berat: <strong class="text-white">{{ number_format($shipment->weight_kg, 2) }} kg</strong></span>
                    @if($shipment->volume_cbm)
                        <span class="text-slate-500">&bull;</span>
                        <span>Volume: <strong class="text-white">{{ number_format($shipment->volume_cbm, 2) }} cbm</strong></span>
                    @endif
                </p>
            </div>

            <!-- Route Summary Pill on Header -->
            <div class="bg-black/50 backdrop-blur-md rounded-2xl p-4 border border-white/10 shrink-0">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Rute & Hub Kargo</div>
                <div class="flex items-center space-x-3 text-sm">
                    <div>
                        <span class="text-xs font-mono font-bold text-[#d4f93a] block">[{{ $shipment->originLocation?->code }}]</span>
                        <span class="text-white font-medium text-xs">{{ $shipment->originLocation?->name }}</span>
                    </div>
                    <div class="text-slate-400">&rarr;</div>
                    <div>
                        <span class="text-xs font-mono font-bold text-[#d4f93a] block">[{{ $shipment->destinationLocation?->code }}]</span>
                        <span class="text-white font-medium text-xs">{{ $shipment->destinationLocation?->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 1: Lokasi Terkini (Current Location) & Analisis ETA & Delay Deterministik -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <!-- Blok 1: Lokasi Terkini (Current Location) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 relative overflow-hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div class="h-8 w-8 rounded-xl bg-slate-900 text-[#d4f93a] flex items-center justify-center font-bold">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Lokasi Terkini (Current Location)</h2>
                        <p class="text-[11px] text-slate-500">Posisi geospasial aktual berdasarkan checkpoint pelacakan terakhir.</p>
                    </div>
                </div>
                @if($currentLocation && !($currentLocation['is_fallback'] ?? false))
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                        Live Checkpoint
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                        Origin Hub
                    </span>
                @endif
            </div>

            @if($currentLocation)
                <div class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-base font-extrabold text-slate-900">
                                {{ $currentLocation['location_name'] }}
                            </div>
                            <div class="text-xs text-slate-500 flex items-center space-x-1.5 mt-0.5">
                                <span class="font-mono font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded text-[10px]">{{ $currentLocation['location_code'] }}</span>
                                <span>&bull;</span>
                                <span>{{ $currentLocation['country'] }} ({{ $currentLocation['type'] }})</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-900 text-[#d4f93a] text-xs font-mono font-bold">
                                {{ $currentLocation['status'] }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Koordinat Geospasial</span>
                            <span class="font-mono font-semibold text-slate-800">
                                @if($currentLocation['latitude'] !== null && $currentLocation['longitude'] !== null)
                                    {{ number_format((float)$currentLocation['latitude'], 4) }}°, {{ number_format((float)$currentLocation['longitude'], 4) }}°
                                @else
                                    <span class="text-slate-400 font-sans italic">Belum tersedia</span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Waktu Pembaruan Terakhir</span>
                            <span class="font-medium text-slate-800">
                                {{ $currentLocation['event_time'] ? \Carbon\Carbon::parse($currentLocation['event_time'])->format('d M Y, H:i') . ' WIB' : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="text-xs text-slate-600 bg-slate-50/50 p-2.5 rounded-lg border border-slate-100/80">
                        <span class="font-semibold text-slate-700">Keterangan Checkpoint:</span>
                        {{ $currentLocation['description'] }}
                    </div>
                </div>
            @else
                <div class="py-8 text-center text-slate-400 text-xs">
                    Informasi koordinat lokasi terkini belum tersedia.
                </div>
            @endif
        </div>

        <!-- Blok 2: Analisis ETA & Deteksi Keterlambatan (Rule-Based) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 relative overflow-hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div class="h-8 w-8 rounded-xl bg-slate-900 text-[#d4f93a] flex items-center justify-center font-bold">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Analisis ETA & Keterlambatan (Rule-Based)</h2>
                        <p class="text-[11px] text-slate-500">Evaluasi deterministik berbasis selisih jadwal dan threshold aktif.</p>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono uppercase {{ $delayAnalysis['is_delayed'] ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                    {{ $delayAnalysis['is_delayed'] ? 'DELAY DETECTED' : 'ON-TIME' }}
                </span>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-center">
                    
                    <!-- Expected Arrival -->
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Target Expected</span>
                        <span class="text-xs font-bold text-slate-800 block">
                            {{ $shipment->expected_arrival ? $shipment->expected_arrival->format('d/m/Y H:i') : '-' }}
                        </span>
                    </div>

                    <!-- Estimated Arrival -->
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Estimasi Aktual</span>
                        <span class="text-xs font-bold text-slate-800 block">
                            {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d/m/Y H:i') : '-' }}
                        </span>
                    </div>

                    <!-- Delay Duration -->
                    <div class="p-2.5 rounded-xl {{ $delayAnalysis['delay_hours'] > 0 ? 'bg-rose-50 border border-rose-100' : 'bg-emerald-50 border border-emerald-100' }}">
                        <span class="text-[10px] font-bold {{ $delayAnalysis['delay_hours'] > 0 ? 'text-rose-600' : 'text-emerald-600' }} uppercase block mb-1">Durasi Delay</span>
                        <span class="text-xs font-extrabold font-mono {{ $delayAnalysis['delay_hours'] > 0 ? 'text-rose-700' : 'text-emerald-700' }} block">
                            {{ $delayAnalysis['delay_hours'] > 0 ? '+' . $delayAnalysis['delay_hours'] . ' Jam' : '0 Jam' }}
                        </span>
                    </div>

                    <!-- Severity Badge -->
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Severity</span>
                        <span class="text-xs font-bold uppercase inline-block px-2 py-0.5 rounded
                            {{ $delayAnalysis['delay_severity'] === 'HIGH' ? 'bg-rose-100 text-rose-800' : 
                              ($delayAnalysis['delay_severity'] === 'MEDIUM' ? 'bg-orange-100 text-orange-800' : 
                              ($delayAnalysis['delay_severity'] === 'LOW' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800')) }}">
                            {{ $delayAnalysis['delay_severity'] }}
                        </span>
                    </div>

                </div>

                <!-- Alasan Deterministik & Threshold Aktif -->
                <div class="p-3.5 rounded-xl bg-slate-50/90 border border-slate-100 text-xs space-y-1.5">
                    <div class="flex items-center justify-between text-[11px] text-slate-500 font-mono">
                        <span>Threshold Sistem Aktif:</span>
                        <span class="font-bold text-slate-700">
                            LOW: &le; {{ $delayAnalysis['low_max_hours'] }}j &bull; MEDIUM: &gt; {{ $delayAnalysis['low_max_hours'] }}–{{ $delayAnalysis['high_min_hours'] }}j &bull; HIGH: &gt; {{ $delayAnalysis['high_min_hours'] }}j
                        </span>
                    </div>
                    <p class="text-slate-700 leading-relaxed font-sans text-xs pt-1 border-t border-slate-200/60">
                        <strong class="text-slate-900 font-semibold">Penjelasan Aturan:</strong> {{ $delayAnalysis['reason'] }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Left 2 Cols: Items, Exceptions History, Tracking Timeline -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Card 1: Manifest Muatan Kargo -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Manifest Muatan Kargo ({{ $shipment->items->count() }} Item)</span>
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Nama Item</th>
                                <th class="py-3 px-4 text-center">Kuantitas</th>
                                <th class="py-3 px-4 text-right">Berat (Kg)</th>
                                <th class="py-3 px-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($shipment->items as $item)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-4 font-semibold text-slate-900">{{ $item->item_name }}</td>
                                    <td class="py-3 px-4 text-center font-bold text-slate-700">{{ $item->quantity }}</td>
                                    <td class="py-3 px-4 text-right font-mono text-slate-800">{{ number_format($item->weight_kg, 2) }}</td>
                                    <td class="py-3 px-4 text-slate-500 text-xs">{{ $item->description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 text-xs">Belum ada item muatan terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card 2: Riwayat Exception Pengiriman Ini (Read-only untuk Admin, Operator & Customer) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="h-6 w-6 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs">
                            !
                        </div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                            Riwayat Exception Pengiriman Ini ({{ $shipment->exceptions->count() }} Insiden)
                        </h2>
                    </div>
                    @if(auth()->user()->isAdmin() || auth()->user()->isOperator())
                        <a href="#form-exception" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                            + Laporkan Kendala Baru
                        </a>
                    @endif
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($shipment->exceptions as $exc)
                        <div class="p-5 hover:bg-slate-50/50 transition space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-xs text-slate-900">{{ $exc->type }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        {{ $exc->severity === 'HIGH' ? 'bg-rose-100 text-rose-800' : 
                                          ($exc->severity === 'MEDIUM' ? 'bg-orange-100 text-orange-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $exc->severity }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $exc->status === 'RESOLVED' ? 'bg-emerald-100 text-emerald-800' : 
                                          ($exc->status === 'INVESTIGATING' ? 'bg-sky-100 text-sky-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $exc->status }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-400 font-mono">
                                    Deteksi: {{ $exc->detected_at ? $exc->detected_at->format('d/m/Y H:i') : '-' }} WIB
                                </div>
                            </div>

                            <p class="text-xs text-slate-700 leading-relaxed">
                                {{ $exc->description }}
                            </p>

                            @if($exc->delay_impact_hours > 0)
                                <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-rose-50 border border-rose-200/60 text-xs text-rose-700 font-medium">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Dampak Keterlambatan: <strong>+{{ $exc->delay_impact_hours }} Jam</strong> ditambahkan ke estimasi kedatangan.</span>
                                </div>
                            @endif

                            @if($exc->resolution_note)
                                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 leading-relaxed">
                                    <div class="font-bold text-emerald-800 mb-0.5 flex items-center space-x-1.5">
                                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Catatan Resolusi (Penyelesaian):</span>
                                    </div>
                                    <p>{{ $exc->resolution_note }}</p>
                                    <div class="mt-1 text-[10px] text-emerald-700">
                                        Diselesaikan oleh: <span class="font-semibold">{{ $exc->resolver?->name ?? 'Admin' }}</span> 
                                        pada {{ $exc->resolved_at ? $exc->resolved_at->format('d M Y, H:i') : '-' }} WIB
                                    </div>
                                </div>
                            @endif

                            <div class="text-[10px] text-slate-400">
                                Dilaporkan oleh: <span class="font-semibold text-slate-600">{{ $exc->reporter?->name ?? 'Sistem' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-xs">
                            Tidak ada insiden atau kendala operasional yang tercatat pada pengiriman ini. Seluruh perjalanan berjalan lancar.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Card 3: Vertical Timeline of Tracking Events (Normal vs Exception) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6 pb-2 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Riwayat Kronologis Perjalanan (Tracking Timeline)</span>
                    </h2>
                    <span class="text-xs text-slate-400 font-mono">{{ $shipment->trackingEvents->count() }} Event</span>
                </div>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @forelse($shipment->trackingEvents as $event)
                        <div class="relative group">
                            
                            <!-- Bullet Indicator: Beda jelas jika is_exception = true -->
                            @if($event->is_exception)
                                <div class="absolute -left-[27px] top-1.5 h-4 w-4 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] font-bold ring-4 ring-rose-200">
                                    !
                                </div>
                            @else
                                <div class="absolute -left-[27px] top-1.5 h-3.5 w-3.5 rounded-full border-2 border-white 
                                    {{ $loop->last ? 'bg-[#0e1117] ring-4 ring-[#d4f93a]/30' : 'bg-slate-400' }}"></div>
                            @endif

                            <!-- Event Box: Styling Mencolok Jika Exception -->
                            <div class="rounded-xl p-4 transition
                                {{ $event->is_exception ? 'bg-rose-50/90 border-2 border-rose-200 shadow-sm' : 'bg-slate-50/80 border border-slate-100 hover:border-slate-200' }}">
                                
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($event->is_exception)
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-rose-600 text-white shadow-xs">
                                                EXCEPTION
                                            </span>
                                            <span class="text-xs font-bold text-rose-900">
                                                {{ $event->status }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                                {{ $event->status === 'DELIVERED' ? 'bg-emerald-100 text-emerald-800' : 
                                                  ($event->status === 'CANCELLED' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-800') }}">
                                                {{ $event->status }}
                                            </span>
                                        @endif

                                        @if($event->location)
                                            <span class="text-xs font-semibold text-slate-700">
                                                {{ $event->location->name }} ({{ $event->location->code }})
                                            </span>
                                        @endif

                                        @if($event->latitude !== null && $event->longitude !== null)
                                            <span class="text-[10px] font-mono text-slate-400">
                                                [{{ number_format((float)$event->latitude, 3) }}, {{ number_format((float)$event->longitude, 3) }}]
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-[11px] {{ $event->is_exception ? 'text-rose-700 font-bold' : 'text-slate-400' }} font-mono">
                                        {{ $event->event_time ? $event->event_time->format('d M Y, H:i') . ' WIB' : '-' }}
                                    </div>
                                </div>

                                <p class="text-xs {{ $event->is_exception ? 'text-rose-950 font-medium' : 'text-slate-700' }} leading-relaxed">
                                    {{ $event->description }}
                                </p>

                                <div class="mt-2 text-[10px] text-slate-400">
                                    Dicatat oleh: <span class="font-medium text-slate-600">{{ $event->creator?->name ?? 'Sistem' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">Belum ada peristiwa pelacakan tercatat.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Action Forms & Meta Info -->
        <div class="space-y-6">

            @if((auth()->user()->isAdmin() || auth()->user()->isOperator()) && !in_array($shipment->status, ['DELIVERED', 'CANCELLED']))
                
                <!-- Action Form 1: Tambah Tracking Event (Checkpoint) -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <span class="h-2 w-2 rounded-full bg-[#d4f93a]"></span>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tambah Checkpoint Tracking</h3>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono">Event Baru</span>
                    </div>

                    <form method="POST" action="{{ route('shipments.tracking.store', $shipment) }}" class="space-y-3.5">
                        @csrf

                        <!-- Lokasi -->
                        <div>
                            <label for="track_location_id" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Pilih Lokasi Checkpoint</label>
                            <select name="location_id" id="track_location_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                                <option value="">-- Pilih Hub / Lokasi (Opsional) --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->name }} ({{ $loc->code }} - {{ $loc->country }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-0.5">Koordinat lat/lng otomatis diambil jika master lokasi dipilih.</p>
                        </div>

                        <!-- Status Transisi (Opsional) -->
                        <div>
                            <label for="track_status" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Status Kargo Saat Ini</label>
                            <select name="status" id="track_status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                                <option value="{{ $shipment->status }}" selected>Tetap: {{ $shipment->status }} (Hanya Checkpoint)</option>
                                @foreach($allowedNextStatuses as $nextSt)
                                    <option value="{{ $nextSt }}">Perbarui ke: {{ $nextSt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Waktu Event -->
                        <div>
                            <label for="track_event_time" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Waktu Checkpoint (WIB)</label>
                            <input type="datetime-local" 
                                   name="event_time" 
                                   id="track_event_time" 
                                   value="{{ old('event_time', now()->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="track_description" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Deskripsi Checkpoint <span class="text-rose-500">*</span></label>
                            <textarea name="description" 
                                      id="track_description" 
                                      rows="2" 
                                      required
                                      placeholder="Contoh: Kargo telah tiba di checkpoint transit..."
                                      class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">{{ old('description') }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-xs font-bold transition shadow-sm">
                            Simpan Checkpoint Tracking
                        </button>
                    </form>
                </div>

                <!-- Action Form 2: Laporkan Exception (Kendala Operasional) -->
                <div id="form-exception" class="bg-white rounded-2xl border border-rose-200 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-rose-100">
                        <div class="flex items-center space-x-2">
                            <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                            <h3 class="text-xs font-bold text-rose-950 uppercase tracking-wider">Laporkan Exception / Kendala</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 uppercase">Alert</span>
                    </div>

                    <form method="POST" action="{{ route('shipments.exceptions.store', $shipment) }}" class="space-y-3.5">
                        @csrf

                        <!-- Tipe Exception -->
                        <div>
                            <label for="exc_type" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tipe Kendala <span class="text-rose-500">*</span></label>
                            <select name="type" id="exc_type" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400">
                                <option value="">-- Pilih Jenis Kendala --</option>
                                <option value="CUSTOMS_HOLD">CUSTOMS_HOLD (Tertahan Bea Cukai)</option>
                                <option value="WEATHER_DELAY">WEATHER_DELAY (Cuaca Buruk / Badai)</option>
                                <option value="PORT_CONGESTION">PORT_CONGESTION (Kepadatan Pelabuhan/Bandara)</option>
                                <option value="DAMAGED">DAMAGED (Kerusakan Kargo)</option>
                                <option value="MISROUTED">MISROUTED (Kargo Salah Rute)</option>
                                <option value="DOCUMENTATION_ISSUE">DOCUMENTATION_ISSUE (Masalah Dokumen)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <!-- Severity -->
                            <div>
                                <label for="exc_severity" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Severity <span class="text-rose-500">*</span></label>
                                <select name="severity" id="exc_severity" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400">
                                    <option value="LOW">LOW</option>
                                    <option value="MEDIUM" selected>MEDIUM</option>
                                    <option value="HIGH">HIGH</option>
                                </select>
                            </div>

                            <!-- Delay Impact Hours -->
                            <div>
                                <label for="exc_delay_hours" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Dampak Delay</label>
                                <div class="relative">
                                    <input type="number" 
                                           name="delay_impact_hours" 
                                           id="exc_delay_hours" 
                                           min="0" 
                                           max="720"
                                           value="{{ old('delay_impact_hours', 0) }}"
                                           class="w-full pl-3 pr-10 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400">
                                    <span class="absolute right-3 top-2 text-[10px] font-bold text-slate-400">Jam</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lokasi Insiden -->
                        <div>
                            <label for="exc_location_id" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Lokasi Insiden</label>
                            <select name="location_id" id="exc_location_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400">
                                <option value="">-- Lokasi Terkait (Opsional) --</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="exc_description" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Rincian Kendala <span class="text-rose-500">*</span></label>
                            <textarea name="description" 
                                      id="exc_description" 
                                      rows="2" 
                                      required
                                      placeholder="Jelaskan kondisi kendala, alasan penahanan, atau kondisi cuaca..."
                                      class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-400">{{ old('description') }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm">
                            Laporkan Insiden Exception
                        </button>
                    </form>
                </div>

                <!-- Action Form 3: State Machine Status Transition -->
                <div class="bg-white rounded-2xl border-2 border-slate-900 shadow-md p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded bg-[#0e1117] text-[#d4f93a]">
                            State Machine
                        </span>
                        <span class="text-xs text-slate-400 font-medium">Status: <strong>{{ $shipment->status }}</strong></span>
                    </div>

                    <h3 class="text-sm font-bold text-slate-900 mb-1">Transisi Status Operasional</h3>
                    <p class="text-xs text-slate-500 mb-4">
                        Pembaruan status wajib mengikuti State Machine terstruktur dan otomatis tercatat ke audit log.
                    </p>

                    <form method="POST" action="{{ route('shipments.status.update', $shipment) }}" class="space-y-3.5">
                        @csrf

                        <div>
                            <label for="new_status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Status Berikutnya yang Sah <span class="text-rose-500">*</span>
                            </label>
                            <select name="status" id="new_status" required
                                    class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                                <option value="">-- Pilih Status Berikutnya --</option>
                                @foreach($allowedNextStatuses as $nextStatus)
                                    <option value="{{ $nextStatus }}">{{ $nextStatus }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="note" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Catatan Transisi (Opsional)
                            </label>
                            <textarea name="note" id="note" rows="2" placeholder="Contoh: Kargo telah diserahterimakan..."
                                      class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-xs font-bold shadow-sm transition">
                            Simpan Transisi Status &rarr;
                        </button>
                    </form>
                </div>

            @elseif(in_array($shipment->status, ['DELIVERED', 'CANCELLED']))
                <!-- Status Final Notice -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm text-slate-700 text-xs">
                    <div class="flex items-center space-x-2 font-bold mb-1.5 {{ $shipment->status === 'DELIVERED' ? 'text-emerald-700' : 'text-rose-700' }}">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Status Pengiriman Final (Terminal)</span>
                    </div>
                    Pengiriman ini telah berstatus <strong>{{ $shipment->status }}</strong>. Seluruh proses operasional telah selesai dan mutasi data dikunci.
                </div>
            @endif

            <!-- Stakeholder Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4 text-xs">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider pb-2 border-b border-slate-100">
                    Informasi Pihak Terkait
                </div>

                <div>
                    <span class="text-slate-400 block mb-0.5">Pemilik Kargo (Customer):</span>
                    <span class="font-bold text-slate-900 text-sm block">{{ $shipment->customer?->name }}</span>
                    <span class="text-slate-500">{{ $shipment->customer?->company_name ?? '-' }}</span>
                    <span class="text-slate-400 block font-mono">{{ $shipment->customer?->email }}</span>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <span class="text-slate-400 block mb-0.5">Carrier Logistik:</span>
                    <span class="font-bold text-slate-900 text-sm block">{{ $shipment->carrier?->name }}</span>
                    <span class="text-slate-500">Kode: {{ $shipment->carrier?->code }}</span>
                    <span class="text-slate-400 block">{{ $shipment->carrier?->contact_email ?? '-' }}</span>
                </div>

                <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400">
                    Dibuat pada: <strong class="text-slate-600">{{ $shipment->created_at->format('d M Y, H:i') }}</strong> oleh <strong class="text-slate-600">{{ $shipment->creator?->name }}</strong>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
