@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

    <!-- Welcome & Hero Header (Inspired by Sleek Modern Dashboard Reference) -->
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
                    Selamat Datang, {{ $user->name }}
                </h1>
                <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                    @if($user->isAdmin())
                        Anda memiliki hak akses penuh Administrator pada platform Trackivo untuk mengelola shipments, carriers, locations, user, dan audit log global.
                    @elseif($user->isOperator())
                        Anda masuk sebagai Operator Operasional. Pantau jalur kontainer aktif, anomali rute pengiriman, dan pembaruan checkpoint real-time.
                    @else
                        Selamat datang di portal pelacakan kargo Anda. Pantau status pengiriman, estimasi tiba di tujuan, dan riwayat perjalanan barang Anda.
                    @endif
                </p>
            </div>

            <!-- Quick Account Profile Pill Card -->
            <div class="bg-black/40 backdrop-blur-md border border-white/10 rounded-2xl p-4 sm:min-w-[260px] shrink-0">
                <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Informasi Akun</div>
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Email:</span>
                        <span class="text-white font-medium truncate max-w-[170px]">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Perusahaan:</span>
                        <span class="text-white font-medium">{{ $user->company_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Telepon:</span>
                        <span class="text-white font-medium">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between pt-1 border-t border-white/10">
                        <span class="text-slate-400">Status Sistem:</span>
                        <span class="text-[#d4f93a] font-semibold flex items-center space-x-1">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#d4f93a]"></span>
                            <span>Active / Online</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        @if ($user->isAdmin())
            <!-- Admin Metrics -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Shipments</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $stats['total_shipments'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-slate-500">Global kargo aktif & arsip</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Active Shipments</div>
                <div class="mt-2 text-2xl font-extrabold text-blue-600">{{ $stats['active_shipments'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-blue-600/80 font-medium">Dalam perjalanan</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Global Carriers</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $stats['total_carriers'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-slate-500">Laut, Udara & Darat</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Locations</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $stats['total_locations'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-slate-500">Ports & Hubs global</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Registered Users</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-slate-500">3 Role terdaftar</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-rose-200 bg-rose-50/30 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-rose-600 uppercase tracking-wider">Open Exceptions</div>
                <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $stats['open_exceptions'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-rose-600/80 font-medium">Memerlukan mitigasi</div>
            </div>

        @elseif ($user->isOperator())
            <!-- Operator Metrics -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Active Shipments</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $stats['active_shipments'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-slate-500">Dipantau hari ini</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">In Transit</div>
                <div class="mt-2 text-2xl font-extrabold text-blue-600">{{ $stats['in_transit'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-blue-600/80 font-medium">Bergerak di rute</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-amber-200 bg-amber-50/30 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-amber-700 uppercase tracking-wider">Delayed Shipments</div>
                <div class="mt-2 text-2xl font-extrabold text-amber-600">{{ $stats['delayed_shipments'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-amber-700/80 font-medium">Mengalami delay</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-rose-200 bg-rose-50/30 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-rose-600 uppercase tracking-wider">Open Exceptions</div>
                <div class="mt-2 text-2xl font-extrabold text-rose-600">{{ $stats['open_exceptions'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-rose-600/80 font-medium">Anomali aktif</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-emerald-200 bg-emerald-50/30 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Delivered</div>
                <div class="mt-2 text-2xl font-extrabold text-emerald-600">{{ $stats['delivered_total'] ?? 0 }}</div>
                <div class="mt-1 text-[11px] text-emerald-700/80 font-medium">Terkirim sukses</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Timezone</div>
                <div class="mt-2 text-lg font-bold text-slate-800">Asia/Jakarta</div>
                <div class="mt-1 text-[11px] text-slate-500">WIB Standar</div>
            </div>

        @else
            <!-- Customer Metrics -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition col-span-2 md:col-span-1 lg:col-span-2">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total My Shipments</div>
                <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['my_shipments'] ?? 0 }}</div>
                <div class="mt-1 text-xs text-slate-500">Seluruh pesanan pengiriman terdaftar</div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-blue-200 bg-blue-50/30 shadow-sm hover:shadow-md transition col-span-2 md:col-span-1 lg:col-span-2">
                <div class="text-[11px] font-semibold text-blue-700 uppercase tracking-wider">In Transit (Aktif)</div>
                <div class="mt-2 text-3xl font-extrabold text-blue-600">{{ $stats['in_transit'] ?? 0 }}</div>
                <div class="mt-1 text-xs text-blue-600/80 font-medium">Sedang bergerak menuju tujuan</div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-emerald-200 bg-emerald-50/30 shadow-sm hover:shadow-md transition col-span-1 lg:col-span-1">
                <div class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Delivered</div>
                <div class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $stats['delivered'] ?? 0 }}</div>
                <div class="mt-1 text-xs text-emerald-700/80 font-medium">Sampai tujuan</div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition col-span-1 lg:col-span-1">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Notifikasi Baru</div>
                <div class="mt-2 text-3xl font-extrabold text-amber-600">{{ $stats['unread_notifications'] ?? 0 }}</div>
                <div class="mt-1 text-xs text-slate-500">Pembaruan kargo</div>
            </div>
        @endif
    </div>

    <!-- Main Content Grid: Recent Shipments & Side Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Recent Shipments Table (Col 8/12) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                        {{ $user->isCustomer() ? 'Pengiriman Kargo Saya' : 'Daftar Pengiriman Global Terbaru' }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Visibilitas data pengiriman internasional terkini</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                    {{ $recentShipments->count() }} Data Ditampilkan
                </span>
            </div>

            @if ($recentShipments->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <svg class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm font-medium">Belum ada data shipment yang tersedia.</p>
                </div>
            @else
                <div class="overflow-x-auto mt-4 custom-scrollbar">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase font-semibold border-b border-slate-100">
                                <th class="pb-3 font-semibold">Resi / Tracking #</th>
                                <th class="pb-3 font-semibold">Moda / Carrier</th>
                                <th class="pb-3 font-semibold">Rute (Asal &rarr; Tujuan)</th>
                                <th class="pb-3 font-semibold">Status</th>
                                <th class="pb-3 font-semibold text-right">Delay</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($recentShipments as $shipment)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 font-bold text-slate-900">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-mono text-xs">{{ $shipment->tracking_number }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-normal">
                                            {{ $shipment->cargo_type }} &bull; {{ number_format($shipment->weight_kg, 0) }} kg
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        <div class="inline-flex items-center space-x-1.5 px-2 py-0.5 rounded-md font-medium text-[11px]
                                            {{ $shipment->transport_mode === 'OCEAN' ? 'bg-cyan-50 text-cyan-700 border border-cyan-200' : 
                                              ($shipment->transport_mode === 'AIR' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 
                                              'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                            <span>{{ $shipment->transport_mode }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-500 mt-0.5 font-medium">
                                            {{ $shipment->carrier->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        <div class="font-medium text-slate-800">
                                            {{ $shipment->originLocation->name ?? 'Origin' }}
                                            <span class="text-slate-400 mx-1">&rarr;</span>
                                            {{ $shipment->destinationLocation->name ?? 'Destination' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            ETA: {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wide
                                            {{ $shipment->status === 'IN_TRANSIT' ? 'bg-amber-100 text-amber-800' : 
                                              ($shipment->status === 'DELIVERED' ? 'bg-emerald-100 text-emerald-800' : 
                                              ($shipment->status === 'BOOKED' ? 'bg-blue-100 text-blue-800' : 
                                              'bg-slate-100 text-slate-700')) }}">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 text-right font-medium">
                                        @if ($shipment->delay_hours > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Right Side Panel (Col 4/12): Role Actions & Notifications -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Quick Features / Shortcut Navigation -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Akses Menu Cepat</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500">Tahap 1</span>
                </div>
                
                <div class="space-y-2 text-xs">
                    @if ($user->isAdmin())
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Shipments Management</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Exception & Anomali Resolver</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Carrier & Port Locations</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Audit Logs & Activity</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Global System Settings</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                    @elseif ($user->isOperator())
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Monitor Active Shipments</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Input Checkpoint Tracking</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Kelola Exception & Delay</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                    @else
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Daftar Pengiriman Saya</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                        <a href="#" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <span class="font-semibold text-slate-700 group-hover:text-slate-900">Cek Notifikasi & Update Kargo</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Notifications Card (for Customer) / System Info (for Admin & Operator) -->
            @if ($user->isCustomer())
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Notifikasi Terbaru</h3>
                        <span class="text-xs font-semibold text-amber-600">{{ $stats['unread_notifications'] ?? 0 }} Belum Dibaca</span>
                    </div>

                    @if ($recentNotifications->isEmpty())
                        <p class="text-xs text-slate-400 text-center py-4">Tidak ada notifikasi baru.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentNotifications as $notif)
                                <div class="p-3 rounded-2xl border {{ $notif->is_read ? 'bg-slate-50 border-slate-100 text-slate-600' : 'bg-amber-50/50 border-amber-200/80 text-slate-900' }} transition">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-xs">{{ $notif->title }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $notif->message }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-gradient-to-br from-[#121620] to-[#0a0d14] rounded-3xl p-6 text-white border border-slate-800 shadow-md">
                    <div class="flex items-center space-x-2 text-[#d4f93a] text-xs font-bold uppercase tracking-wider mb-2">
                        <span class="h-2 w-2 rounded-full bg-[#d4f93a]"></span>
                        <span>Trackivo Global Core</span>
                    </div>
                    <h4 class="text-base font-bold text-white mb-2">Fondasi Arsitektur Tahap 1</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Database 10 tabel, model Eloquent, autentikasi 3-role (Admin, Operator, Customer), RoleMiddleware proteksi rute, dan timezone Asia/Jakarta telah aktif dan siap digunakan.
                    </p>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-400">
                        <span>Database: MySQL (trackivo_db)</span>
                        <span>Status: Operational</span>
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
