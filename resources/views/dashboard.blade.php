@extends('layouts.app')

@section('title', 'Dashboard Analitik')

@section('content')
<div class="space-y-6">

    <!-- Row 1: 4 Baris KPI Dashboard (Persis Mengikuti Referensi Desain) -->
    <div id="dashboard-kpi-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- 1. Total Shipment (Spent this month style) -->
        <div class="kpi-card bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-[#6F756C] uppercase tracking-wider block">Total Shipment</span>
                <div class="text-3xl font-extrabold text-[#1C1E1B] tracking-tight">
                    <span class="kpi-count-up notranslate" data-target="{{ $kpis['total_shipments'] }}">{{ number_format($kpis['total_shipments']) }}</span>
                </div>
                <div class="text-[11px] text-[#6F756C]">Total volume terdata</div>
            </div>
            <!-- Mini bar chart decorative SVG -->
            <div class="flex items-end space-x-1 h-9 px-2">
                <div class="w-1.5 bg-[#DFE1D8] rounded-full h-4"></div>
                <div class="w-1.5 bg-[#556E5C] rounded-full h-8"></div>
                <div class="w-1.5 bg-[#DFE1D8] rounded-full h-5"></div>
                <div class="w-1.5 bg-[#1C1E1B] rounded-full h-9"></div>
                <div class="w-1.5 bg-[#DFE1D8] rounded-full h-6"></div>
            </div>
        </div>

        <!-- 2. In Transit (New clients style) -->
        <div class="kpi-card bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-full bg-[#F4EFE6] text-[#9A6721] flex items-center justify-center text-xs">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-[#6F756C] uppercase tracking-wider">In Transit</span>
                </div>
                <div class="text-3xl font-extrabold text-[#1C1E1B] tracking-tight">
                    <span class="kpi-count-up notranslate" data-target="{{ $kpis['in_transit'] }}">{{ number_format($kpis['in_transit']) }}</span>
                </div>
                <div class="text-[11px] text-[#9A6721] font-medium">Dalam rute perjalanan</div>
            </div>
            <!-- Mini curve decorative SVG -->
            <div class="w-16 h-8 text-[#9A6721]/60">
                <svg viewBox="0 0 64 28" fill="none" class="w-full h-full stroke-current" stroke-width="2.5" stroke-linecap="round">
                    <path d="M2 24 C 18 24, 26 4, 42 16 C 50 22, 58 10, 62 4" />
                </svg>
            </div>
        </div>

        <!-- 3. Delivered (Earnings style) -->
        <div class="kpi-card bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-full bg-[#E6F0E9] text-[#2F6846] flex items-center justify-center text-xs">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-[#6F756C] uppercase tracking-wider">Delivered</span>
                </div>
                <div class="text-3xl font-extrabold text-[#1C1E1B] tracking-tight">
                    <span class="kpi-count-up notranslate" data-target="{{ $kpis['delivered'] }}">{{ number_format($kpis['delivered']) }}</span>
                </div>
                <div class="text-[11px] text-[#2F6846] font-medium">Tiba sukses di tujuan</div>
            </div>
            <!-- Progress Pill Indicator -->
            <div class="w-14 h-14 rounded-full bg-[#E6F0E9] flex flex-col items-center justify-center text-[#2F6846] font-bold text-xs border border-[#C8DACD]">
                <span>{{ $kpis['total_shipments'] > 0 ? round(($kpis['delivered'] / $kpis['total_shipments']) * 100) : 0 }}%</span>
                <span class="text-[9px] font-normal uppercase text-[#2F6846]/75">Done</span>
            </div>
        </div>

        <!-- 4. Highlight Sage Card (Kartu Paling Kanan Sesuai Referensi) -->
        <div class="kpi-card bg-gradient-to-br from-[#7B9581] to-[#4E6756] text-white rounded-[24px] p-6 shadow-sm flex items-center justify-between relative overflow-hidden hover:shadow-md transition">
            <div class="space-y-1 relative z-10">
                <span class="text-xs font-semibold text-white/80 uppercase tracking-wider block">Operational Impact</span>
                <div class="text-3xl font-extrabold text-white tracking-tight">
                    <span class="kpi-count-up notranslate" data-target="{{ $kpis['delayed'] }}">{{ number_format($kpis['delayed']) }}</span>
                    <span class="text-xs font-normal text-white/80">delayed</span>
                </div>
                <div class="text-[11px] text-white/90">
                    {{ $kpis['open_exceptions'] }} active exceptions &bull; {{ number_format($kpis['delay_rate'], 1) }}% rate
                </div>
            </div>
            <!-- Wavy Smooth Curve SVG in Highlight Card -->
            <div class="w-20 h-10 text-white/80 relative z-10">
                <svg viewBox="0 0 80 32" fill="none" class="w-full h-full stroke-current" stroke-width="2.5" stroke-linecap="round">
                    <path d="M2 20 Q 20 2, 40 18 T 78 8" />
                </svg>
            </div>
            <!-- Background Glow -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
        </div>

    </div>

    @if(!auth()->user()->isCustomer())
    <!-- Control Tower Analytics Grid (Interactive Thin Ring & Horizontal Ranked Allocation Panel) -->
    <div id="control-tower-analytics" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Kartu 1: Komposisi & Status Kargo (Interactive Thin Ring + Live Center KPI) - Col 6/12 -->
        <div class="control-tower-card lg:col-span-6 bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] flex flex-col justify-between h-full hover:shadow-md transition">
            <!-- Header Kartu Kiri -->
            <div class="flex items-center justify-between pb-3.5 border-b border-[#DFE1D8]/60 mb-4">
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-base font-bold text-[#1C2820] tracking-tight">Komposisi Status Kargo</h2>
                        <span class="sr-only">Distribusi Status Pengiriman</span>
                    </div>
                    <p class="text-xs text-[#6F756C] mt-0.5">Distribusi Status Pengiriman &amp; ketepatan waktu pengiriman</p>
                </div>
                <!-- Pill Switcher Status vs SLA Delay -->
                <div class="bg-[#F1F3EC] p-1 rounded-full inline-flex text-xs font-medium border border-[#DFE1D8]/60">
                    <button type="button" id="card1-tab-status" class="card1-tab-btn px-3 py-1 rounded-full font-semibold bg-[#243329] text-white shadow-xs transition-all duration-200">
                        Status
                    </button>
                    <button type="button" id="card1-tab-sla" class="card1-tab-btn px-3 py-1 rounded-full font-medium text-[#6F756C] hover:text-[#1C2820] transition-all duration-200">
                        SLA Delay
                    </button>
                </div>
            </div>

            <!-- Isi Kartu Kiri (2 Sisi: Ring SVG Kiri & List Detail Kanan) -->
            <div class="my-auto py-2">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center">
                    
                    <!-- Sisi Kiri (sm:col-span-5): Custom Interactive Thin SVG Ring -->
                    <div class="sm:col-span-5 flex flex-col items-center justify-center relative">
                        <div class="relative w-44 h-44 sm:w-48 sm:h-48 flex items-center justify-center">
                            <svg id="cargo-ring-svg" class="w-full h-full -rotate-90 transform" viewBox="0 0 200 200">
                                <!-- Base background track ring -->
                                <circle cx="100" cy="100" r="76" stroke="#ECEEE8" stroke-width="13" fill="none" />
                                <!-- Dynamic Segments Container -->
                                <g id="cargo-ring-segments"></g>
                            </svg>

                            <!-- Live Center KPI -->
                            <div id="cargo-ring-center" class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none px-2 select-none">
                                <span id="ring-center-value" class="text-3xl font-extrabold text-[#1C2820] tracking-tight leading-none transition-all duration-150">
                                    {{ $kpis['total_shipments'] }}
                                </span>
                                <span id="ring-center-label" class="text-[11px] font-medium text-[#6F756C] mt-1.5 transition-all duration-150 truncate max-w-[130px]">
                                    Total Kargo
                                </span>
                                <span id="ring-center-badge" class="mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F4EE] text-[#556E5C] border border-[#DFE1D8]/60 transition-all duration-150">
                                    100%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan (sm:col-span-7): Daftar Rincian Interaktif -->
                    <div class="sm:col-span-7 flex flex-col justify-center space-y-1 min-h-[190px]" id="cargo-detail-list">
                        <!-- Populated by JavaScript with active rows and micro progress bars -->
                    </div>

                </div>
            </div>

            <!-- Footer Kartu Kiri -->
            <div class="pt-3.5 border-t border-[#DFE1D8]/60 flex items-center justify-between text-xs text-[#6F756C] mt-3">
                <span id="cargo-ring-footer-text">Total Volume: <strong class="text-[#1C2820]">{{ $kpis['total_shipments'] }} Kargo</strong></span>
                <span class="text-[11px] text-[#889E8B] flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#889E8B]"></span>
                    <span>Interaktif &bull; Hover Segmen</span>
                </span>
            </div>
        </div>

        <!-- Kartu 2: Alokasi Carrier & Moda (Horizontal Ranked Allocation Panel) - Col 6/12 -->
        <div class="control-tower-card lg:col-span-6 bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] flex flex-col justify-between h-full hover:shadow-md transition">
            <!-- Header Kartu Kanan -->
            <div class="flex items-center justify-between pb-3.5 border-b border-[#DFE1D8]/60 mb-4">
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-base font-bold text-[#1C2820] tracking-tight">Alokasi Carrier &amp; Moda</h2>
                        <span class="sr-only">Distribusi Shipment Berdasarkan Carrier</span>
                    </div>
                    <p class="text-xs text-[#6F756C] mt-0.5">Distribusi Shipment Berdasarkan Carrier &amp; jaringan moda</p>
                </div>
                <!-- Pill Switcher Carrier vs Moda -->
                <div class="bg-[#F1F3EC] p-1 rounded-full inline-flex text-xs font-medium border border-[#DFE1D8]/60">
                    <button type="button" id="card2-tab-carrier" class="card2-tab-btn px-3 py-1 rounded-full font-semibold bg-[#243329] text-white shadow-xs transition-all duration-200">
                        Carrier
                    </button>
                    <button type="button" id="card2-tab-mode" class="card2-tab-btn px-3 py-1 rounded-full font-medium text-[#6F756C] hover:text-[#1C2820] transition-all duration-200">
                        Moda
                    </button>
                </div>
            </div>

            <!-- Isi Kartu Kanan: Daftar Bar Horizontal Modern -->
            <div class="my-auto py-2">
                <div id="allocation-items-container" class="space-y-3">
                    <!-- Populated by JavaScript with Horizontal Ranked Allocation Rows -->
                </div>
            </div>

            <!-- Footer Kartu Kanan -->
            <div class="pt-3.5 border-t border-[#DFE1D8]/60 flex items-center justify-between text-xs text-[#6F756C] mt-3">
                <span id="allocation-footer-summary">Total Carrier Terlibat: <strong class="text-[#1C2820]">{{ count($carrierDistribution) }} Carrier</strong></span>
                <a href="{{ route('shipments.index') }}" class="inline-flex items-center px-3 py-1 rounded-full bg-[#F1F4EE] text-[#243329] border border-[#DFE1D8] text-xs font-medium hover:bg-[#E4ECE0] transition-colors">
                    Daftar Pengiriman
                </a>
            </div>
        </div>

    </div>
    @endif

    <!-- Kartu Peta Sebaran Pengiriman Global (Leaflet.js) -->
    <div id="dashboard-map-card" class="bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] relative">
        <!-- Header Kartu -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-[#DFE1D8]/60 gap-4 mb-4">
            <div>
                <div class="flex items-center space-x-2.5">
                    <h2 class="text-base font-bold text-[#1C1E1B] tracking-tight">
                        {{ $user->isCustomer() ? 'Peta Sebaran Pengiriman Saya' : 'Peta Sebaran Pengiriman Global' }}
                    </h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F5F6F1] text-[#556E5C] border border-[#DFE1D8]">
                        <span id="map-shipment-count">{{ count($mapShipments) }}</span>&nbsp;Pengiriman Tampil
                    </span>
                </div>
                <p class="text-xs text-[#6F756C] mt-1">
                    Seluruh posisi dan rute ditarik langsung dari data operasional aktual sistem <span class="notranslate">Trackivo</span>.
                </p>
            </div>

            <!-- Toolbar Filter Sisi Klien & Tombol Reset -->
            <div class="flex flex-wrap items-center gap-1.5">
                <button type="button" data-filter="all" class="map-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#1C1E1B] text-white shadow-xs transition">
                    Semua
                </button>
                <button type="button" data-filter="OCEAN" class="map-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F5F6F1] text-[#6F756C] hover:bg-[#DFE1D8] hover:text-[#1C1E1B] transition">
                    Ocean
                </button>
                <button type="button" data-filter="AIR" class="map-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F5F6F1] text-[#6F756C] hover:bg-[#DFE1D8] hover:text-[#1C1E1B] transition">
                    Air
                </button>
                <button type="button" data-filter="LAND" class="map-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F5F6F1] text-[#6F756C] hover:bg-[#DFE1D8] hover:text-[#1C1E1B] transition">
                    Land
                </button>
                <button type="button" data-filter="delayed" class="map-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F5F6F1] text-[#6F756C] hover:bg-[#DFE1D8] hover:text-[#1C1E1B] transition">
                    Delayed
                </button>
                <button type="button" id="map-reset-btn" title="Reset Tampilan Peta" class="inline-flex items-center space-x-1 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F5F6F1] hover:bg-[#DFE1D8] text-[#1C1E1B] transition ml-1">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reset Tampilan</span>
                </button>
            </div>
        </div>

        <!-- Wadah Peta Leaflet.js -->
        <div class="relative rounded-[20px] overflow-hidden border border-[#DFE1D8]/80">
            <div id="dashboard-global-map" style="height: 440px; width: 100%;" class="z-10"></div>

            <!-- Empty notice overlay jika tidak ada pengiriman yang tampil -->
            <div id="map-empty-overlay" style="display: none;" class="absolute inset-0 bg-[#E9EAE4]/60 backdrop-blur-xs z-20 flex items-center justify-center pointer-events-none">
                <div class="bg-white/95 backdrop-blur-md px-5 py-3 rounded-2xl shadow-lg border border-[#DFE1D8] text-center">
                    <p class="text-xs font-bold text-[#1C1E1B]">Belum ada pengiriman aktif untuk ditampilkan</p>
                    <p class="text-[11px] text-[#6F756C] mt-0.5">Semua data rute kargo akan muncul secara otomatis saat shipment terdata.</p>
                </div>
            </div>
        </div>

        <!-- Legenda Ringkas Peta -->
        <div class="mt-4 pt-3.5 border-t border-[#DFE1D8]/60 flex flex-wrap items-center justify-between text-xs text-[#6F756C] gap-3">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#556E5C] inline-block"></span>
                    <span class="font-medium text-[11px]">Kapal = Ocean</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#35473A] inline-block"></span>
                    <span class="font-medium text-[11px]">Pesawat = Air</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#78927E] inline-block"></span>
                    <span class="font-medium text-[11px]">Truk = Land</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-3.5 h-3.5 rounded-full border-2 border-[#D96B6B] bg-white inline-block"></span>
                    <span class="font-medium text-[11px]">Ring Merah = Terlambat</span>
                </div>
            </div>
            <div class="text-[11px] text-[#6F756C]">
                Garis solid: Origin &rarr; Posisi Terkini &bull; Garis putus-putus: Posisi Terkini &rarr; Tujuan
            </div>
        </div>
    </div>

    <!-- Bagian Bawah Dashboard: Recent Shipments & Side Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Tabel Ringkas Pengiriman Terbaru (Col 8/12) -->
        <div class="lg:col-span-8 bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)]">
            <div class="flex items-center justify-between pb-4 border-b border-[#DFE1D8]/60">
                <div>
                    <h2 class="text-base font-bold text-[#1C1E1B] tracking-tight">
                        {{ $user->isCustomer() ? 'Pengiriman Kargo Terbaru Saya' : 'Daftar Pengiriman Terbaru' }}
                    </h2>
                    <p class="text-xs text-[#6F756C] mt-0.5">Visibilitas 6 data pengiriman kargo terbaru</p>
                </div>
                <a href="{{ route('shipments.index') }}" class="inline-flex items-center px-3 py-1 rounded-full bg-[#F1F4EE] text-[#243329] border border-[#DFE1D8] text-xs font-medium hover:bg-[#E4ECE0] transition-colors whitespace-nowrap">
                    Lihat Semua
                </a>
            </div>

            @if ($recentShipments->isEmpty())
                <div class="py-12 text-center text-[#6F756C]">
                    <svg class="h-10 w-10 mx-auto text-[#C9CCC0] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm font-medium">Belum ada data shipment yang tersedia.</p>
                </div>
            @else
                <div class="overflow-x-auto mt-4 custom-scrollbar">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[#6F756C] uppercase font-semibold border-b border-[#DFE1D8]/60 text-[11px]">
                                <th class="pb-3">Nomor Resi</th>
                                <th class="pb-3">Moda & Carrier</th>
                                <th class="pb-3">Rute</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Delay</th>
                                <th class="pb-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#DFE1D8]/40 text-[#1C1E1B]">
                            @foreach ($recentShipments as $shipment)
                                <tr class="hover:bg-[#F5F6F1] transition">
                                    <td class="py-3.5 font-bold text-[#1C1E1B]">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="font-mono text-xs hover:text-[#556E5C] transition flex items-center space-x-1 notranslate">
                                            <span>{{ $shipment->tracking_number }}</span>
                                        </a>
                                        <div class="text-[10px] text-[#6F756C] font-normal mt-0.5">
                                            {{ $shipment->cargo_type }} &bull; {{ number_format($shipment->weight_kg, 0) }} kg
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        <div class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full font-semibold text-[10px]
                                            {{ $shipment->transport_mode === 'OCEAN' ? 'bg-[#E6F0E9] text-[#2F6846]' : 
                                              ($shipment->transport_mode === 'AIR' ? 'bg-[#F4EFE6] text-[#9A6721]' : 
                                              'bg-[#EDF1EC] text-[#556E5C]') }}">
                                            <span>{{ $shipment->transport_mode }}</span>
                                        </div>
                                        <div class="text-[11px] text-[#6F756C] font-medium mt-0.5">
                                            {{ $shipment->carrier->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        <div class="font-medium text-[#1C1E1B]">
                                            <span class="notranslate">{{ $shipment->originLocation->code ?? 'Origin' }}</span>
                                            <span class="text-[#6F756C] mx-1">&rarr;</span>
                                            <span class="notranslate">{{ $shipment->destinationLocation->code ?? 'Destination' }}</span>
                                        </div>
                                        <div class="text-[10px] text-[#6F756C] mt-0.5">
                                            ETA: {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        @php
                                            $stMap = [
                                                'CREATED'    => 'bg-[#F1F2EC] text-[#6F756C]',
                                                'BOOKED'     => 'bg-[#EDF1EC] text-[#556E5C]',
                                                'PICKED_UP'  => 'bg-[#F7EFE1] text-[#9A6721]',
                                                'IN_TRANSIT' => 'bg-[#F7EFE1] text-[#9A6721]',
                                                'DELIVERED'  => 'bg-[#E6F0E9] text-[#2F6846]',
                                                'CANCELLED'  => 'bg-[#F9E6E6] text-[#B84242]',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold {{ $stMap[$shipment->status] ?? 'bg-[#F1F2EC] text-[#6F756C]' }}">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 font-medium">
                                        @if ($shipment->delay_hours > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold
                                                {{ $shipment->delay_severity === 'HIGH' ? 'bg-[#F9E6E6] text-[#B84242]' : 
                                                  ($shipment->delay_severity === 'MEDIUM' ? 'bg-[#F7EFE1] text-[#9A6721]' : 
                                                  'bg-[#F7EFE1] text-[#9A6721]') }}">
                                                +{{ $shipment->delay_hours }}h Delay
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-[#2F6846] text-[11px] font-semibold">
                                                On-time
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 text-center">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-[#F5F6F1] hover:bg-[#556E5C] hover:text-white text-[#1C1E1B] text-xs font-semibold transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Side Panel (Col 4/12) -->
        <div class="lg:col-span-4 space-y-6">
            
            @if ($user->isAdmin() || $user->isOperator())
                <!-- Panel Khusus Admin & Operator: Exception Aktif -->
                <div class="bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)]">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#DFE1D8]/60">
                        <div>
                            <h3 class="text-sm font-bold text-[#1C1E1B] uppercase tracking-wider">Exception Aktif</h3>
                            <p class="text-[11px] text-[#6F756C]">Kendala OPEN / INVESTIGATING</p>
                        </div>
                        <a href="{{ route('exceptions.index') }}" class="inline-flex items-center px-3 py-1 rounded-full bg-[#FBF1EE] text-[#A85542] border border-[#F0D6CE] text-xs font-medium hover:bg-[#F6E4DE] transition-colors whitespace-nowrap">
                            Ke Halaman Exceptions
                        </a>
                    </div>

                    @if ($activeExceptions->isEmpty())
                        <div class="py-6 text-center text-[#6F756C]">
                            <svg class="h-8 w-8 text-[#2F6846] mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs font-semibold text-[#1C1E1B]">Tidak Ada Exception Aktif</p>
                            <p class="text-[11px] text-[#6F756C] mt-0.5">Operasional pengiriman berjalan lancar tanpa anomali terbuka.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($activeExceptions as $exc)
                                <div class="p-3.5 rounded-[18px] border border-[#F0C9C9] bg-[#F9E6E6]/40 hover:bg-[#F9E6E6]/70 transition">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                            {{ $exc->severity === 'HIGH' ? 'bg-[#F9E6E6] text-[#B84242]' : ($exc->severity === 'MEDIUM' ? 'bg-[#F7EFE1] text-[#9A6721]' : 'bg-[#F1F2EC] text-[#6F756C]') }}">
                                            {{ $exc->severity }} &bull; {{ $exc->status }}
                                        </span>
                                        <span class="text-[10px] text-[#6F756C]">{{ $exc->detected_at ? $exc->detected_at->diffForHumans() : '-' }}</span>
                                    </div>
                                    <div class="text-xs font-bold text-[#1C1E1B] mt-1">
                                        {{ str_replace('_', ' ', $exc->type) }}
                                    </div>
                                    <p class="text-xs text-[#6F756C] mt-0.5 line-clamp-2 leading-relaxed">{{ $exc->description }}</p>
                                    <div class="mt-2 pt-2 border-t border-[#F0C9C9]/60 flex items-center justify-between text-[11px]">
                                        <a href="{{ route('shipments.show', $exc->shipment) }}" class="font-mono text-[#556E5C] hover:underline font-semibold notranslate">
                                            {{ $exc->shipment->tracking_number ?? 'Shipment' }}
                                        </a>
                                        <span class="text-[#B84242] font-bold">+{{ $exc->delay_impact_hours }}h Impact</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Notifikasi & Pembaruan -->
            <div class="bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)]">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#DFE1D8]/60">
                    <h3 class="text-sm font-bold text-[#1C1E1B] uppercase tracking-wider">Pembaruan Notifikasi</h3>
                    <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-3 py-1 rounded-full bg-[#F1F4EE] text-[#243329] border border-[#DFE1D8] text-xs font-medium hover:bg-[#E4ECE0] transition-colors whitespace-nowrap">
                        Semua Notifikasi
                    </a>
                </div>

                @if ($recentNotifications->isEmpty())
                    <p class="text-xs text-[#6F756C] text-center py-4">Belum ada notifikasi.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($recentNotifications as $notif)
                            <div class="p-3.5 rounded-[18px] border {{ $notif->is_read ? 'bg-[#F5F6F1] border-[#DFE1D8] text-[#6F756C]' : 'bg-[#FAF9F5] border-[#D4AF77]/60 text-[#1C1E1B]' }} transition">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-xs">{{ $notif->title }}</span>
                                    <span class="text-[10px] text-[#6F756C]">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-[#6F756C] leading-relaxed">{{ $notif->message }}</p>
                                @if($notif->shipment)
                                    <div class="mt-2 pt-1 text-[11px]">
                                        <a href="{{ route('shipments.show', $notif->shipment) }}" class="text-[#556E5C] font-semibold hover:underline">
                                            Lihat Resi <span class="notranslate">{{ $notif->shipment->tracking_number }}</span> &rarr;
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

@push('styles')
<!-- Leaflet.js Official Stylesheet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-popup-content-wrapper {
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(28, 30, 27, 0.1), 0 8px 10px -6px rgba(28, 30, 27, 0.08);
        padding: 4px;
        background: #FFFFFF;
        border: 1px solid #DFE1D8;
    }
    .leaflet-popup-content {
        margin: 12px 14px;
        line-height: 1.5;
    }
    .leaflet-container {
        font-family: inherit;
    }
    .trackivo-map-marker {
        background: transparent;
        border: none;
    }
</style>
@endpush

@push('scripts')
<!-- Leaflet.js Official CDN -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // =========================================================================
        // TRACKIVO INTERACTIVE CONTROL TOWER ANALYTICS (NATURAL SAGE & SAND THEME)
        // =========================================================================
        const controlTower = document.getElementById('control-tower-analytics');

        if (controlTower) {
            // Palet Natural Sage & Sand Resmi Trackivo
            const PALETTE = {
                deepSage: '#243329',
                darkSage: '#3E5245',
                sage: '#556E5C',
                softSage: '#889E8B',
                lightSage: '#C8D3BE',
                warmSand: '#C5A572',
                sandLight: '#D4AF77',
                terracotta: '#B96A55',
                track: '#ECEEE8',
                trackLight: '#EFEFE9',
                textDark: '#1C2820',
                textMuted: '#6F756C',
                bgHighlight: '#F7F8F4',
                bgPill: '#F1F4EE',
                white: '#FFFFFF'
            };

            // Data Mentah dari Backend Controller
            const rawStatus = @json($statusDistribution);
            const rawCarrierDist = @json($carrierDistribution);
            const rawCarrierStats = @json($carrierStats ?? []);
            const rawModeDist = @json($modeDistribution ?? []);
            const rawSlaDist = @json($slaDistribution ?? []);
            const totalShipments = {{ (int) $kpis['total_shipments'] }};
            const delayedCount = {{ (int) $kpis['delayed'] }};
            const onTimeCount = Math.max(0, totalShipments - delayedCount);

            // ---------------------------------------------------------------------
            // KARTU 1: KOMPOSISI & STATUS KARGO (SVG THIN RING + LIVE CENTER KPI)
            // ---------------------------------------------------------------------
            let card1CurrentTab = 'status'; // 'status' | 'sla'

            const statusItemsConfig = [
                { key: 'CREATED', label: 'Created', color: PALETTE.softSage, value: Number(rawStatus['CREATED'] || 0) },
                { key: 'BOOKED', label: 'Booked', color: PALETTE.sage, value: Number(rawStatus['BOOKED'] || 0) },
                { key: 'PICKED_UP', label: 'Picked Up', color: PALETTE.warmSand, value: Number(rawStatus['PICKED_UP'] || 0) },
                { key: 'IN_TRANSIT', label: 'In Transit', color: PALETTE.darkSage, value: Number(rawStatus['IN_TRANSIT'] || 0) },
                { key: 'DELIVERED', label: 'Delivered', color: PALETTE.deepSage, value: Number(rawStatus['DELIVERED'] || 0) },
                { key: 'CANCELLED', label: 'Cancelled', color: PALETTE.terracotta, value: Number(rawStatus['CANCELLED'] || 0) },
            ];

            const slaItemsConfig = [
                { key: 'ON_TIME', label: 'On-Time', color: PALETTE.darkSage, value: onTimeCount },
                { key: 'DELAYED', label: 'Delayed', color: PALETTE.terracotta, value: delayedCount },
            ];

            const ringSegmentsGroup = document.getElementById('cargo-ring-segments');
            const centerValEl = document.getElementById('ring-center-value');
            const centerLblEl = document.getElementById('ring-center-label');
            const centerBdgEl = document.getElementById('ring-center-badge');
            const detailListEl = document.getElementById('cargo-detail-list');
            const ringFooterTextEl = document.getElementById('cargo-ring-footer-text');
            const tabStatusBtn = document.getElementById('card1-tab-status');
            const tabSlaBtn = document.getElementById('card1-tab-sla');

            const RADIUS = 76;
            const CIRCUMFERENCE = 2 * Math.PI * RADIUS; // ~477.52

            function setCenterKpiDefault() {
                if (card1CurrentTab === 'status') {
                    centerValEl.textContent = totalShipments;
                    centerLblEl.textContent = 'Total Kargo';
                    centerBdgEl.textContent = '100%';
                    centerBdgEl.className = 'mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F4EE] text-[#556E5C] border border-[#DFE1D8]/60 transition-all duration-150';
                } else {
                    const onTimeRate = totalShipments > 0 ? Math.round((onTimeCount / totalShipments) * 100) : 0;
                    centerValEl.textContent = `${onTimeRate}%`;
                    centerLblEl.textContent = 'On-Time Rate';
                    centerBdgEl.textContent = `${onTimeCount}/${totalShipments} Kargo`;
                    centerBdgEl.className = 'mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F4EE] text-[#3E5245] border border-[#DFE1D8]/60 transition-all duration-150';
                }
            }

            function highlightItem(itemKey) {
                const items = card1CurrentTab === 'status' ? statusItemsConfig : slaItemsConfig;
                const activeItem = items.find(i => i.key === itemKey);
                if (!activeItem) return;

                const tabTotal = items.reduce((acc, curr) => acc + curr.value, 0);
                const pct = tabTotal > 0 ? Math.round((activeItem.value / tabTotal) * 100) : 0;

                // Update Live Center KPI
                centerValEl.textContent = activeItem.value;
                centerLblEl.textContent = activeItem.label;
                centerBdgEl.textContent = `${pct}%`;
                centerBdgEl.className = 'mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold text-white transition-all duration-150';
                centerBdgEl.style.backgroundColor = activeItem.color;

                // Slices opacity & thickness
                const slices = ringSegmentsGroup.querySelectorAll('circle');
                slices.forEach(s => {
                    if (s.getAttribute('data-key') === itemKey) {
                        s.setAttribute('stroke-width', '17');
                        s.style.opacity = '1';
                    } else {
                        s.setAttribute('stroke-width', '13');
                        s.style.opacity = '0.22';
                    }
                });

                // List rows dimming
                const rows = detailListEl.querySelectorAll('.cargo-detail-row');
                rows.forEach(r => {
                    if (r.getAttribute('data-key') === itemKey) {
                        r.classList.add('bg-[#F7F8F4]', 'shadow-xs');
                        r.style.opacity = '1';
                    } else {
                        r.classList.remove('bg-[#F7F8F4]', 'shadow-xs');
                        r.style.opacity = '0.45';
                    }
                });
            }

            function resetHighlight() {
                setCenterKpiDefault();
                centerBdgEl.style.backgroundColor = '';

                const slices = ringSegmentsGroup.querySelectorAll('circle');
                slices.forEach(s => {
                    s.setAttribute('stroke-width', '13');
                    s.style.opacity = '1';
                });

                const rows = detailListEl.querySelectorAll('.cargo-detail-row');
                rows.forEach(r => {
                    r.classList.remove('bg-[#F7F8F4]', 'shadow-xs');
                    r.style.opacity = '1';
                });
            }

            function renderCard1() {
                const items = card1CurrentTab === 'status' ? statusItemsConfig : slaItemsConfig;
                const tabTotal = items.reduce((acc, curr) => acc + curr.value, 0);

                // Update Center KPI & Footer
                setCenterKpiDefault();
                if (ringFooterTextEl) {
                    if (card1CurrentTab === 'status') {
                        ringFooterTextEl.innerHTML = `Total Volume: <strong class="text-[#1C2820]">${tabTotal} Kargo</strong>`;
                    } else {
                        const delayedRate = tabTotal > 0 ? ((delayedCount / tabTotal) * 100).toFixed(1) : 0;
                        ringFooterTextEl.innerHTML = `SLA Ketepatan: <strong class="text-[#1C2820]">${onTimeCount} On-Time &bull; ${delayedRate}% Delay</strong>`;
                    }
                }

                // Render SVG Segments
                ringSegmentsGroup.innerHTML = '';
                if (tabTotal === 0) {
                    // Empty state ring
                    centerValEl.textContent = '0';
                    centerLblEl.textContent = '0 Kargo';
                    centerBdgEl.textContent = '0%';
                } else {
                    const activeItems = items.filter(i => i.value > 0);
                    let accumulatedLength = 0;
                    const hasMultiple = activeItems.length > 1;
                    const gap = hasMultiple ? 3.5 : 0;

                    activeItems.forEach(item => {
                        const segRatio = item.value / tabTotal;
                        const arcLength = segRatio * CIRCUMFERENCE;
                        const dashLen = Math.max(0, arcLength - gap);
                        const dashOffset = -accumulatedLength;
                        accumulatedLength += arcLength;

                        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        circle.setAttribute('cx', '100');
                        circle.setAttribute('cy', '100');
                        circle.setAttribute('r', RADIUS);
                        circle.setAttribute('fill', 'none');
                        circle.setAttribute('stroke', item.color);
                        circle.setAttribute('stroke-width', '13');
                        circle.setAttribute('stroke-dasharray', `${dashLen} ${CIRCUMFERENCE}`);
                        circle.setAttribute('stroke-dashoffset', `${CIRCUMFERENCE}`); // for entry animation
                        circle.setAttribute('data-key', item.key);
                        circle.style.cursor = 'pointer';
                        circle.style.transition = 'stroke-dashoffset 0.75s cubic-bezier(0.16, 1, 0.3, 1), stroke-width 0.2s ease, opacity 0.2s ease';

                        circle.addEventListener('mouseenter', () => highlightItem(item.key));
                        circle.addEventListener('mouseleave', resetHighlight);

                        ringSegmentsGroup.appendChild(circle);

                        // Trigger animation
                        setTimeout(() => {
                            circle.setAttribute('stroke-dashoffset', `${dashOffset}`);
                        }, 20);
                    });
                }

                // Render List on Right Side
                detailListEl.innerHTML = '';
                if (tabTotal === 0) {
                    detailListEl.innerHTML = `
                        <div class="py-6 text-center text-[#6F756C]">
                            <svg class="h-8 w-8 text-[#C8D3BE] mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-xs font-semibold text-[#1C2820]">Belum Ada Data Kargo</p>
                            <p class="text-[11px] text-[#889E8B] mt-0.5">Analitik status akan otomatis terisi saat pengiriman dibuat.</p>
                        </div>
                    `;
                } else {
                    items.forEach(item => {
                        const pct = tabTotal > 0 ? Math.round((item.value / tabTotal) * 100) : 0;
                        const row = document.createElement('div');
                        row.className = 'cargo-detail-row group cursor-pointer px-2.5 py-1.5 rounded-xl transition-all duration-200 border border-transparent hover:border-[#DFE1D8]/60';
                        row.setAttribute('data-key', item.key);

                        row.innerHTML = `
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: ${item.color};"></span>
                                    <span class="text-xs font-semibold text-[#1C2820] group-hover:text-[#243329] transition-colors">${item.label}</span>
                                </div>
                                <div class="flex items-center space-x-1.5 text-xs">
                                    <span class="font-bold text-[#1C2820] notranslate">${item.value}</span>
                                    <span class="text-[#C8D3BE]">&bull;</span>
                                    <span class="text-[11px] font-semibold text-[#6F756C] bg-[#F1F4EE] px-1.5 py-0.2 rounded-md notranslate">${pct}%</span>
                                </div>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-[#EFEFE9] overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 ease-out" style="width: ${pct}%; background-color: ${item.color};"></div>
                            </div>
                        `;

                        row.addEventListener('mouseenter', () => highlightItem(item.key));
                        row.addEventListener('mouseleave', resetHighlight);

                        detailListEl.appendChild(row);
                    });
                }
            }

            // Card 1 Switcher Events
            tabStatusBtn.addEventListener('click', function () {
                if (card1CurrentTab === 'status') return;
                card1CurrentTab = 'status';
                tabStatusBtn.className = 'card1-tab-btn px-3 py-1 rounded-full font-semibold bg-[#243329] text-white shadow-xs transition-all duration-200';
                tabSlaBtn.className = 'card1-tab-btn px-3 py-1 rounded-full font-medium text-[#6F756C] hover:text-[#1C2820] transition-all duration-200';
                renderCard1();
            });

            tabSlaBtn.addEventListener('click', function () {
                if (card1CurrentTab === 'sla') return;
                card1CurrentTab = 'sla';
                tabSlaBtn.className = 'card1-tab-btn px-3 py-1 rounded-full font-semibold bg-[#243329] text-white shadow-xs transition-all duration-200';
                tabStatusBtn.className = 'card1-tab-btn px-3 py-1 rounded-full font-medium text-[#6F756C] hover:text-[#1C2820] transition-all duration-200';
                renderCard1();
            });

            renderCard1();


            // ---------------------------------------------------------------------
            // KARTU 2: ALOKASI CARRIER & MODA (HORIZONTAL RANKED ALLOCATION PANEL)
            // ---------------------------------------------------------------------
            let card2CurrentTab = 'carrier'; // 'carrier' | 'mode'

            // Normalisasi data carrier
            let carrierItems = [];
            if (rawCarrierStats && rawCarrierStats.length > 0) {
                carrierItems = rawCarrierStats.map(c => ({
                    code: c.code || 'CARR',
                    name: c.name,
                    total: Number(c.total || 0),
                }));
            } else if (rawCarrierDist && Object.keys(rawCarrierDist).length > 0) {
                carrierItems = Object.entries(rawCarrierDist).map(([name, total]) => ({
                    code: name.replace(/[^A-Za-z]/g, '').slice(0, 4).toUpperCase() || 'CARR',
                    name: name,
                    total: Number(total || 0),
                }));
            }
            carrierItems.sort((a, b) => b.total - a.total);

            // Data Moda Transportasi Aktual
            const modeIcons = {
                OCEAN: `<svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20 21c-1.39 0-2.78-.47-4-1.32-2.44 1.71-5.56 1.71-8 0C6.78 20.53 5.39 21 4 21H2v2h2c1.38 0 2.74-.35 4-.99 2.52 1.29 5.48 1.29 8 0 1.26.65 2.62.99 4 .99h2v-2h-2zM3.95 19H4c1.6 0 3.02-.88 4-2 .98 1.12 2.4 2 4 2s3.02-.88 4-2c.98 1.12 2.4 2 4 2h.05l1.89-6.68c.08-.26.06-.54-.06-.78s-.33-.41-.6-.46L19 13.01V5c0-.55-.45-1-1-1h-5V1h-2v3H6c-.55 0-1 .45-1 1v8.01l-2.28-.07c-.27.05-.48.22-.6.46s-.14.52-.06.78L3.95 19zM8 6h8v5.5l-4-1-4 1V6z"/></svg>`,
                AIR: `<svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>`,
                LAND: `<svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-2 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>`,
            };

            const modeItems = [
                {
                    code: 'OCEAN',
                    name: 'Ocean Freight',
                    total: Number(rawModeDist['OCEAN'] || 0),
                    icon: modeIcons.OCEAN,
                    gradient: 'from-[#243329] to-[#3E5245]'
                },
                {
                    code: 'AIR',
                    name: 'Air Freight',
                    total: Number(rawModeDist['AIR'] || 0),
                    icon: modeIcons.AIR,
                    gradient: 'from-[#3E5245] to-[#556E5C]'
                },
                {
                    code: 'LAND',
                    name: 'Land Transport',
                    total: Number(rawModeDist['LAND'] || 0),
                    icon: modeIcons.LAND,
                    gradient: 'from-[#C5A572] to-[#D4AF77]'
                },
            ];

            const allocationContainer = document.getElementById('allocation-items-container');
            const allocationSummaryEl = document.getElementById('allocation-footer-summary');
            const tabCarrierBtn = document.getElementById('card2-tab-carrier');
            const tabModeBtn = document.getElementById('card2-tab-mode');

            // Palet Gradasi Sage & Sand Bertingkat
            const rankGradients = [
                'from-[#243329] to-[#3E5245]',
                'from-[#3E5245] to-[#556E5C]',
                'from-[#556E5C] to-[#889E8B]',
                'from-[#C5A572] to-[#D4AF77]',
                'from-[#889E8B] to-[#C8D3BE]',
                'from-[#A4B3A8] to-[#DFE1D8]'
            ];

            function renderCard2() {
                allocationContainer.innerHTML = '';

                if (card2CurrentTab === 'carrier') {
                    if (allocationSummaryEl) {
                        allocationSummaryEl.innerHTML = `Total Carrier Terlibat: <strong class="text-[#1C2820]">${carrierItems.length} Carrier</strong>`;
                    }

                    if (carrierItems.length === 0) {
                        allocationContainer.innerHTML = `
                            <div class="py-6 text-center text-[#6F756C]">
                                <svg class="h-8 w-8 text-[#C8D3BE] mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="text-xs font-semibold text-[#1C2820]">Belum Ada Carrier Terdata</p>
                                <p class="text-[11px] text-[#889E8B] mt-0.5">Alokasi carrier akan muncul setelah shipment memiliki penetapan carrier.</p>
                            </div>
                        `;
                        return;
                    }

                    const maxCarrierTotal = Math.max(...carrierItems.map(c => c.total), 1);
                    const sumCarrierTotal = carrierItems.reduce((acc, curr) => acc + curr.total, 0) || totalShipments || 1;

                    carrierItems.forEach((c, idx) => {
                        const barWidth = Math.max(6, Math.round((c.total / maxCarrierTotal) * 100));
                        const sharePct = Math.round((c.total / sumCarrierTotal) * 100);
                        const grad = rankGradients[idx % rankGradients.length];

                        const row = document.createElement('div');
                        row.className = 'allocation-row group cursor-pointer py-1.5 px-2 rounded-xl transition-all duration-200 border border-transparent hover:bg-[#F7F8F4] hover:border-[#DFE1D8]/60';

                        row.innerHTML = `
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center space-x-2 min-w-0 pr-2">
                                    <span class="bg-[#F1F4EE] text-[#243329] font-bold text-[10px] px-2 py-0.5 rounded-md border border-[#DFE1D8]/70 shrink-0 notranslate">${c.code}</span>
                                    <span class="text-xs font-semibold text-[#1C2820] truncate group-hover:text-[#243329] transition-colors">${c.name}</span>
                                </div>
                                <div class="flex items-center space-x-2 shrink-0">
                                    <span class="text-xs font-bold text-[#1C2820] notranslate">${c.total} <span class="text-[10px] font-normal text-[#6F756C]">kargo</span></span>
                                    <span class="bg-[#F1F4EE] text-[#556E5C] text-[11px] font-semibold px-2 py-0.5 rounded-full notranslate border border-[#DFE1D8]/50">${sharePct}%</span>
                                </div>
                            </div>
                            <div class="h-2.5 w-full rounded-full bg-[#EFEFE9] overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r ${grad} transition-all duration-700 ease-out" style="width: 0%;" data-target-width="${barWidth}%"></div>
                            </div>
                        `;

                        // Hover focus interaktif: baris lain meredup
                        row.addEventListener('mouseenter', () => {
                            const allRows = allocationContainer.querySelectorAll('.allocation-row');
                            allRows.forEach(r => {
                                if (r !== row) r.style.opacity = '0.45';
                            });
                        });
                        row.addEventListener('mouseleave', () => {
                            const allRows = allocationContainer.querySelectorAll('.allocation-row');
                            allRows.forEach(r => r.style.opacity = '1');
                        });

                        allocationContainer.appendChild(row);

                        // Trigger smooth progress bar growth
                        setTimeout(() => {
                            const bar = row.querySelector('[data-target-width]');
                            if (bar) bar.style.width = bar.getAttribute('data-target-width');
                        }, 40 + idx * 25);
                    });
                } else {
                    // TAB MODA TRANSPORTASI
                    const activeModaCount = modeItems.filter(m => m.total > 0).length;
                    if (allocationSummaryEl) {
                        allocationSummaryEl.innerHTML = `Total Moda Aktif: <strong class="text-[#1C2820]">${activeModaCount} dari 3 Moda</strong>`;
                    }

                    const sumModeTotal = modeItems.reduce((acc, curr) => acc + curr.total, 0) || totalShipments || 1;
                    const maxModeTotal = Math.max(...modeItems.map(m => m.total), 1);

                    modeItems.forEach((m, idx) => {
                        const barWidth = Math.max(6, Math.round((m.total / maxModeTotal) * 100));
                        const sharePct = Math.round((m.total / sumModeTotal) * 100);

                        const row = document.createElement('div');
                        row.className = 'allocation-row group cursor-pointer py-1.5 px-2 rounded-xl transition-all duration-200 border border-transparent hover:bg-[#F7F8F4] hover:border-[#DFE1D8]/60';

                        row.innerHTML = `
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center space-x-2 min-w-0 pr-2">
                                    <span class="w-6 h-6 rounded-lg bg-[#F1F4EE] text-[#243329] border border-[#DFE1D8]/70 flex items-center justify-center shrink-0">
                                        ${m.icon}
                                    </span>
                                    <span class="text-xs font-semibold text-[#1C2820] truncate group-hover:text-[#243329] transition-colors">${m.name}</span>
                                </div>
                                <div class="flex items-center space-x-2 shrink-0">
                                    <span class="text-xs font-bold text-[#1C2820] notranslate">${m.total} <span class="text-[10px] font-normal text-[#6F756C]">kargo</span></span>
                                    <span class="bg-[#F1F4EE] text-[#556E5C] text-[11px] font-semibold px-2 py-0.5 rounded-full notranslate border border-[#DFE1D8]/50">${sharePct}%</span>
                                </div>
                            </div>
                            <div class="h-2.5 w-full rounded-full bg-[#EFEFE9] overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r ${m.gradient} transition-all duration-700 ease-out" style="width: 0%;" data-target-width="${barWidth}%"></div>
                            </div>
                        `;

                        row.addEventListener('mouseenter', () => {
                            const allRows = allocationContainer.querySelectorAll('.allocation-row');
                            allRows.forEach(r => {
                                if (r !== row) r.style.opacity = '0.45';
                            });
                        });
                        row.addEventListener('mouseleave', () => {
                            const allRows = allocationContainer.querySelectorAll('.allocation-row');
                            allRows.forEach(r => r.style.opacity = '1');
                        });

                        allocationContainer.appendChild(row);

                        setTimeout(() => {
                            const bar = row.querySelector('[data-target-width]');
                            if (bar) bar.style.width = bar.getAttribute('data-target-width');
                        }, 40 + idx * 40);
                    });
                }
            }

            // Card 2 Switcher Events
            tabCarrierBtn.addEventListener('click', function () {
                if (card2CurrentTab === 'carrier') return;
                card2CurrentTab = 'carrier';
                tabCarrierBtn.className = 'card2-tab-btn px-3 py-1 rounded-full font-semibold bg-[#243329] text-white shadow-xs transition-all duration-200';
                tabModeBtn.className = 'card2-tab-btn px-3 py-1 rounded-full font-medium text-[#6F756C] hover:text-[#1C2820] transition-all duration-200';
                renderCard2();
            });

            tabModeBtn.addEventListener('click', function () {
                if (card2CurrentTab === 'mode') return;
                card2CurrentTab = 'mode';
                tabModeBtn.className = 'card2-tab-btn px-3 py-1 rounded-full font-semibold bg-[#243329] text-white shadow-xs transition-all duration-200';
                tabCarrierBtn.className = 'card2-tab-btn px-3 py-1 rounded-full font-medium text-[#6F756C] hover:text-[#1C2820] transition-all duration-200';
                renderCard2();
            });

            renderCard2();
        }

        // 3. Inisialisasi Peta Sebaran Pengiriman Global (Leaflet.js)
        const mapElement = document.getElementById('dashboard-global-map');
        if (!mapElement) return;

        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        const map = L.map('dashboard-global-map', {
            scrollWheelZoom: false,
            attributionControl: true
        }).setView([15, 10], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
        }).addTo(map);

        const routesLayer = L.layerGroup().addTo(map);
        const markersLayer = L.layerGroup().addTo(map);

        const mapShipmentsData = @json($mapShipments);

        function createPopupContent(s) {
            const delayBadge = s.delay_hours > 0
                ? `<span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:10px;font-weight:700;background:#F9E6E6;color:#B84242;border:1px solid #F0C9C9;">+${s.delay_hours}h (${escapeHtml(s.delay_severity)})</span>`
                : `<span style="display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:10px;font-weight:700;background:#E6F0E9;color:#2F6846;border:1px solid #C8DACD;">On-Time</span>`;

            const statusBadgeColors = {
                'CREATED': 'background:#F1F2EC;color:#6F756C;border:1px solid #DFE1D8;',
                'BOOKED': 'background:#EDF1EC;color:#556E5C;border:1px solid #DFE1D8;',
                'PICKED_UP': 'background:#F7EFE1;color:#9A6721;border:1px solid #E6D8BF;',
                'IN_TRANSIT': 'background:#F7EFE1;color:#9A6721;border:1px solid #E6D8BF;',
                'DELIVERED': 'background:#E6F0E9;color:#2F6846;border:1px solid #C8DACD;',
                'CANCELLED': 'background:#F9E6E6;color:#B84242;border:1px solid #F0C9C9;',
            };
            const stStyle = statusBadgeColors[s.status] || 'background:#F1F2EC;color:#6F756C;border:1px solid #DFE1D8;';

            return `
                <div style="font-family: inherit; font-size: 12px; line-height: 1.4; min-width: 230px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <span style="font-family: monospace; font-size: 11px; font-weight: 800; color: #1C1E1B;">${escapeHtml(s.tracking_number)}</span>
                        <span style="font-size: 9px; font-weight: 700; text-transform: uppercase; padding: 2px 8px; border-radius: 9999px; ${stStyle}">${escapeHtml(s.status)}</span>
                    </div>
                    <div style="font-size: 11px; color: #6F756C; margin-bottom: 6px;">
                        <span style="font-weight: 700; color: #1C1E1B;">${escapeHtml(s.transport_mode)}</span> &bull; ${escapeHtml(s.carrier_name)}
                    </div>
                    <div style="background: #F5F6F1; border-radius: 12px; padding: 6px 10px; margin-bottom: 8px; border: 1px solid #DFE1D8;">
                        <div style="display: flex; align-items: center; justify-content: space-between; font-weight: 700; color: #1C1E1B; font-size: 11px;">
                            <span>${escapeHtml(s.origin.code)}</span>
                            <span style="color: #6F756C; font-weight: normal;">&rarr;</span>
                            <span>${escapeHtml(s.destination.code)}</span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; color: #6F756C; font-size: 10px; margin-top: 2px;">
                            <span class="truncate" style="max-width: 100px;">${escapeHtml(s.origin.name)}</span>
                            <span class="truncate" style="max-width: 100px; text-align: right;">${escapeHtml(s.destination.name)}</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 11px; color: #6F756C;">Status Delay:</span>
                        ${delayBadge}
                    </div>
                    <a href="${escapeHtml(s.detail_url)}" style="display: block; width: 100%; text-align: center; padding: 7px 12px; background: #556E5C; color: #ffffff; border-radius: 9999px; font-weight: 700; font-size: 11px; text-decoration: none; transition: background 0.2s;">
                        Lihat Detail Pengiriman &rarr;
                    </a>
                </div>
            `;
        }

        function renderMapShipments(filter = 'all') {
            markersLayer.clearLayers();
            routesLayer.clearLayers();

            const filtered = mapShipmentsData.filter(s => {
                if (filter === 'all') return true;
                if (filter === 'OCEAN') return s.transport_mode === 'OCEAN';
                if (filter === 'AIR') return s.transport_mode === 'AIR';
                if (filter === 'LAND') return s.transport_mode === 'LAND';
                if (filter === 'delayed') {
                    return (s.delay_hours > 0) || (s.delay_severity === 'MEDIUM') || (s.delay_severity === 'HIGH');
                }
                return true;
            });

            const countElem = document.getElementById('map-shipment-count');
            if (countElem) {
                countElem.textContent = filtered.length;
            }

            const emptyNotice = document.getElementById('map-empty-overlay');

            if (filtered.length === 0) {
                if (emptyNotice) emptyNotice.style.display = 'flex';
                map.setView([15, 10], 2);
                return;
            } else {
                if (emptyNotice) emptyNotice.style.display = 'none';
            }

            const boundsPoints = [];

            filtered.forEach(s => {
                const originLatLng = [s.origin.lat, s.origin.lng];
                const destLatLng = [s.destination.lat, s.destination.lng];
                const currentLatLng = [s.current.lat, s.current.lng];

                boundsPoints.push(originLatLng, destLatLng, currentLatLng);

                const modeColor = s.transport_mode === 'OCEAN' ? '#556E5C' : (s.transport_mode === 'AIR' ? '#35473A' : '#78927E');

                // Garis solid halus Origin -> Current
                const traveledLine = L.polyline([originLatLng, currentLatLng], {
                    color: modeColor,
                    weight: 2.5,
                    opacity: 0.85,
                });
                routesLayer.addLayer(traveledLine);

                // Garis putus-putus halus Current -> Destination
                const remainingLine = L.polyline([currentLatLng, destLatLng], {
                    color: modeColor,
                    weight: 2,
                    opacity: 0.65,
                    dashArray: '6, 6',
                });
                routesLayer.addLayer(remainingLine);

                // Titik kecil Origin
                const originPoint = L.circleMarker(originLatLng, {
                    radius: 4.5,
                    fillColor: '#243329',
                    color: '#ffffff',
                    weight: 2,
                    fillOpacity: 0.9,
                }).bindTooltip(`${escapeHtml(s.origin.code)} (Origin)`, { direction: 'top', offset: [0, -4] });
                routesLayer.addLayer(originPoint);

                // Titik kecil Destination
                const destPoint = L.circleMarker(destLatLng, {
                    radius: 4.5,
                    fillColor: '#6F756C',
                    color: '#ffffff',
                    weight: 2,
                    fillOpacity: 0.9,
                }).bindTooltip(`${escapeHtml(s.destination.code)} (Destination)`, { direction: 'top', offset: [0, -4] });
                routesLayer.addLayer(destPoint);

                // Custom DivIcon Marker Current Location
                const isDelayed = s.delay_severity === 'HIGH' || s.delay_severity === 'MEDIUM';
                const ringBorder = s.delay_severity === 'HIGH' ? '#D96B6B' : (s.delay_severity === 'MEDIUM' ? '#D4AF77' : '#ffffff');
                const ringShadow = isDelayed
                    ? `0 0 0 4px ${s.delay_severity === 'HIGH' ? 'rgba(217,107,107,0.35)' : 'rgba(212,175,119,0.35)'}, 0 4px 12px rgba(28,30,27,0.2)`
                    : '0 4px 10px rgba(28,30,27,0.15)';

                let iconSvg = '';
                if (s.transport_mode === 'OCEAN') {
                    iconSvg = `<svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor"><path d="M20 21c-1.39 0-2.78-.47-4-1.32-2.44 1.71-5.56 1.71-8 0C6.78 20.53 5.39 21 4 21H2v2h2c1.38 0 2.74-.35 4-.99 2.52 1.29 5.48 1.29 8 0 1.26.65 2.62.99 4 .99h2v-2h-2zM3.95 19H4c1.6 0 3.02-.88 4-2 .98 1.12 2.4 2 4 2s3.02-.88 4-2c.98 1.12 2.4 2 4 2h.05l1.89-6.68c.08-.26.06-.54-.06-.78s-.33-.41-.6-.46L19 13.01V5c0-.55-.45-1-1-1h-5V1h-2v3H6c-.55 0-1 .45-1 1v8.01l-2.28-.07c-.27.05-.48.22-.6.46s-.14.52-.06.78L3.95 19zM8 6h8v5.5l-4-1-4 1V6z"/></svg>`;
                } else if (s.transport_mode === 'AIR') {
                    iconSvg = `<svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>`;
                } else {
                    iconSvg = `<svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="currentColor"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-2 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>`;
                }

                const markerHtml = `
                    <div style="background-color: ${modeColor}; color: #ffffff; width: 34px; height: 34px; border-radius: 50%; border: 2.5px solid ${ringBorder}; box-shadow: ${ringShadow}; display: flex; align-items: center; justify-content: center; position: relative;">
                        ${iconSvg}
                        ${isDelayed ? `<span style="position: absolute; top: -3px; right: -3px; width: 10px; height: 10px; background-color: ${ringBorder}; border-radius: 50%; border: 1.5px solid #ffffff;"></span>` : ''}
                    </div>
                `;

                const customIcon = L.divIcon({
                    className: 'trackivo-map-marker',
                    html: markerHtml,
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                    popupAnchor: [0, -20],
                });

                const marker = L.marker(currentLatLng, { icon: customIcon });
                marker.bindPopup(createPopupContent(s));
                markersLayer.addLayer(marker);
            });

            if (boundsPoints.length > 0) {
                map.fitBounds(L.latLngBounds(boundsPoints).pad(0.12));
            }
        }

        // Filter Buttons Event Listeners
        const filterButtons = document.querySelectorAll('.map-filter-btn');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                filterButtons.forEach(b => {
                    b.classList.remove('bg-[#1C1E1B]', 'text-white', 'shadow-xs');
                    b.classList.add('bg-[#F5F6F1]', 'text-[#6F756C]', 'hover:bg-[#DFE1D8]', 'hover:text-[#1C1E1B]');
                });
                this.classList.remove('bg-[#F5F6F1]', 'text-[#6F756C]', 'hover:bg-[#DFE1D8]', 'hover:text-[#1C1E1B]');
                this.classList.add('bg-[#1C1E1B]', 'text-white', 'shadow-xs');
                renderMapShipments(filter);
            });
        });

        // Reset Button Event Listener
        const resetBtn = document.getElementById('map-reset-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                const allBtn = document.querySelector('.map-filter-btn[data-filter="all"]');
                if (allBtn) {
                    allBtn.click();
                } else {
                    renderMapShipments('all');
                }
            });
        }

        // Inisialisasi awal render peta
        renderMapShipments('all');

        setTimeout(() => {
            map.invalidateSize();
        }, 250);
    });
</script>
@endpush
