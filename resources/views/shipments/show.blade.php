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
    <div class="bg-gradient-to-br from-[#7B9581] via-[#556E5C] to-[#425848] rounded-[24px] p-6 sm:p-8 text-white shadow-sm mb-8 relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex flex-wrap items-center gap-2.5 mb-3">
                    <!-- Status Badge -->
                    @php
                        $statusStyles = [
                            'CREATED'    => 'bg-white/20 text-white border-white/20',
                            'BOOKED'     => 'bg-white/20 text-white border-white/20',
                            'PICKED_UP'  => 'bg-[#F7EFE1] text-[#9A6721] border-[#E6D8BF]',
                            'IN_TRANSIT' => 'bg-[#F7EFE1] text-[#9A6721] border-[#E6D8BF]',
                            'DELIVERED'  => 'bg-[#E6F0E9] text-[#2F6846] border-[#C8DACD]',
                            'CANCELLED'  => 'bg-[#F9E6E6] text-[#B84242] border-[#F0C9C9]',
                        ];
                    @endphp
                    <span class="inline-flex items-center space-x-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusStyles[$shipment->status] ?? 'bg-white/20 text-white border-white/20' }}">
                        <span class="h-2 w-2 rounded-full {{ in_array($shipment->status, ['DELIVERED', 'IN_TRANSIT']) ? 'bg-white' : ($shipment->status === 'CANCELLED' ? 'bg-[#B84242]' : 'bg-white') }}"></span>
                        <span>STATUS: {{ $shipment->status }}</span>
                    </span>

                    <!-- Transport Mode -->
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/15 text-white border border-white/10">
                        {{ $shipment->transport_mode }}
                    </span>

                    <!-- Carrier -->
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-semibold bg-white/10 text-white border border-white/10">
                        {{ $shipment->carrier?->name }} ({{ $shipment->carrier?->code }})
                    </span>

                    <!-- Delay status badge if delayed -->
                    @if($delayAnalysis['is_delayed'])
                        <span class="inline-flex items-center space-x-1 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#F9E6E6] text-[#B84242] border border-[#F0C9C9]">
                            <span>DELAY {{ $delayAnalysis['delay_severity'] }} (+{{ $delayAnalysis['delay_hours'] }} Jam)</span>
                        </span>
                    @else
                        <span class="inline-flex items-center space-x-1 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#E6F0E9] text-[#2F6846] border border-[#C8DACD]">
                            <span>ON-TIME</span>
                        </span>
                    @endif
                </div>

                <div class="flex items-baseline space-x-3">
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight font-mono text-white">
                        {{ $shipment->tracking_number }}
                    </h1>
                </div>

                <p class="text-sm text-white/90 mt-2 flex flex-wrap items-center gap-x-2 gap-y-1">
                    <span class="font-medium text-white">{{ $shipment->cargo_type }}</span>
                    <span class="text-white/60">&bull;</span>
                    <span>Total Berat: <strong class="text-white">{{ number_format($shipment->weight_kg, 2) }} kg</strong></span>
                    @if($shipment->volume_cbm)
                        <span class="text-white/60">&bull;</span>
                        <span>Volume: <strong class="text-white">{{ number_format($shipment->volume_cbm, 2) }} cbm</strong></span>
                    @endif
                </p>
            </div>

            <!-- Route Summary Pill on Header -->
            <div class="bg-black/25 backdrop-blur-md rounded-[20px] p-4 border border-white/15 shrink-0">
                <div class="text-[11px] font-bold text-white/75 uppercase tracking-wider mb-2">Rute & Hub Kargo</div>
                <div class="flex items-center space-x-3 text-sm">
                    <div>
                        <span class="text-xs font-mono font-bold text-white block">[{{ $shipment->originLocation?->code }}]</span>
                        <span class="text-white/90 font-medium text-xs">{{ $shipment->originLocation?->name }}</span>
                    </div>
                    <div class="text-white/60">&rarr;</div>
                    <div>
                        <span class="text-xs font-mono font-bold text-white block">[{{ $shipment->destinationLocation?->code }}]</span>
                        <span class="text-white/90 font-medium text-xs">{{ $shipment->destinationLocation?->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section: Peta Rute & Pelacakan Geospasial (OpenStreetMap) -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden mb-8">
        <div class="p-5 sm:p-6 border-b border-[#DFE1D8]/60 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#556E5C] animate-pulse"></span>
                    <h2 class="text-sm font-bold text-[#1C1E1B] uppercase tracking-wider">
                        Peta Rute & Pelacakan Geospasial (OpenStreetMap)
                    </h2>
                </div>
                <p class="text-xs text-[#6F756C] mt-0.5">
                    Visualisasi spasial perjalanan kargo dari titik asal (Origin), riwayat checkpoint, hingga destinasi akhir.
                </p>
            </div>

            <!-- Legend Pelacakan Geospasial -->
            <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#E5ECE2] text-[#243329] border border-[#C8D3BE]">
                    <span class="h-2 w-2 rounded-full bg-[#243329]"></span>
                    <span>Origin Hub</span>
                </span>
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#E6F0E9] text-[#2F6846]">
                    <span class="h-2 w-2 rounded-full bg-[#2F6846]"></span>
                    <span>Destination Hub</span>
                </span>
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#F7EFE1] text-[#9A6721]">
                    <span class="h-2 w-2 rounded-full bg-[#D4AF77]"></span>
                    <span>Checkpoint</span>
                </span>
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#F9E6E6] text-[#B84242]">
                    <span class="h-2 w-2 rounded-full bg-[#B84242]"></span>
                    <span>Exception</span>
                </span>
            </div>
        </div>

        <!-- Wadah Peta Leaflet.js -->
        <div class="relative">
            <div id="shipment-map" class="z-10 w-full h-[300px] md:h-[400px]"></div>
            
            @php
                $hasOriginCoord = $mapData['origin']['latitude'] !== null && $mapData['origin']['longitude'] !== null;
                $hasDestCoord = $mapData['destination']['latitude'] !== null && $mapData['destination']['longitude'] !== null;
                $hasEventCoord = count($mapData['events']) > 0;
            @endphp
            @if(!$hasOriginCoord && !$hasDestCoord && !$hasEventCoord)
                <div class="absolute inset-0 z-20 bg-slate-50/95 backdrop-blur-xs flex flex-col items-center justify-center p-6 text-center">
                    <div class="h-12 w-12 rounded-2xl bg-slate-200 text-slate-500 flex items-center justify-center mb-3">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <div class="text-sm font-bold text-slate-800">Koordinat Geospasial Belum Lengkap</div>
                    <p class="text-xs text-slate-500 max-w-md mt-1">
                        Informasi titik koordinat latitude dan longitude belum tersedia pada lokasi origin, destination, maupun checkpoint kargo ini.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Row 1: Lokasi Terkini, Cuaca Terkini (External API), dan Analisis ETA (Rule-Based) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <!-- Blok 1: Lokasi Terkini (Current Location) -->
        <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6 relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="h-8 w-8 rounded-full bg-[#EDF1EC] text-[#4E6756] border border-[#DCE5DA] flex items-center justify-center font-bold">
                            <svg class="h-4 w-4 text-[#4E6756]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Lokasi Terkini</h2>
                            <p class="text-[11px] text-slate-500">Posisi geospasial aktual checkpoint.</p>
                        </div>
                    </div>
                    @if($currentLocation && !($currentLocation['is_fallback'] ?? false))
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#E6F0E9] text-[#2F6846] border border-[#CFE2D5] uppercase font-mono">
                            LIVE
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase font-mono">
                            ORIGIN
                        </span>
                    @endif
                </div>

                @if($currentLocation)
                    <div class="space-y-3.5">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-sm font-extrabold text-slate-900 leading-snug">
                                    {{ $currentLocation['location_name'] }}
                                </div>
                                <div class="text-xs text-slate-500 flex items-center space-x-1.5 mt-0.5">
                                    <span class="font-mono font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded text-[10px]">{{ $currentLocation['location_code'] }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $currentLocation['country'] }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $locStatus = $currentLocation['status'] ?? '';
                                    if (in_array($locStatus, ['DELIVERED', 'ON TIME', 'LIVE'])) {
                                        $locStatusStyle = 'bg-[#E6F0E9] text-[#2F6846] border-[#CFE2D5]';
                                    } elseif (in_array($locStatus, ['DELAYED', 'CANCELLED', 'HIGH'])) {
                                        $locStatusStyle = 'bg-[#F9E6E6] text-[#B84242] border-[#F0D0D0]';
                                    } else {
                                        $locStatusStyle = 'bg-[#F7EFE1] text-[#9A6721] border-[#EADBC0]';
                                    }
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-mono font-semibold border {{ $locStatusStyle }}">
                                    {{ $currentLocation['status'] }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Koordinat</span>
                                <span class="font-mono font-semibold text-slate-800 text-[11px]">
                                    @if($currentLocation['latitude'] !== null && $currentLocation['longitude'] !== null)
                                        {{ number_format((float)$currentLocation['latitude'], 4) }}°, {{ number_format((float)$currentLocation['longitude'], 4) }}°
                                    @else
                                        <span class="text-slate-400 font-sans italic">Belum ada</span>
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Pembaruan</span>
                                <span class="font-medium text-slate-800 text-[11px]">
                                    {{ $currentLocation['event_time'] ? \Carbon\Carbon::parse($currentLocation['event_time'])->format('d M, H:i') . ' WIB' : '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="text-xs text-slate-600 bg-slate-50/50 p-2.5 rounded-lg border border-slate-100/80">
                            <span class="font-semibold text-slate-700">Keterangan:</span>
                            {{ $currentLocation['description'] }}
                        </div>
                    </div>
                @else
                    <div class="py-8 text-center text-slate-400 text-xs">
                        Informasi koordinat lokasi terkini belum tersedia.
                    </div>
                @endif
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[10px] text-slate-400">
                Pembaruan checkpoint tervalidasi oleh sistem TrackingService.
            </div>
        </div>

        <!-- Blok 2: Informasi Cuaca Lokasi Terkini (External API) -->
        <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6 relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="h-8 w-8 rounded-full bg-[#EDF1EC] text-[#4E6756] border border-[#DCE5DA] flex items-center justify-center font-bold">
                            <svg class="h-4 w-4 text-[#4E6756]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Cuaca Lokasi Terkini</h2>
                            <p class="text-[11px] text-slate-500">Kondisi meteorologi aktual di sekitar titik kargo.</p>
                        </div>
                    </div>
                    @if($weatherData['available'])
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#E9EAE4] text-[#243329] border border-[#DFE1D8] uppercase font-mono">
                            OPEN-METEO
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase font-mono">
                            OFFLINE
                        </span>
                    @endif
                </div>

                @if($weatherData['available'])
                    <div class="space-y-3.5">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Titik Pantau Cuaca</span>
                                <div class="text-sm font-extrabold text-slate-900 leading-snug">
                                    {{ $weatherData['location_label'] }}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 rounded-full bg-[#E9EAE4] text-[#243329] text-xs font-bold border border-[#DFE1D8]">
                                    {{ $weatherData['condition'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Metrik Utama Cuaca -->
                        <div class="grid grid-cols-3 gap-2 p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Suhu</span>
                                <span class="text-base font-extrabold font-mono text-slate-900">
                                    {{ number_format($weatherData['temperature_c'], 1) }}°C
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Kelembapan</span>
                                <span class="text-base font-extrabold font-mono text-slate-900">
                                    {{ $weatherData['humidity_pct'] !== null ? $weatherData['humidity_pct'] . '%' : '-' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Angin</span>
                                <span class="text-base font-extrabold font-mono text-slate-900">
                                    {{ $weatherData['wind_speed_kmh'] !== null ? number_format($weatherData['wind_speed_kmh'], 1) : '-' }} <span class="text-[9px] font-sans font-medium text-slate-500">km/j</span>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-500 px-0.5">
                            <span>Observasi: <strong class="text-slate-700">{{ $weatherData['observed_at'] ?? '-' }}</strong></span>
                            <span class="font-mono text-[10px] text-slate-400">{{ $weatherData['source'] }}</span>
                        </div>
                    </div>
                @else
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 space-y-1.5">
                        <div class="font-semibold text-slate-800 flex items-center space-x-1.5">
                            <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Informasi Cuaca Tidak Dapat Dimuat</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            {{ $weatherData['error'] ?? 'Layanan Open-Meteo API tidak merespons atau koordinat pengiriman belum lengkap.' }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Catatan Disclaimer WAJIB -->
            <div class="mt-4 pt-3 border-t border-slate-100">
                <p class="text-[10px] text-slate-400 leading-relaxed">
                    * Data cuaca dari layanan eksternal ini hanya bersifat informasi operasional dan tidak digunakan sebagai klaim prediksi keterlambatan (delay).
                </p>
            </div>
        </div>

        <!-- Blok 3: Analisis ETA & Deteksi Keterlambatan (Rule-Based) -->
        <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6 relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="h-8 w-8 rounded-full bg-[#EDF1EC] text-[#4E6756] border border-[#DCE5DA] flex items-center justify-center font-bold">
                            <svg class="h-4 w-4 text-[#4E6756]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Analisis ETA & Delay</h2>
                            <p class="text-[11px] text-slate-500">Evaluasi deterministik threshold.</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono uppercase border {{ $delayAnalysis['is_delayed'] ? 'bg-[#F9E6E6] text-[#B84242] border-[#F0D0D0]' : 'bg-[#E6F0E9] text-[#2F6846] border-[#CFE2D5]' }}">
                        {{ $delayAnalysis['is_delayed'] ? 'DELAY DETECTED' : 'ON-TIME' }}
                    </span>
                </div>

                <div class="space-y-3.5">
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Target</span>
                            <span class="text-[11px] font-bold text-slate-800 block">
                                {{ $shipment->expected_arrival ? $shipment->expected_arrival->format('d/m/Y H:i') : '-' }}
                            </span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Estimasi</span>
                            <span class="text-[11px] font-bold text-slate-800 block">
                                {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d/m/Y H:i') : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="p-2 rounded-xl {{ $delayAnalysis['delay_hours'] > 0 ? 'bg-rose-50 border border-rose-100' : 'bg-emerald-50 border border-emerald-100' }}">
                            <span class="text-[10px] font-bold {{ $delayAnalysis['delay_hours'] > 0 ? 'text-rose-600' : 'text-emerald-600' }} uppercase block mb-0.5">Durasi Delay</span>
                            <span class="text-xs font-extrabold font-mono {{ $delayAnalysis['delay_hours'] > 0 ? 'text-rose-700' : 'text-emerald-700' }} block">
                                {{ $delayAnalysis['delay_hours'] > 0 ? '+' . $delayAnalysis['delay_hours'] . ' Jam' : '0 Jam' }}
                            </span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Severity</span>
                            <span class="text-xs font-semibold uppercase inline-block px-2.5 py-0.5 rounded-full border
                                {{ $delayAnalysis['delay_severity'] === 'HIGH' ? 'bg-[#F9E6E6] text-[#B84242] border-[#F0D0D0]' : 
                                  ($delayAnalysis['delay_severity'] === 'MEDIUM' ? 'bg-[#F7EFE1] text-[#9A6721] border-[#EADBC0]' : 
                                  ($delayAnalysis['delay_severity'] === 'LOW' ? 'bg-[#F7EFE1] text-[#9A6721] border-[#EADBC0]' : 'bg-[#E6F0E9] text-[#2F6846] border-[#CFE2D5]')) }}">
                                {{ $delayAnalysis['delay_severity'] }}
                            </span>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-50/90 border border-slate-100 text-[11px] text-slate-600 leading-relaxed">
                        <strong class="text-slate-800">Aturan:</strong> {{ $delayAnalysis['reason'] }}
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[10px] text-slate-400">
                Threshold: LOW &le; {{ $delayAnalysis['low_max_hours'] }}j &bull; HIGH &gt; {{ $delayAnalysis['high_min_hours'] }}j
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Left 2 Cols: Items, Exceptions History, Tracking Timeline -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Card 1: Manifest Muatan Kargo -->
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
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
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
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
                        <a href="#form-exception" class="text-xs font-bold text-[#556E5C] hover:text-[#425848] hover:underline">
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
                                        {{ $exc->status === 'RESOLVED' ? 'bg-[#E9EAE4] text-[#243329] border border-[#C8D3BE]' : 
                                          ($exc->status === 'INVESTIGATING' ? 'bg-[#DFE1D8] text-[#3E5245] border border-[#C8D3BE]' : 'bg-[#B96A55]/15 text-[#B96A55] border border-[#B96A55]/30') }}">
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
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
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
                                    {{ $loop->last ? 'bg-[#556E5C] ring-4 ring-[#556E5C]/20' : 'bg-slate-400' }}"></div>
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
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider border
                                                {{ $event->status === 'DELIVERED' ? 'bg-[#E6F0E9] text-[#2F6846] border-[#CFE2D5]' : 
                                                  ($event->status === 'CANCELLED' ? 'bg-[#F9E6E6] text-[#B84242] border-[#F0D0D0]' : 'bg-[#F7EFE1] text-[#9A6721] border-[#EADBC0]') }}">
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

            <!-- Card Informasi Pembayaran & Struk -->
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Informasi Pembayaran & Struk</h3>
                    </div>
                    @if($shipment->payment_status === 'PAID')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono uppercase bg-[#E9EAE4] text-[#243329] border border-[#C8D3BE]">
                            LUNAS
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono uppercase bg-[#D8CFC0]/40 text-[#B96A55] border border-[#D8CFC0]">
                            BELUM LUNAS
                        </span>
                    @endif
                </div>

                <div class="space-y-3.5 text-xs">
                    <!-- Invoice No -->
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-medium">Nomor Faktur (Invoice):</span>
                        <span class="font-mono font-bold text-[#243329]">{{ $shipment->invoice_number ?? '-' }}</span>
                    </div>

                    <!-- Total Amount -->
                    <div class="p-3 rounded-xl bg-[#F6F7F3] border border-[#DFE1D8]">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Total Tagihan (IDR)</span>
                        <div class="text-lg font-extrabold font-mono text-[#243329]">
                            {{ \App\Services\ShippingRateService::formatRupiah($shipment->total_amount) }}
                        </div>
                    </div>

                    <!-- Rincian Biaya -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5 text-[11px] text-slate-600">
                        <div class="flex justify-between">
                            <span>Biaya Kirim Dasar:</span>
                            <span class="font-mono font-medium text-slate-800">{{ \App\Services\ShippingRateService::formatRupiah($shipment->base_cost) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Berat & Volume:</span>
                            <span class="font-mono font-medium text-slate-800">{{ \App\Services\ShippingRateService::formatRupiah($shipment->weight_volume_cost) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Layanan & Asuransi:</span>
                            <span class="font-mono font-medium text-slate-800">{{ \App\Services\ShippingRateService::formatRupiah($shipment->insurance_cost) }}</span>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-slate-400 font-medium">Metode Pembayaran:</span>
                        <span class="font-semibold text-slate-800">
                            {{ \App\Services\ShippingRateService::getPaymentMethodLabel($shipment->payment_method) }}
                        </span>
                    </div>

                    <!-- Status Pelunasan -->
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-medium">Status Pembayaran:</span>
                        <span class="font-bold {{ $shipment->payment_status === 'PAID' ? 'text-[#3E5245]' : 'text-[#B96A55]' }}">
                            {{ $shipment->payment_status === 'PAID' ? 'LUNAS (TERVERIFIKASI)' : 'MENUNGGU PEMBAYARAN' }}
                        </span>
                    </div>

                    @if($shipment->payment_status === 'PAID')
                        <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-500 space-y-1">
                            <div class="flex justify-between">
                                <span>Waktu Pelunasan:</span>
                                <span class="font-medium text-slate-700 font-mono">
                                    {{ $shipment->paid_at ? $shipment->paid_at->format('d/m/Y H:i') . ' WIB' : '-' }}
                                </span>
                            </div>
                            @if($shipment->paymentConfirmedBy)
                                <div class="flex justify-between">
                                    <span>Petugas Pemverifikasi:</span>
                                    <span class="font-medium text-slate-700">{{ $shipment->paymentConfirmedBy->name }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Tombol Lihat & Cetak Struk -->
                    <div class="pt-3">
                        <a href="{{ route('shipments.receipt', $shipment) }}" target="_blank"
                           class="w-full inline-flex items-center justify-center space-x-2 py-2.5 px-4 rounded-full bg-[#E9EAE4] hover:bg-[#DFE1D8] text-[#243329] text-xs font-bold transition border border-[#C8D3BE] shadow-xs">
                            <svg class="w-4 h-4 text-[#3E5245]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Lihat & Cetak Struk Resmi</span>
                        </a>
                    </div>
                </div>

                <!-- Form Konfirmasi Lunas Khusus Admin & Operator jika UNPAID -->
                @if((auth()->user()->isAdmin() || auth()->user()->isOperator()) && $shipment->payment_status !== 'PAID')
                    <div class="mt-4 pt-4 border-t border-[#DFE1D8]">
                        <span class="text-[11px] font-bold text-[#243329] uppercase tracking-wider block mb-2">
                            Konfirmasi Pembayaran (Kasir / Loket)
                        </span>
                        <form method="POST" action="{{ route('shipments.payment.confirm', $shipment) }}" class="space-y-2.5">
                            @csrf
                            <div>
                                <label for="confirm_payment_method" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                                    Metode Diterima
                                </label>
                                <select name="payment_method" id="confirm_payment_method"
                                        class="w-full px-3 py-1.5 rounded-xl border border-[#DFE1D8] text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C]">
                                    <option value="BANK_TRANSFER" {{ $shipment->payment_method === 'BANK_TRANSFER' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="CASH" {{ $shipment->payment_method === 'CASH' ? 'selected' : '' }}>Tunai (Loket)</option>
                                    <option value="QRIS" {{ $shipment->payment_method === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="CORPORATE_INVOICE" {{ $shipment->payment_method === 'CORPORATE_INVOICE' ? 'selected' : '' }}>Tagihan Invoice B2B</option>
                                </select>
                            </div>

                            <button type="submit"
                                    class="w-full py-2.5 px-4 rounded-full bg-[#243329] hover:bg-[#3E5245] text-white text-xs font-bold transition shadow-xs flex items-center justify-center space-x-1.5">
                                <svg class="w-4 h-4 text-[#C8D3BE]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Konfirmasi Pembayaran Lunas</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            @if((auth()->user()->isAdmin() || auth()->user()->isOperator()) && !in_array($shipment->status, ['DELIVERED', 'CANCELLED']))
                
                <!-- Action Form 1: Tambah Tracking Event (Checkpoint) -->
                <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tambah Checkpoint Tracking</h3>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono">Event Baru</span>
                    </div>

                    <form method="POST" action="{{ route('shipments.tracking.store', $shipment) }}" class="space-y-3.5">
                        @csrf

                        <!-- Lokasi -->
                        <div>
                            <label for="track_location_id" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Pilih Lokasi Checkpoint</label>
                            <select name="location_id" id="track_location_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C]">
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
                            <select name="status" id="track_status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C]">
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
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C]">
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="track_description" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Deskripsi Checkpoint <span class="text-rose-500">*</span></label>
                            <textarea name="description" 
                                      id="track_description" 
                                      rows="2" 
                                      required
                                      placeholder="Contoh: Kargo telah tiba di checkpoint transit..."
                                      class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C]">{{ old('description') }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs font-bold transition shadow-xs">
                            Simpan Checkpoint Tracking
                        </button>
                    </form>
                </div>

                <!-- Action Form 2: Laporkan Exception (Kendala Operasional) -->
                <div id="form-exception" class="bg-white rounded-[24px] border border-rose-200 shadow-sm p-6">
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

                        <button type="submit" class="w-full py-2.5 px-5 rounded-full bg-[#B84242] hover:bg-[#A33838] text-white text-xs font-bold transition shadow-xs">
                            Laporkan Insiden Exception
                        </button>
                    </form>
                </div>

                <!-- Action Form 3: State Machine Status Transition -->
                <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold tracking-wide uppercase px-3 py-1 rounded-full bg-[#EDF1EC] text-[#425848] border border-[#D4DFD2]">
                            State Machine
                        </span>
                        <span class="text-xs text-slate-500 font-medium">Status: <strong>{{ $shipment->status }}</strong></span>
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
                                    class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C]">
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
                                      class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C]"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs font-bold shadow-xs transition">
                            Simpan Transisi Status &rarr;
                        </button>
                    </form>
                </div>

            @elseif(in_array($shipment->status, ['DELIVERED', 'CANCELLED']))
                <!-- Status Final Notice -->
                <div class="p-5 rounded-[24px] bg-white border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] text-slate-700 text-xs">
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
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-5 space-y-4 text-xs">
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

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-popup-content-wrapper {
        border-radius: 1rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        padding: 4px;
    }
    .leaflet-popup-content {
        margin: 12px 14px;
        line-height: 1.5;
    }
    .leaflet-container {
        font-family: inherit;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapData = @json($mapData);

    const mapElement = document.getElementById('shipment-map');
    if (!mapElement) return;

    // Inisialisasi peta Leaflet dengan center default koordinat Indonesia
    const defaultCenter = [-6.1751, 106.8650];
    const map = L.map('shipment-map', {
        scrollWheelZoom: false,
        attributionControl: true,
        minZoom: 1
    }).setView(defaultCenter, 4);

    // Tile Layer OpenStreetMap resmi WAJIB mencantumkan atribusi
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
    }).addTo(map);

    const boundsPoints = [];
    const traveledPoints = [];

    // Factory helper DivIcon kustom tanpa dependensi asset PNG eksternal
    function createDivIcon(bgColor, textColor, innerHtml, size = 32) {
        return L.divIcon({
            className: 'custom-map-icon',
            html: `<div style="background-color: ${bgColor}; color: ${textColor}; width: ${size}px; height: ${size}px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px;">${innerHtml}</div>`,
            iconSize: [size, size],
            iconAnchor: [size / 2, size / 2],
            popupAnchor: [0, -(size / 2 + 4)]
        });
    }

    // 1. Pasang Marker Origin
    if (mapData.origin && mapData.origin.latitude !== null && mapData.origin.longitude !== null) {
        const originLatLng = [mapData.origin.latitude, mapData.origin.longitude];
        boundsPoints.push(originLatLng);
        traveledPoints.push(originLatLng);

        const originIcon = createDivIcon('#243329', '#ffffff', `
            <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        `, 34);

        const originPopup = `
            <div style="font-family: inherit; font-size: 12px; line-height: 1.4; min-width: 200px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-size: 10px; font-weight: 800; background: #243329; color: #ffffff; padding: 2px 8px; border-radius: 9999px; text-transform: uppercase;">ORIGIN</span>
                    <span style="font-size: 11px; font-weight: bold; font-family: monospace; color: #6F756C;">[${escapeHtml(mapData.origin.code)}]</span>
                </div>
                <div style="font-weight: 800; color: #1C1E1B; font-size: 13px;">${escapeHtml(mapData.origin.name)}</div>
                <div style="color: #6F756C; font-size: 11px; margin-top: 2px;">Tipe: <strong>${escapeHtml(mapData.origin.type)}</strong></div>
                <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #DFE1D8; font-size: 11px; color: #1C1E1B;">
                    <span style="color: #6F756C;">Jadwal Berangkat:</span><br>
                    <strong>${escapeHtml(mapData.origin.expected_departure || 'Belum ditentukan')}</strong>
                </div>
            </div>
        `;

        L.marker(originLatLng, { icon: originIcon }).addTo(map).bindPopup(originPopup);
    }

    // 2. Pasang Marker Tracking Events (Normal & Exception)
    if (mapData.events && mapData.events.length > 0) {
        mapData.events.forEach((ev) => {
            const evLatLng = [ev.latitude, ev.longitude];
            boundsPoints.push(evLatLng);
            traveledPoints.push(evLatLng);

            const isException = Boolean(ev.is_exception);
            const bgColor = isException ? '#e11d48' : '#f59e0b';
            const iconHtml = isException ? '!' : `<span style="width: 7px; height: 7px; background-color: #ffffff; border-radius: 50%;"></span>`;
            const evIcon = createDivIcon(bgColor, '#ffffff', iconHtml, isException ? 30 : 26);

            const evPopup = `
                <div style="font-family: inherit; font-size: 12px; line-height: 1.4; min-width: 220px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; gap: 6px;">
                        <span style="font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; ${isException ? 'background: #ffe4e6; color: #be123c;' : 'background: #fef3c7; color: #92400e;'}">
                            ${isException ? 'EXCEPTION: ' : 'CHECKPOINT: '}${escapeHtml(ev.status)}
                        </span>
                        <span style="font-size: 10px; font-family: monospace; color: #64748b;">${escapeHtml(ev.event_time)}</span>
                    </div>
                    <div style="font-weight: 700; color: #0f172a; font-size: 12px;">${escapeHtml(ev.location_name)}</div>
                    <div style="margin-top: 4px; font-size: 11px; color: #475569; background: #f8fafc; padding: 6px; border-radius: 6px; border: 1px solid #e2e8f0; line-height: 1.35;">
                        ${escapeHtml(ev.description)}
                    </div>
                </div>
            `;

            L.marker(evLatLng, { icon: evIcon }).addTo(map).bindPopup(evPopup);
        });
    }

    // 3. Pasang Marker Destination
    let destLatLng = null;
    if (mapData.destination && mapData.destination.latitude !== null && mapData.destination.longitude !== null) {
        destLatLng = [mapData.destination.latitude, mapData.destination.longitude];
        boundsPoints.push(destLatLng);

        const destIcon = createDivIcon('#556E5C', '#ffffff', `
            <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        `, 34);

        const destPopup = `
            <div style="font-family: inherit; font-size: 12px; line-height: 1.4; min-width: 200px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                    <span style="font-size: 10px; font-weight: 800; background: #E6F0E9; color: #2F6846; padding: 2px 8px; border-radius: 9999px; text-transform: uppercase;">DESTINATION</span>
                    <span style="font-size: 11px; font-weight: bold; font-family: monospace; color: #6F756C;">[${escapeHtml(mapData.destination.code)}]</span>
                </div>
                <div style="font-weight: 800; color: #2F6846; font-size: 13px;">${escapeHtml(mapData.destination.name)}</div>
                <div style="color: #6F756C; font-size: 11px; margin-top: 2px;">Tipe: <strong>${escapeHtml(mapData.destination.type)}</strong></div>
                <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #DFE1D8; font-size: 11px; color: #1C1E1B; space-y-1;">
                    <div><span style="color: #6F756C;">Target Tiba:</span> <strong>${escapeHtml(mapData.destination.expected_arrival || '-')}</strong></div>
                    <div><span style="color: #6F756C;">Estimasi Tiba:</span> <strong>${escapeHtml(mapData.destination.estimated_arrival || '-')}</strong></div>
                </div>
            </div>
        `;

        L.marker(destLatLng, { icon: destIcon }).addTo(map).bindPopup(destPopup);
    }

    // 4. Gambar Garis Polylines
    // Rute yang sudah dilalui: Solid line (Origin -> urutan Checkpoints)
    if (traveledPoints.length >= 2) {
        L.polyline(traveledPoints, {
            color: '#1C1E1B',
            weight: 3.5,
            opacity: 0.85
        }).addTo(map);
    }

    // Garis putus-putus (Dashed line) dari titik terakhir menuju Destination
    if (traveledPoints.length > 0 && destLatLng) {
        const lastTraveled = traveledPoints[traveledPoints.length - 1];
        L.polyline([lastTraveled, destLatLng], {
            color: '#556E5C',
            weight: 3,
            dashArray: '6, 8',
            opacity: 0.85
        }).addTo(map);
    } else if (traveledPoints.length === 0 && mapData.origin && mapData.origin.latitude !== null && destLatLng) {
        // Fallback jika belum ada checkpoint: garis putus-putus langsung dari origin ke destination
        L.polyline([[mapData.origin.latitude, mapData.origin.longitude], destLatLng], {
            color: '#6F756C',
            weight: 2.5,
            dashArray: '6, 8',
            opacity: 0.7
        }).addTo(map);
    }

    // 5. Fit Bounds ke seluruh koordinat
    if (boundsPoints.length > 1) {
        const isMobile = window.innerWidth < 768;
        map.fitBounds(L.latLngBounds(boundsPoints), {
            padding: isMobile ? [15, 15] : [45, 45],
            maxZoom: 13
        });
    } else if (boundsPoints.length === 1) {
        map.setView(boundsPoints[0], 9);
    }

    setTimeout(() => {
        map.invalidateSize();
    }, 250);
    window.addEventListener('resize', () => {
        map.invalidateSize();
    });

    function escapeHtml(str) {
        if (!str) return '';
        const p = document.createElement('p');
        p.textContent = str;
        return p.innerHTML;
    }
});
</script>
@endpush
@endsection
