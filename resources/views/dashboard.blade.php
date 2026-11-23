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

    <!-- Chart.js Analytics Grid (2 Graphs: Status Distribution & Carrier Distribution) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Grafik 1: Distribusi Status (Doughnut Chart) - Col 5/12 -->
        <div class="chart-reveal-card lg:col-span-5 bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-[#DFE1D8]/60 mb-4">
                <div>
                    <h2 class="text-base font-bold text-[#1C1E1B] tracking-tight">Distribusi Status Pengiriman</h2>
                    <p class="text-xs text-[#6F756C] mt-0.5">Komposisi kargo berdasarkan status operasional saat ini</p>
                </div>
                <span class="p-2 rounded-xl bg-[#F5F6F1] text-[#556E5C]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </span>
            </div>

            @if($kpis['total_shipments'] === 0)
                <div class="h-64 flex flex-col items-center justify-center text-[#6F756C] text-center p-6">
                    <svg class="h-10 w-10 text-[#C9CCC0] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-sm font-semibold text-[#1C1E1B]">Belum Ada Data Pengiriman</p>
                    <p class="text-xs text-[#6F756C] mt-1">Grafik status akan otomatis terbentuk saat shipment dibuat.</p>
                </div>
            @else
                <div class="relative h-64 flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-2 pt-4 border-t border-[#DFE1D8]/60 text-[11px] text-[#6F756C] mt-3">
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-[#9EABA2] shrink-0"></span><span>Created: {{ $statusDistribution['CREATED'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-[#8FA896] shrink-0"></span><span>Booked: {{ $statusDistribution['BOOKED'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-[#D4AF77] shrink-0"></span><span>Picked Up: {{ $statusDistribution['PICKED_UP'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-[#556E5C] shrink-0"></span><span>In Transit: {{ $statusDistribution['IN_TRANSIT'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-[#2F6846] shrink-0"></span><span>Delivered: {{ $statusDistribution['DELIVERED'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-[#D96B6B] shrink-0"></span><span>Cancelled: {{ $statusDistribution['CANCELLED'] }}</span></div>
            </div>
        </div>

        <!-- Grafik 2: Distribusi Carrier (Bar Chart) - Col 7/12 -->
        <div class="chart-reveal-card lg:col-span-7 bg-white rounded-[24px] p-6 border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-[#DFE1D8]/60 mb-4">
                <div>
                    <h2 class="text-base font-bold text-[#1C1E1B] tracking-tight">Distribusi Shipment Berdasarkan Carrier</h2>
                    <p class="text-xs text-[#6F756C] mt-0.5">Alokasi muatan logistik pada masing-masing maskapai & pelayaran</p>
                </div>
                <span class="p-2 rounded-xl bg-[#F5F6F1] text-[#556E5C]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </span>
            </div>

            @if(empty($carrierDistribution))
                <div class="h-64 flex flex-col items-center justify-center text-[#6F756C] text-center p-6">
                    <svg class="h-10 w-10 text-[#C9CCC0] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-sm font-semibold text-[#1C1E1B]">Belum Ada Pengiriman Terdata</p>
                    <p class="text-xs text-[#6F756C] mt-1">Distribusi carrier akan muncul setelah shipment memiliki alokasi carrier.</p>
                </div>
            @else
                <div class="relative h-64">
                    <canvas id="carrierChart"></canvas>
                </div>
            @endif

            <div class="pt-4 border-t border-[#DFE1D8]/60 flex items-center justify-between text-xs text-[#6F756C] mt-3">
                <span>Total Carrier Terlibat: <strong class="text-[#1C1E1B]">{{ count($carrierDistribution) }} Carrier</strong></span>
                <a href="{{ route('shipments.index') }}" class="font-medium text-[#556E5C] hover:text-[#425848] transition flex items-center space-x-1">
                    <span>Lihat di daftar pengiriman</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

    </div>

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
                <a href="{{ route('shipments.index') }}" class="text-xs font-semibold px-4 py-2 rounded-full bg-[#F5F6F1] hover:bg-[#556E5C] hover:text-white text-[#1C1E1B] transition">
                    Lihat Semua &rarr;
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
                                <th class="pb-3 text-right">Aksi</th>
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
                                    <td class="py-3.5 text-right">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="px-3 py-1 rounded-full bg-[#F5F6F1] hover:bg-[#556E5C] hover:text-white text-[#1C1E1B] text-xs font-semibold transition">
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
                        <a href="{{ route('exceptions.index') }}" class="text-xs font-semibold text-[#B84242] hover:underline transition">
                            Ke Halaman Exceptions &rarr;
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
                    <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-[#556E5C] hover:text-[#425848] transition">
                        Semua Notifikasi &rarr;
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
<!-- Chart.js Official CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Leaflet.js Official CDN -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data Status dari Backend
        const statusData = @json($statusDistribution);
        const statusLabels = ['CREATED', 'BOOKED', 'PICKED UP', 'IN TRANSIT', 'DELIVERED', 'CANCELLED'];
        const statusValues = [
            statusData['CREATED'] || 0,
            statusData['BOOKED'] || 0,
            statusData['PICKED_UP'] || 0,
            statusData['IN_TRANSIT'] || 0,
            statusData['DELIVERED'] || 0,
            statusData['CANCELLED'] || 0,
        ];
        // Palet Sage & Sand Harmonik
        const statusColors = ['#9EABA2', '#8FA896', '#D4AF77', '#556E5C', '#2F6846', '#D96B6B'];

        // 1. Inisialisasi Doughnut Chart: Distribusi Status
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx && {{ $kpis['total_shipments'] }} > 0) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: statusColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1C1E1B',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) {
                                    const total = {{ $kpis['total_shipments'] }};
                                    const val = context.raw || 0;
                                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                    return ` ${val} shipment (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. Inisialisasi Bar Chart: Distribusi Carrier
        const carrierData = @json($carrierDistribution);
        const carrierLabels = Object.keys(carrierData);
        const carrierValues = Object.values(carrierData);
        const carrierCtx = document.getElementById('carrierChart');

        if (carrierCtx && carrierLabels.length > 0) {
            new Chart(carrierCtx, {
                type: 'bar',
                data: {
                    labels: carrierLabels,
                    datasets: [{
                        label: 'Jumlah Pengiriman',
                        data: carrierValues,
                        backgroundColor: '#556E5C',
                        hoverBackgroundColor: '#425848',
                        borderRadius: 12,
                        borderSkipped: false,
                        maxBarThickness: 38
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1C1E1B',
                            padding: 10,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.raw} pengiriman aktif/tercatat`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 11, weight: '600' },
                                color: '#6F756C'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: { size: 11 },
                                color: '#6F756C'
                            },
                            grid: {
                                color: '#E9EAE4'
                            }
                        }
                    }
                }
            });
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
                    fillColor: '#1C1E1B',
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
