@extends('layouts.app')

@section('title', 'Dashboard Analitik')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Welcome & Hero Header -->
    <div class="bg-gradient-to-r from-[#0f131c] via-[#141a27] to-[#1a2233] rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800/80 mb-8 relative overflow-hidden">
        <!-- Ambient background glow -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#d4f93a]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-20 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <!-- Role Tag & Live Indicator -->
                <div class="flex items-center space-x-2.5 mb-3">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ $user->isAdmin() ? 'bg-purple-500/20 text-purple-300 border border-purple-500/40' : 
                          ($user->isOperator() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 
                          'bg-[#d4f93a]/20 text-[#d4f93a] border border-[#d4f93a]/40') }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $user->isAdmin() ? 'bg-purple-400' : ($user->isOperator() ? 'bg-amber-400' : 'bg-[#d4f93a]') }} animate-ping"></span>
                        <span>Portal {{ ucfirst($user->role) }}</span>
                    </span>

                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/80 text-slate-300 text-xs">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</span>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Dashboard Analitik Kargo
                </h1>
                <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                    @if($user->isAdmin())
                        Visibilitas data operasional global terintegrasi, pemantauan anomali, metrik keterlambatan, dan distribusi armada.
                    @elseif($user->isOperator())
                        Pusat kendali operasional kargo untuk memantau pergerakan kontainer aktif, anomali kendala, dan kepatuhan jadwal tiba.
                    @else
                        Pantau seluruh pesanan kargo Anda, status perjalanan real-time, dan pembaruan estimasi waktu tiba terkini.
                    @endif
                </p>
            </div>

            <!-- Quick Account Profile Pill Card -->
            <div class="bg-black/40 backdrop-blur-md border border-white/10 rounded-2xl p-4 sm:min-w-[260px] shrink-0">
                <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Informasi Akun</div>
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Pengguna:</span>
                        <span class="text-white font-medium truncate max-w-[170px]">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Perusahaan:</span>
                        <span class="text-white font-medium">{{ $user->company_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Scope Metrik:</span>
                        <span class="text-[#d4f93a] font-semibold">
                            {{ $user->isCustomer() ? 'Data Kargo Saya' : 'Seluruh Global Kargo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 7 Metrik KPI Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3.5 mb-8">
        
        <!-- 1. Total Shipment -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Shipment</span>
                <span class="p-1 rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ number_format($kpis['total_shipments']) }}</div>
            <div class="mt-1 text-[11px] text-slate-500 truncate">Total volume terdata</div>
        </div>

        <!-- 2. In Transit -->
        <div class="bg-white rounded-2xl p-4 border border-indigo-200/80 bg-indigo-50/20 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between text-indigo-400 mb-1">
                <span class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider">In Transit</span>
                <span class="p-1 rounded-lg bg-indigo-100 text-indigo-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-extrabold text-indigo-600">{{ number_format($kpis['in_transit']) }}</div>
            <div class="mt-1 text-[11px] text-indigo-700/80 font-medium truncate">Dalam rute perjalanan</div>
        </div>

        <!-- 3. Delivered -->
        <div class="bg-white rounded-2xl p-4 border border-emerald-200/80 bg-emerald-50/20 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between text-emerald-400 mb-1">
                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Delivered</span>
                <span class="p-1 rounded-lg bg-emerald-100 text-emerald-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-extrabold text-emerald-600">{{ number_format($kpis['delivered']) }}</div>
            <div class="mt-1 text-[11px] text-emerald-700/80 font-medium truncate">Tiba sukses di tujuan</div>
        </div>

        <!-- 4. Delayed -->
        <div class="bg-white rounded-2xl p-4 border border-amber-200/80 bg-amber-50/20 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between text-amber-400 mb-1">
                <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Delayed</span>
                <span class="p-1 rounded-lg bg-amber-100 text-amber-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-extrabold text-amber-600">{{ number_format($kpis['delayed']) }}</div>
            <div class="mt-1 text-[11px] text-amber-700/80 font-medium truncate">Mengalami keterlambatan</div>
        </div>

        <!-- 5. Open Exceptions -->
        <div class="bg-white rounded-2xl p-4 border border-rose-200/80 bg-rose-50/20 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between text-rose-400 mb-1">
                <span class="text-[10px] font-bold text-rose-700 uppercase tracking-wider">Open Exceptions</span>
                <span class="p-1 rounded-lg bg-rose-100 text-rose-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ number_format($kpis['open_exceptions']) }}</div>
            <div class="mt-1 text-[11px] text-rose-700/80 font-medium truncate">Anomali aktif/investigasi</div>
        </div>

        <!-- 6. Delay Rate (%) -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Delay Rate</span>
                <span class="p-1 rounded-lg bg-amber-50 text-amber-600 text-[10px] font-bold">%</span>
            </div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ number_format($kpis['delay_rate'], 1) }}%</div>
            <div class="mt-1 text-[11px] text-slate-500 truncate">Rasio delay kargo</div>
        </div>

        <!-- 7. Exception Rate (%) -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Exception Rate</span>
                <span class="p-1 rounded-lg bg-rose-50 text-rose-600 text-[10px] font-bold">%</span>
            </div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ number_format($kpis['exception_rate'], 1) }}%</div>
            <div class="mt-1 text-[11px] text-slate-500 truncate">Kargo terdampak anomali</div>
        </div>

    </div>

    <!-- Chart.js Analytics Grid (2 Graphs) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        
        <!-- Grafik 1: Distribusi Status (Doughnut Chart) - Col 5/12 -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Distribusi Status Pengiriman</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Komposisi kargo berdasarkan status operasional saat ini</p>
                </div>
                <span class="p-1.5 rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </span>
            </div>

            @if($kpis['total_shipments'] === 0)
                <div class="h-64 flex flex-col items-center justify-center text-slate-400 text-center p-6">
                    <svg class="h-12 w-12 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-600">Belum Ada Data Pengiriman</p>
                    <p class="text-xs text-slate-400 mt-1">Grafik status akan otomatis terbentuk saat shipment dibuat.</p>
                </div>
            @else
                <div class="relative h-64 flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 text-[11px] text-slate-600 mt-3">
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-slate-400 shrink-0"></span><span>Created: {{ $statusDistribution['CREATED'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-sky-400 shrink-0"></span><span>Booked: {{ $statusDistribution['BOOKED'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-amber-400 shrink-0"></span><span>Picked Up: {{ $statusDistribution['PICKED_UP'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-indigo-500 shrink-0"></span><span>In Transit: {{ $statusDistribution['IN_TRANSIT'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-emerald-500 shrink-0"></span><span>Delivered: {{ $statusDistribution['DELIVERED'] }}</span></div>
                <div class="flex items-center space-x-1.5"><span class="h-2 w-2 rounded-full bg-rose-500 shrink-0"></span><span>Cancelled: {{ $statusDistribution['CANCELLED'] }}</span></div>
            </div>
        </div>

        <!-- Grafik 2: Distribusi Carrier (Bar Chart) - Col 7/12 -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Distribusi Shipment Berdasarkan Carrier</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Alokasi muatan logistik pada masing-masing maskapai & pelayaran</p>
                </div>
                <span class="p-1.5 rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </span>
            </div>

            @if(empty($carrierDistribution))
                <div class="h-64 flex flex-col items-center justify-center text-slate-400 text-center p-6">
                    <svg class="h-12 w-12 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-600">Belum Ada Pengiriman Terdata</p>
                    <p class="text-xs text-slate-400 mt-1">Distribusi carrier akan muncul setelah shipment memiliki alokasi carrier.</p>
                </div>
            @else
                <div class="relative h-64">
                    <canvas id="carrierChart"></canvas>
                </div>
            @endif

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 mt-3">
                <span>Total Carrier Terlibat: <strong class="text-slate-800">{{ count($carrierDistribution) }} Carrier</strong></span>
                <a href="{{ route('shipments.index') }}" class="font-medium text-slate-700 hover:text-blue-600 transition flex items-center space-x-1">
                    <span>Lihat di daftar pengiriman</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

    </div>

    <!-- Bagian Bawah Dashboard: Recent Shipments & Side Panel (Active Exceptions / Notifications) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Tabel Ringkas Pengiriman Terbaru (Col 8/12) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">
                        {{ $user->isCustomer() ? 'Pengiriman Kargo Terbaru Saya' : 'Daftar Pengiriman Terbaru' }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Visibilitas 6 data pengiriman kargo terbaru</p>
                </div>
                <a href="{{ route('shipments.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if ($recentShipments->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <svg class="h-10 w-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm font-medium">Belum ada data shipment yang tersedia.</p>
                </div>
            @else
                <div class="overflow-x-auto mt-4 custom-scrollbar">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase font-semibold border-b border-slate-100 text-[11px]">
                                <th class="pb-3">Nomor Resi</th>
                                <th class="pb-3">Moda & Carrier</th>
                                <th class="pb-3">Rute</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Delay</th>
                                <th class="pb-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($recentShipments as $shipment)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 font-bold text-slate-900">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="font-mono text-xs hover:text-blue-600 transition flex items-center space-x-1">
                                            <span>{{ $shipment->tracking_number }}</span>
                                        </a>
                                        <div class="text-[10px] text-slate-400 font-normal mt-0.5">
                                            {{ $shipment->cargo_type }} &bull; {{ number_format($shipment->weight_kg, 0) }} kg
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        <div class="inline-flex items-center space-x-1 px-2 py-0.5 rounded font-medium text-[10px]
                                            {{ $shipment->transport_mode === 'OCEAN' ? 'bg-cyan-50 text-cyan-700 border border-cyan-200' : 
                                              ($shipment->transport_mode === 'AIR' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 
                                              'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                            <span>{{ $shipment->transport_mode }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-600 font-medium mt-0.5">
                                            {{ $shipment->carrier->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        <div class="font-medium text-slate-800">
                                            {{ $shipment->originLocation->code ?? 'Origin' }}
                                            <span class="text-slate-400 mx-1">&rarr;</span>
                                            {{ $shipment->destinationLocation->code ?? 'Destination' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                            ETA: {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        @php
                                            $stMap = [
                                                'CREATED'    => 'bg-slate-100 text-slate-700 border-slate-200',
                                                'BOOKED'     => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'PICKED_UP'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'IN_TRANSIT' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'DELIVERED'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'CANCELLED'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $stMap[$shipment->status] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 font-medium">
                                        @if ($shipment->delay_hours > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold
                                                {{ $shipment->delay_severity === 'HIGH' ? 'bg-rose-100 text-rose-700 border border-rose-200' : 
                                                  ($shipment->delay_severity === 'MEDIUM' ? 'bg-orange-100 text-orange-700 border border-orange-200' : 
                                                  'bg-amber-100 text-amber-700 border border-amber-200') }}">
                                                +{{ $shipment->delay_hours }}h Delay
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-emerald-600 text-[11px] font-semibold">
                                                On-time
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 text-right">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
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
                <!-- Panel Khusus Admin & Operator: Exception Aktif (OPEN / INVESTIGATING) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Exception Aktif</h3>
                            <p class="text-[11px] text-slate-400">Kendala OPEN / INVESTIGATING</p>
                        </div>
                        <a href="{{ route('exceptions.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition">
                            Ke Halaman Exceptions &rarr;
                        </a>
                    </div>

                    @if ($activeExceptions->isEmpty())
                        <div class="py-6 text-center text-slate-400">
                            <svg class="h-8 w-8 text-emerald-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs font-semibold text-slate-700">Tidak Ada Exception Aktif</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Operasional pengiriman berjalan lancar tanpa anomali terbuka.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($activeExceptions as $exc)
                                <div class="p-3 rounded-2xl border border-rose-100 bg-rose-50/40 hover:bg-rose-50 transition">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $exc->severity === 'HIGH' ? 'bg-rose-200 text-rose-800' : ($exc->severity === 'MEDIUM' ? 'bg-amber-200 text-amber-800' : 'bg-slate-200 text-slate-700') }}">
                                            {{ $exc->severity }} &bull; {{ $exc->status }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">{{ $exc->detected_at ? $exc->detected_at->diffForHumans() : '-' }}</span>
                                    </div>
                                    <div class="text-xs font-bold text-slate-900 mt-1">
                                        {{ str_replace('_', ' ', $exc->type) }}
                                    </div>
                                    <p class="text-xs text-slate-600 mt-0.5 line-clamp-2 leading-relaxed">{{ $exc->description }}</p>
                                    <div class="mt-2 pt-2 border-t border-rose-200/60 flex items-center justify-between text-[11px]">
                                        <a href="{{ route('shipments.show', $exc->shipment) }}" class="font-mono text-blue-600 hover:underline">
                                            {{ $exc->shipment->tracking_number ?? 'Shipment' }}
                                        </a>
                                        <span class="text-rose-700 font-bold">+{{ $exc->delay_impact_hours }}h Impact</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Notifikasi & Pintasan Cepat -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Pembaruan Notifikasi</h3>
                    <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        Semua Notifikasi &rarr;
                    </a>
                </div>

                @if ($recentNotifications->isEmpty())
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada notifikasi.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($recentNotifications as $notif)
                            <div class="p-3 rounded-2xl border {{ $notif->is_read ? 'bg-slate-50 border-slate-100 text-slate-600' : 'bg-amber-50/60 border-amber-200 text-slate-900' }} transition">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-xs">{{ $notif->title }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $notif->message }}</p>
                                @if($notif->shipment)
                                    <div class="mt-2 pt-1 text-[11px]">
                                        <a href="{{ route('shipments.show', $notif->shipment) }}" class="text-blue-600 font-semibold hover:underline">
                                            Lihat Resi {{ $notif->shipment->tracking_number }} &rarr;
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

@push('scripts')
<!-- Chart.js Official CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        const statusColors = ['#94a3b8', '#38bdf8', '#fbbf24', '#6366f1', '#10b981', '#f43f5e'];

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
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8,
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
                        backgroundColor: '#0f172a',
                        hoverBackgroundColor: '#d4f93a',
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 42
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
                            backgroundColor: '#0f172a',
                            padding: 10,
                            cornerRadius: 8,
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
                                color: '#475569'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: { size: 11 },
                                color: '#64748b'
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
