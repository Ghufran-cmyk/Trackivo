<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shipment ? 'Lacak Resi ' . $shipment->tracking_number : 'Pelacakan Pengiriman Kargo Publik' }} - Trackivo</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        canvas: '#E9EAE4',
                        charcoal: '#1C1E1B',
                        muted: '#6F756C',
                        sage: {
                            50: '#F4F7F4',
                            100: '#E6F0E9',
                            200: '#C8DACD',
                            300: '#A3BEAA',
                            500: '#556E5C',
                            600: '#425848',
                            700: '#35473A',
                            800: '#243329',
                            900: '#1B271F',
                        },
                        sand: {
                            50: '#FAF9F5',
                            100: '#F5F6F1',
                            200: '#E9EAE4',
                            300: '#DFE1D8',
                            400: '#C8D3BE',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E9EAE4;
            color: #1C1E1B;
        }

        /* Leaflet custom map icon & popups */
        .leaflet-popup-content-wrapper {
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(28, 30, 27, 0.15);
            border: 1px solid #DFE1D8;
            padding: 4px;
        }
        .leaflet-popup-content {
            margin: 12px 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .leaflet-container {
            font-family: inherit;
        }
    </style>
</head>
<body class="min-h-screen bg-[#E9EAE4] text-[#1C1E1B] antialiased flex flex-col justify-between">

    <!-- Top Navigation Bar -->
    <header class="w-full max-w-6xl mx-auto px-4 sm:px-6 pt-5 pb-2">
        <nav class="flex items-center justify-between">
            <!-- Brand Logo in Dark-Sage Badge -->
            <a href="{{ route('tracking.public') }}" class="group inline-flex items-center space-x-3 transition">
                <div class="bg-[#243329] px-4 py-2 rounded-full border border-white/10 flex items-center shadow-sm group-hover:bg-[#1B271F] transition">
                    <img src="{{ asset('images/logonama.png') }}" alt="Trackivo Logo" class="h-6 sm:h-7 w-auto object-contain">
                </div>
                <span class="hidden md:inline-flex items-center text-xs font-semibold uppercase tracking-wider text-[#556E5C] bg-[#DFE1D8]/60 px-3 py-1 rounded-full border border-[#DFE1D8]">
                    Public Tracking Portal
                </span>
            </a>

            <!-- Right Action: Login / Dashboard -->
            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-[#243329] hover:bg-[#1B271F] text-white text-xs font-semibold shadow-sm transition">
                        <span>Kembali ke Dashboard</span>
                        <svg class="w-4 h-4 text-[#C8D3BE]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-white hover:bg-[#F5F6F1] text-[#243329] border border-[#DFE1D8] text-xs font-semibold shadow-sm transition">
                        <span>Masuk ke Portal</span>
                        <svg class="w-3.5 h-3.5 text-[#556E5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Main Content Container -->
    <main class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 flex-grow">

        <!-- Hero Card & Search Form -->
        <div class="bg-[#243329] rounded-[28px] p-6 sm:p-10 text-white shadow-[0_16px_40px_rgba(28,30,27,0.12)] relative overflow-hidden mb-8">
            <!-- Subtle glow background decoration -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#556E5C]/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -top-10 w-60 h-60 bg-[#C8D3BE]/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl mx-auto text-center">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-white/10 text-[#C8D3BE] border border-white/10 mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#C8D3BE] animate-pulse"></span>
                    <span>Pelacakan Kargo Real-Time Tanpa Login</span>
                </span>

                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white">
                    Lacak Pengiriman Kargo Anda
                </h1>
                <p class="text-xs sm:text-sm text-[#D5DFD2] mt-2 mb-6 sm:mb-8 leading-relaxed">
                    Pantau posisi geospasial aktual, riwayat checkpoint kargo, dan estimasi waktu ketibaan dengan akurat melalui nomor resi pengiriman.
                </p>

                <!-- Search Form -->
                <form action="{{ route('tracking.public') }}" method="GET" class="relative max-w-xl mx-auto">
                    <div class="flex flex-col sm:flex-row items-stretch gap-2.5 p-2 bg-[#1B271F] rounded-2xl border border-[#3B4F41] shadow-inner focus-within:border-[#C8D3BE] transition">
                        <div class="relative flex-grow flex items-center">
                            <svg class="w-5 h-5 text-[#889B8D] ml-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input 
                                type="text" 
                                name="tracking_number" 
                                id="tracking_number" 
                                value="{{ $trackingNumber }}" 
                                placeholder="Masukkan nomor resi (contoh: TRK-2026-JKT-RTM-001)" 
                                required
                                autocomplete="off"
                                class="w-full bg-transparent border-0 text-white placeholder-[#738779] px-3 py-3 text-sm font-mono focus:outline-none focus:ring-0">
                        </div>
                        <button 
                            type="submit" 
                            class="bg-[#C8D3BE] hover:bg-[#D7E0CE] text-[#1C2820] font-bold rounded-xl py-3 px-6 text-xs sm:text-sm transition-all shadow-sm flex items-center justify-center space-x-2 shrink-0 active:scale-[0.98]">
                            <span>Lacak Resi</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                @if(!$searched)
                    <div class="mt-4 flex items-center justify-center space-x-2 text-[11px] text-[#A2B6A6]">
                        <span>Contoh resi aktif:</span>
                        <a href="{{ route('tracking.public', ['tracking_number' => 'TRK-2026-JKT-RTM-001']) }}" class="font-mono underline text-[#D5DFD2] hover:text-white">TRK-2026-JKT-RTM-001</a>
                    </div>
                @endif
            </div>
        </div>

        @if($searched && $shipment)
            <!-- HASIL PELACAKAN RESI -->
            <div class="space-y-8">

                <!-- 1. Ringkasan Pengiriman (Shipment Summary Card) -->
                <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6 sm:p-8">
                    <!-- Status Header Row -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-[#DFE1D8]/60">
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-[#6F756C] mb-1">
                                Nomor Resi Pengiriman
                            </div>
                            <div class="flex items-center space-x-3">
                                <h2 class="text-2xl sm:text-3xl font-extrabold font-mono text-[#1C1E1B] tracking-tight">
                                    {{ $shipment->tracking_number }}
                                </h2>
                                <button 
                                    type="button" 
                                    onclick="navigator.clipboard.writeText('{{ $shipment->tracking_number }}'); alert('Nomor resi berhasil disalin!');" 
                                    title="Salin nomor resi"
                                    class="p-1.5 rounded-lg text-[#6F756C] hover:text-[#1C1E1B] hover:bg-[#F5F6F1] transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Status Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Status Pengiriman Lembut -->
                            @php
                                $statusStyles = [
                                    'CREATED'    => 'bg-[#F0F2ED] text-[#4F594D] border-[#DCE0D7]',
                                    'BOOKED'     => 'bg-[#F7EFE1] text-[#9A6721] border-[#E6D8BF]',
                                    'PICKED_UP'  => 'bg-[#EDF1EC] text-[#3E5544] border-[#D3DEC6]',
                                    'IN_TRANSIT' => 'bg-[#F7EFE1] text-[#9A6721] border-[#E6D8BF]',
                                    'DELIVERED'  => 'bg-[#E6F0E9] text-[#2F6846] border-[#C8DACD]',
                                    'CANCELLED'  => 'bg-[#F9E6E6] text-[#B84242] border-[#F0C9C9]',
                                ];
                            @endphp
                            <span class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusStyles[$shipment->status] ?? 'bg-[#F0F2ED] text-[#4F594D] border-[#DCE0D7]' }}">
                                <span class="w-2 h-2 rounded-full {{ in_array($shipment->status, ['DELIVERED', 'IN_TRANSIT']) ? 'bg-[#556E5C]' : 'bg-[#9A6721]' }}"></span>
                                <span>STATUS: {{ $shipment->status }}</span>
                            </span>

                            <!-- Ketepatan Waktu / Delay -->
                            @if($delayAnalysis && $delayAnalysis['is_delayed'])
                                <span class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#F9E6E6] text-[#B84242] border border-[#F0C9C9]">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>DELAY (+{{ $delayAnalysis['delay_hours'] }} Jam)</span>
                                </span>
                            @else
                                <span class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#E6F0E9] text-[#2F6846] border border-[#C8DACD]">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>ON-TIME</span>
                                </span>
                            @endif

                            <!-- Moda Transportasi -->
                            <span class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-full text-xs font-semibold bg-[#F5F6F1] text-[#35473A] border border-[#DFE1D8]">
                                @if($shipment->transport_mode === 'AIR')
                                    <svg class="w-3.5 h-3.5 text-[#556E5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                @elseif($shipment->transport_mode === 'OCEAN')
                                    <svg class="w-3.5 h-3.5 text-[#556E5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @else
                                    <svg class="w-3.5 h-3.5 text-[#556E5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                @endif
                                <span>{{ $shipment->transport_mode }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Details Grid: Rute, Carrier, Jadwal -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
                        <!-- Kolom 1: Rute Asal & Tujuan -->
                        <div class="space-y-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#6F756C] block">
                                Rute Logistik
                            </span>
                            <div class="flex items-start space-x-3">
                                <div class="flex flex-col items-center mt-1">
                                    <div class="w-3 h-3 rounded-full bg-[#243329]"></div>
                                    <div class="w-0.5 h-8 bg-[#DFE1D8]"></div>
                                    <div class="w-3 h-3 rounded-full bg-[#556E5C]"></div>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div>
                                        <div class="font-bold text-[#1C1E1B] text-sm">
                                            {{ $shipment->originLocation?->name ?? 'Titik Asal' }}
                                        </div>
                                        <div class="text-[#6F756C] font-mono text-[11px]">
                                            [{{ $shipment->originLocation?->code ?? '-' }}] &bull; {{ $shipment->originLocation?->country ?? '-' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#1C1E1B] text-sm">
                                            {{ $shipment->destinationLocation?->name ?? 'Titik Tujuan' }}
                                        </div>
                                        <div class="text-[#6F756C] font-mono text-[11px]">
                                            [{{ $shipment->destinationLocation?->code ?? '-' }}] &bull; {{ $shipment->destinationLocation?->country ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom 2: Operator & Karakteristik Kargo -->
                        <div class="space-y-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#6F756C] block">
                                Operator & Spesifikasi Kargo
                            </span>
                            <div class="space-y-2 text-xs text-[#1C1E1B]">
                                <div>
                                    <span class="text-[#6F756C] block text-[11px]">Carrier / Maskapai:</span>
                                    <span class="font-semibold text-sm">{{ $shipment->carrier?->name ?? 'Carrier Partner' }}</span>
                                    <span class="text-[#6F756C] font-mono text-[11px]">({{ $shipment->carrier?->code ?? '-' }})</span>
                                </div>
                                <div>
                                    <span class="text-[#6F756C] block text-[11px]">Kategori Muatan:</span>
                                    <span class="font-medium">{{ $shipment->cargo_type ?? 'General Cargo' }}</span>
                                </div>
                                <div class="flex items-center space-x-3 text-[11px] text-[#6F756C]">
                                    <span>Berat: <strong class="text-[#1C1E1B]">{{ number_format($shipment->weight_kg, 2) }} kg</strong></span>
                                    @if($shipment->volume_cbm)
                                        <span>&bull;</span>
                                        <span>Volume: <strong class="text-[#1C1E1B]">{{ number_format($shipment->volume_cbm, 2) }} cbm</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Kolom 3: Jadwal Keberangkatan & Estimasi Tiba (ETA) -->
                        <div class="space-y-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#6F756C] block">
                                Estimasi Kedatangan (ETA)
                            </span>
                            <div class="p-3.5 rounded-2xl bg-[#F5F6F1] border border-[#DFE1D8] space-y-2 text-xs">
                                <div>
                                    <span class="text-[10px] font-bold uppercase text-[#6F756C] block">Target Kedatangan (Expected):</span>
                                    <span class="font-semibold text-[#1C1E1B]">
                                        {{ $shipment->expected_arrival ? $shipment->expected_arrival->format('d M Y, H:i') . ' WIB' : 'Belum ditentukan' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase text-[#6F756C] block">Estimasi Kedatangan Terkini (ETA):</span>
                                    <span class="font-bold text-sm text-[#243329]">
                                        {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y, H:i') . ' WIB' : 'Sesuai Jadwal' }}
                                    </span>
                                </div>
                                @if($currentLocation)
                                    <div class="pt-1 border-t border-[#DFE1D8]/60 text-[11px] text-[#556E5C] flex items-center space-x-1">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        <span class="truncate">Posisi: <strong>{{ $currentLocation['location_name'] }}</strong></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Peta Rute Interaktif (Leaflet.js) -->
                <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
                    <div class="p-5 sm:p-6 border-b border-[#DFE1D8]/60 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#556E5C] animate-pulse"></span>
                                <h3 class="text-sm font-bold text-[#1C1E1B] uppercase tracking-wider">
                                    Peta Rute & Titik Checkpoint Geospasial
                                </h3>
                            </div>
                            <p class="text-xs text-[#6F756C] mt-0.5">
                                Visualisasi spasial rute kargo dari titik asal (Origin), riwayat checkpoint yang telah dilalui, hingga destinasi akhir.
                            </p>
                        </div>

                        <!-- Legend -->
                        <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#243329] text-white">
                                <span class="h-2 w-2 rounded-full bg-white"></span>
                                <span>Origin Hub</span>
                            </span>
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#F7EFE1] text-[#9A6721] border border-[#E6D8BF]">
                                <span class="h-2 w-2 rounded-full bg-[#9A6721]"></span>
                                <span>Checkpoint</span>
                            </span>
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#E6F0E9] text-[#2F6846] border border-[#C8DACD]">
                                <span class="h-2 w-2 rounded-full bg-[#2F6846]"></span>
                                <span>Destination Hub</span>
                            </span>
                        </div>
                    </div>

                    <!-- Wadah Peta Leaflet.js -->
                    <div class="relative">
                        <div id="public-shipment-map" style="height: 420px; width: 100%;" class="z-10"></div>

                        @php
                            $hasOriginCoord = ($mapData['origin']['latitude'] ?? null) !== null && ($mapData['origin']['longitude'] ?? null) !== null;
                            $hasDestCoord = ($mapData['destination']['latitude'] ?? null) !== null && ($mapData['destination']['longitude'] ?? null) !== null;
                            $hasEventCoord = count($mapData['events'] ?? []) > 0;
                        @endphp
                        @if(!$hasOriginCoord && !$hasDestCoord && !$hasEventCoord)
                            <div class="absolute inset-0 z-20 bg-[#FAF9F5]/90 backdrop-blur-xs flex flex-col items-center justify-center p-6 text-center">
                                <div class="h-12 w-12 rounded-2xl bg-[#DFE1D8] text-[#556E5C] flex items-center justify-center mb-3">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                </div>
                                <div class="text-sm font-bold text-[#1C1E1B]">Koordinat Peta Belum Tersedia</div>
                                <p class="text-xs text-[#6F756C] max-w-md mt-1">
                                    Informasi koordinat latitude dan longitude belum tersedia pada rute pengiriman ini.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 3. Timeline Riwayat Checkpoint (Terbaru di atas) -->
                <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6 sm:p-8">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-[#DFE1D8]/60">
                        <div>
                            <h3 class="text-sm font-bold text-[#1C1E1B] uppercase tracking-wider">
                                Riwayat Perjalanan & Checkpoint Kargo
                            </h3>
                            <p class="text-xs text-[#6F756C] mt-0.5">
                                Catatan pembaruan kronologis status dan lokasi kargo (urutan terbaru di posisi teratas).
                            </p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[#F5F6F1] text-[#556E5C] border border-[#DFE1D8]">
                            {{ $timelineEvents->count() }} Checkpoint Tercatat
                        </span>
                    </div>

                    @if($timelineEvents->count() > 0)
                        <div class="relative pl-6 sm:pl-8 space-y-6 before:content-[''] before:absolute before:left-2.5 sm:before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#DFE1D8]">
                            @foreach($timelineEvents as $index => $event)
                                <div class="relative group">
                                    <!-- Dot Marker -->
                                    <div class="absolute -left-6 sm:-left-8 top-1.5 w-5 h-5 rounded-full border-4 border-white {{ $index === 0 ? 'bg-[#556E5C] ring-4 ring-[#E6F0E9]' : 'bg-[#C8DACD]' }} shadow-sm"></div>

                                    <!-- Event Card -->
                                    <div class="bg-[#FAF9F5] group-hover:bg-[#F5F6F1] border border-[#DFE1D8]/70 rounded-2xl p-4 sm:p-5 transition shadow-xs">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $event->is_exception ? 'bg-[#F9E6E6] text-[#B84242]' : 'bg-[#E6F0E9] text-[#2F6846]' }}">
                                                    {{ $event->status }}
                                                </span>
                                                <span class="font-bold text-sm text-[#1C1E1B]">
                                                    {{ $event->location?->name ?? 'Posisi Lapangan / Checkpoint' }}
                                                </span>
                                            </div>
                                            <div class="text-[11px] font-mono text-[#6F756C]">
                                                {{ $event->event_time ? $event->event_time->format('d M Y, H:i') . ' WIB' : '-' }}
                                            </div>
                                        </div>

                                        <p class="text-xs text-[#4F594D] leading-relaxed">
                                            {{ $event->description }}
                                        </p>

                                        @if($event->location)
                                            <div class="mt-2.5 pt-2.5 border-t border-[#DFE1D8]/50 flex items-center space-x-3 text-[11px] text-[#6F756C]">
                                                <span>Kode Hub: <strong class="font-mono text-[#1C1E1B]">{{ $event->location->code }}</strong></span>
                                                <span>&bull;</span>
                                                <span>Negara: <strong class="text-[#1C1E1B]">{{ $event->location->country }}</strong></span>
                                                <span>&bull;</span>
                                                <span>Tipe: <strong class="text-[#1C1E1B]">{{ $event->location->type }}</strong></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- State jika belum ada checkpoint terdaftar -->
                        <div class="py-12 text-center">
                            <div class="w-12 h-12 rounded-full bg-[#F5F6F1] text-[#556E5C] mx-auto flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-[#1C1E1B]">Kargo Menunggu Keberangkatan</h4>
                            <p class="text-xs text-[#6F756C] max-w-sm mx-auto mt-1">
                                Checkpoint pertama akan diperbarui segera setelah kargo meninggalkan titik asal (Origin Hub).
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        @elseif($searched && !$shipment)
            <!-- NOT FOUND STATE -->
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-8 sm:p-12 text-center max-w-xl mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-[#F9E6E6] text-[#B84242] mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h2 class="text-lg font-bold text-[#1C1E1B]">Nomor Resi Tidak Ditemukan</h2>
                <p class="text-xs sm:text-sm text-[#6F756C] mt-2 leading-relaxed">
                    Kami tidak menemukan data pengiriman kargo dengan nomor resi <span class="font-mono font-semibold text-[#1C1E1B] bg-[#F5F6F1] px-2 py-0.5 rounded">"{{ $trackingNumber }}"</span>.
                </p>

                <div class="mt-6 p-4 rounded-xl bg-[#FAF9F5] border border-[#DFE1D8] text-left text-xs text-[#4F594D] space-y-1.5">
                    <div class="font-bold text-[#1C1E1B]">Saran Pemeriksaan:</div>
                    <ul class="list-disc list-inside space-y-1 text-[#6F756C]">
                        <li>Periksa kembali ejaan karakter dan format nomor resi Anda.</li>
                        <li>Pastikan tidak ada spasi tambahan atau karakter khusus yang terpotong.</li>
                        <li>Contoh nomor resi pengiriman kargo yang valid: <span class="font-mono font-bold text-[#243329]">TRK-2026-JKT-RTM-001</span>.</li>
                    </ul>
                </div>

                <div class="mt-6">
                    <a href="{{ route('tracking.public') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-[#556E5C] hover:text-[#243329] transition">
                        <span>Bersihkan formulir pencarian</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </a>
                </div>
            </div>
        @else
            <!-- INITIAL LANDING / GUIDE CARDS (Belum Ada Pencarian) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
                    <div class="w-10 h-10 rounded-xl bg-[#E6F0E9] text-[#2F6846] flex items-center justify-center font-bold mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#1C1E1B] mb-1">Pelacakan Geospasial Interaktif</h3>
                    <p class="text-xs text-[#6F756C] leading-relaxed">
                        Pantau perjalanan kargo laut, udara, dan darat secara visual di atas peta interaktif dari titik awal pengiriman hingga pelabuhan/bandara tujuan.
                    </p>
                </div>

                <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
                    <div class="w-10 h-10 rounded-xl bg-[#F7EFE1] text-[#9A6721] flex items-center justify-center font-bold mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#1C1E1B] mb-1">Estimasi Kedatangan (ETA) Cerdas</h3>
                    <p class="text-xs text-[#6F756C] leading-relaxed">
                        Ketahui status ketepatan waktu kargo secara transparan lengkap dengan deteksi awal potensi keterlambatan (delay) rute transit.
                    </p>
                </div>

                <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
                    <div class="w-10 h-10 rounded-xl bg-[#EDF1EC] text-[#3E5544] flex items-center justify-center font-bold mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#1C1E1B] mb-1">Privasi &amp; Akses Publik Terjamin</h3>
                    <p class="text-xs text-[#6F756C] leading-relaxed">
                        Akses informasi status dan checkpoint publik tanpa login, dengan proteksi ketat data pribadi dan kerahasiaan pelanggan kargo.
                    </p>
                </div>
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 text-center text-xs text-[#6F756C]">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-[#DFE1D8]">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-[#243329]">Trackivo Global</span>
                <span>&bull;</span>
                <span>Supply Chain Visibility &amp; Geospatial Tracking</span>
            </div>
            <div>
                &copy; {{ date('Y') }} Trackivo. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <!-- Leaflet Map Script (Hanya jika shipment ditemukan) -->
    @if($searched && $shipment && $mapData)
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const mapData = @json($mapData);
                const mapElement = document.getElementById('public-shipment-map');
                if (!mapElement) return;

                // Center default kepulauan Indonesia
                const defaultCenter = [-6.1751, 106.8650];
                const map = L.map('public-shipment-map', {
                    scrollWheelZoom: false,
                    attributionControl: true
                }).setView(defaultCenter, 4);

                // OpenStreetMap Tile Layer resmi dengan atribusi
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
                }).addTo(map);

                const boundsPoints = [];
                const traveledPoints = [];

                // Factory helper DivIcon kustom selaras dengan tema sage
                function createDivIcon(bgColor, textColor, innerHtml, size = 32) {
                    return L.divIcon({
                        className: 'custom-map-icon',
                        html: `<div style="background-color: ${bgColor}; color: ${textColor}; width: ${size}px; height: ${size}px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 4px 10px rgba(28,30,27,0.25); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px;">${innerHtml}</div>`,
                        iconSize: [size, size],
                        iconAnchor: [size / 2, size / 2],
                        popupAnchor: [0, -(size / 2 + 4)]
                    });
                }

                // 1. Pasang Marker Origin Hub
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
                        <div style="font-size: 12px; line-height: 1.4; min-width: 200px;">
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

                // 2. Pasang Marker Checkpoints
                if (mapData.events && mapData.events.length > 0) {
                    mapData.events.forEach((ev) => {
                        const evLatLng = [ev.latitude, ev.longitude];
                        boundsPoints.push(evLatLng);
                        traveledPoints.push(evLatLng);

                        const isException = Boolean(ev.is_exception);
                        const bgColor = isException ? '#B84242' : '#9A6721';
                        const iconHtml = isException ? '!' : `<span style="width: 7px; height: 7px; background-color: #ffffff; border-radius: 50%;"></span>`;
                        const evIcon = createDivIcon(bgColor, '#ffffff', iconHtml, isException ? 28 : 24);

                        const evPopup = `
                            <div style="font-size: 12px; line-height: 1.4; min-width: 220px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; gap: 6px;">
                                    <span style="font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; ${isException ? 'background: #F9E6E6; color: #B84242;' : 'background: #F7EFE1; color: #9A6721;'}">
                                        ${isException ? 'EXCEPTION: ' : 'CHECKPOINT: '}${escapeHtml(ev.status)}
                                    </span>
                                    <span style="font-size: 10px; font-family: monospace; color: #6F756C;">${escapeHtml(ev.event_time)}</span>
                                </div>
                                <div style="font-weight: 700; color: #1C1E1B; font-size: 12px;">${escapeHtml(ev.location_name)}</div>
                                <div style="margin-top: 4px; font-size: 11px; color: #4F594D; background: #FAF9F5; padding: 6px; border-radius: 6px; border: 1px solid #DFE1D8; line-height: 1.35;">
                                    ${escapeHtml(ev.description)}
                                </div>
                            </div>
                        `;

                        L.marker(evLatLng, { icon: evIcon }).addTo(map).bindPopup(evPopup);
                    });
                }

                // 3. Pasang Marker Destination Hub
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
                        <div style="font-size: 12px; line-height: 1.4; min-width: 200px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                                <span style="font-size: 10px; font-weight: 800; background: #E6F0E9; color: #2F6846; padding: 2px 8px; border-radius: 9999px; text-transform: uppercase;">DESTINATION</span>
                                <span style="font-size: 11px; font-weight: bold; font-family: monospace; color: #6F756C;">[${escapeHtml(mapData.destination.code)}]</span>
                            </div>
                            <div style="font-weight: 800; color: #2F6846; font-size: 13px;">${escapeHtml(mapData.destination.name)}</div>
                            <div style="color: #6F756C; font-size: 11px; margin-top: 2px;">Tipe: <strong>${escapeHtml(mapData.destination.type)}</strong></div>
                            <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #DFE1D8; font-size: 11px; color: #1C1E1B;">
                                <div><span style="color: #6F756C;">Target Tiba:</span> <strong>${escapeHtml(mapData.destination.expected_arrival || '-')}</strong></div>
                                <div><span style="color: #6F756C;">Estimasi Tiba:</span> <strong>${escapeHtml(mapData.destination.estimated_arrival || '-')}</strong></div>
                            </div>
                        </div>
                    `;

                    L.marker(destLatLng, { icon: destIcon }).addTo(map).bindPopup(destPopup);
                }

                // 4. Garis Polylines Rute
                // Rute yang telah dilalui: Solid line (#243329)
                if (traveledPoints.length >= 2) {
                    L.polyline(traveledPoints, {
                        color: '#243329',
                        weight: 3.5,
                        opacity: 0.85
                    }).addTo(map);
                }

                // Garis putus-putus (Dashed line) menuju Destination Hub (#556E5C)
                if (traveledPoints.length > 0 && destLatLng) {
                    const lastTraveled = traveledPoints[traveledPoints.length - 1];
                    L.polyline([lastTraveled, destLatLng], {
                        color: '#556E5C',
                        weight: 3,
                        dashArray: '6, 8',
                        opacity: 0.85
                    }).addTo(map);
                } else if (traveledPoints.length === 0 && mapData.origin && mapData.origin.latitude !== null && destLatLng) {
                    // Fallback jika belum ada checkpoint
                    L.polyline([[mapData.origin.latitude, mapData.origin.longitude], destLatLng], {
                        color: '#6F756C',
                        weight: 2.5,
                        dashArray: '6, 8',
                        opacity: 0.7
                    }).addTo(map);
                }

                // 5. Fit Bounds ke seluruh koordinat
                if (boundsPoints.length > 1) {
                    map.fitBounds(L.latLngBounds(boundsPoints), {
                        padding: [45, 45],
                        maxZoom: 13
                    });
                } else if (boundsPoints.length === 1) {
                    map.setView(boundsPoints[0], 9);
                }

                function escapeHtml(str) {
                    if (!str) return '';
                    const p = document.createElement('p');
                    p.textContent = str;
                    return p.innerHTML;
                }
            });
        </script>
    @endif
</body>
</html>
