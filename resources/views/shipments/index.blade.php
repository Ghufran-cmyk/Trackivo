@extends('layouts.app')

@section('title', auth()->user()->isCustomer() ? 'Pengiriman Saya' : 'Daftar Pengiriman Kargo')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1C1E1B] tracking-tight">
                {{ auth()->user()->isCustomer() ? 'Pengiriman Saya' : 'Daftar Pengiriman Global' }}
            </h1>
            <p class="text-xs sm:text-sm text-[#6F756C] mt-1">
                {{ auth()->user()->isCustomer() ? 'Pantau status, estimasi waktu tiba, anomali delay, dan riwayat perjalanan muatan kargo Anda.' : 'Pantau pergerakan seluruh kargo lintas samudera, udara, dan darat dalam sistem Trackivo.' }}
            </p>
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isOperator())
            <div class="shrink-0">
                <a href="{{ route('shipments.create') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs sm:text-sm font-semibold shadow-xs transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat Shipment</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Filter, Search & Sorting Panel -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
        <form method="GET" action="{{ route('shipments.index') }}" class="space-y-4">
            
            <!-- Row 1: Search, Status, Carrier, Sorting & Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
                
                <!-- Search input (4 cols) -->
                <div class="lg:col-span-4">
                    <label for="search" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Cari Nomor Resi / Jenis Kargo</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: TRK-2026-..., Elektronik, Garment"
                               class="w-full pl-9 pr-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] placeholder-[#9EABA2] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <svg class="h-4 w-4 text-[#9EABA2] absolute left-3 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Status (2 cols) -->
                <div class="lg:col-span-2">
                    <label for="status" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Status Pengiriman</label>
                    <select name="status" id="status" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="">Semua Status</option>
                        @foreach(['CREATED', 'BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'CANCELLED'] as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Carrier (2 cols) -->
                <div class="lg:col-span-2">
                    <label for="carrier_id" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Carrier</label>
                    <select name="carrier_id" id="carrier_id" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="">Semua Carrier</option>
                        @foreach($carriers as $c)
                            <option value="{{ $c->id }}" {{ request('carrier_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sorting / Pengurutan (2 cols) -->
                <div class="lg:col-span-2">
                    <label for="sort" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Urutkan</label>
                    <select name="sort" id="sort" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Terbaru Dibuat</option>
                        <option value="eta_asc" {{ request('sort') == 'eta_asc' ? 'selected' : '' }}>Estimasi Tiba Terdekat</option>
                        <option value="eta_desc" {{ request('sort') == 'eta_desc' ? 'selected' : '' }}>Estimasi Tiba Terjauh</option>
                        <option value="delay_desc" {{ request('sort') == 'delay_desc' ? 'selected' : '' }}>Keterlambatan Terlama</option>
                        <option value="number_asc" {{ request('sort') == 'number_asc' ? 'selected' : '' }}>Nomor Resi A–Z</option>
                    </select>
                </div>

                <!-- Action Filter Buttons (2 cols) -->
                <div class="lg:col-span-2 flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs sm:text-sm font-semibold transition shadow-xs">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['search', 'status', 'carrier_id', 'origin_location_id', 'destination_location_id', 'date_from', 'date_to', 'delay_status', 'sort']))
                        <a href="{{ route('shipments.index') }}" class="px-4 py-2.5 rounded-full border border-[#DFE1D8] bg-[#F5F6F1] text-[#1C1E1B] text-xs sm:text-sm font-semibold hover:bg-[#E2E4DC] transition" title="Reset Semua Filter">
                            Reset
                        </a>
                    @endif
                </div>

            </div>

            <!-- Row 2: Origin, Destination, Tanggal Berangkat (From/To), Delay Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 pt-3 border-t border-[#DFE1D8]/60">
                
                <!-- Titik Asal (Origin) -->
                <div>
                    <label for="origin_location_id" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Titik Asal (Origin)</label>
                    <select name="origin_location_id" id="origin_location_id" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="">Semua Titik Asal</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('origin_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Titik Tujuan (Destination) -->
                <div>
                    <label for="destination_location_id" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Titik Tujuan (Destination)</label>
                    <select name="destination_location_id" id="destination_location_id" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="">Semua Titik Tujuan</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('destination_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Keberangkatan Dari (date_from) -->
                <div>
                    <label for="date_from" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Berangkat Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                </div>

                <!-- Keberangkatan Sampai (date_to) -->
                <div>
                    <label for="date_to" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Berangkat Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                </div>

                <!-- Status Keterlambatan (delay_status) -->
                <div>
                    <label for="delay_status" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Status Keterlambatan</label>
                    <select name="delay_status" id="delay_status" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="">Semua Kondisi Delay</option>
                        <option value="on_time" {{ request('delay_status') == 'on_time' ? 'selected' : '' }}>On-Time (Tepat Waktu)</option>
                        <option value="delayed" {{ request('delay_status') == 'delayed' ? 'selected' : '' }}>Delayed (Semua Keterlambatan)</option>
                        <option value="LOW" {{ request('delay_status') == 'LOW' ? 'selected' : '' }}>Delay Ringan (LOW)</option>
                        <option value="MEDIUM" {{ request('delay_status') == 'MEDIUM' ? 'selected' : '' }}>Delay Sedang (MEDIUM)</option>
                        <option value="HIGH" {{ request('delay_status') == 'HIGH' ? 'selected' : '' }}>Delay Kritis (HIGH)</option>
                    </select>
                </div>

            </div>

        </form>
    </div>

    <!-- Shipments Table Card -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        <div class="p-6 border-b border-[#DFE1D8]/60 flex items-center justify-between">
            <h2 class="text-base font-bold text-[#1C1E1B]">Daftar Pengiriman Terdata</h2>
            <span class="text-xs text-[#6F756C]">Menampilkan: <span class="font-bold text-[#1C1E1B]">{{ $shipments->count() }}</span> dari <span class="font-bold text-[#1C1E1B]">{{ $shipments->total() }}</span> total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-[#FAF9F5] text-[#6F756C] uppercase text-[10.5px] font-bold tracking-wider border-b border-[#DFE1D8]/60">
                        <th class="py-3.5 px-4 whitespace-nowrap">Nomor Resi</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Status</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Moda & Kargo</th>
                        <th class="py-3.5 px-4">Rute</th>
                        <th class="py-3.5 px-4">Carrier</th>
                        @if(!auth()->user()->isCustomer())
                            <th class="py-3.5 px-4">Pelanggan</th>
                        @endif
                        <th class="py-3.5 px-4 whitespace-nowrap">Est. Tiba</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Delay</th>
                        <th class="py-3.5 px-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DFE1D8]/40">
                    @forelse($shipments as $shipment)
                        <tr class="hover:bg-[#F5F6F1] transition text-xs sm:text-[13px]">
                            <!-- Tracking Number -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('shipments.show', $shipment) }}" class="font-mono font-bold text-xs text-[#1C1E1B] hover:text-[#556E5C] inline-flex items-center space-x-1 group">
                                    <span>{{ $shipment->tracking_number }}</span>
                                    <svg class="h-3 w-3 text-[#9EABA2] group-hover:text-[#556E5C] transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                <span class="text-[10.5px] text-[#6F756C] block mt-0.5">{{ $shipment->items->count() }} item</span>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @php
                                    $statusBadges = [
                                        'CREATED'    => 'bg-[#F1F2EC] text-[#6F756C]',
                                        'BOOKED'     => 'bg-[#EDF1EC] text-[#556E5C]',
                                        'PICKED_UP'  => 'bg-[#F7EFE1] text-[#9A6721]',
                                        'IN_TRANSIT' => 'bg-[#F7EFE1] text-[#9A6721]',
                                        'DELIVERED'  => 'bg-[#E6F0E9] text-[#2F6846]',
                                        'CANCELLED'  => 'bg-[#F9E6E6] text-[#B84242]',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold {{ $statusBadges[$shipment->status] ?? 'bg-[#F1F2EC] text-[#6F756C]' }}">
                                    {{ $shipment->status }}
                                </span>
                            </td>

                            <!-- Transport Mode & Cargo -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-1.5">
                                    <span class="px-2 py-0.5 rounded-full text-[9.5px] font-bold uppercase tracking-wider
                                        {{ $shipment->transport_mode === 'OCEAN' ? 'bg-[#E6F0E9] text-[#2F6846]' :
                                          ($shipment->transport_mode === 'AIR' ? 'bg-[#F7EFE1] text-[#9A6721]' : 'bg-[#EDF1EC] text-[#556E5C]') }}">
                                        {{ $shipment->transport_mode }}
                                    </span>
                                    <span class="font-medium text-[#1C1E1B] truncate max-w-[130px]">{{ $shipment->cargo_type }}</span>
                                </div>
                                <span class="text-[10.5px] text-[#6F756C] block mt-0.5">{{ number_format($shipment->weight_kg, 1) }} kg</span>
                            </td>

                            <!-- Route -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-1 font-semibold text-[#1C1E1B]">
                                    <span class="px-1.5 py-0.5 bg-[#F5F6F1] rounded-md text-[#1C1E1B] text-[11px] font-mono">{{ $shipment->originLocation?->code ?? '-' }}</span>
                                    <span class="text-[#6F756C] text-xs">&rarr;</span>
                                    <span class="px-1.5 py-0.5 bg-[#F5F6F1] rounded-md text-[#1C1E1B] text-[11px] font-mono">{{ $shipment->destinationLocation?->code ?? '-' }}</span>
                                </div>
                                <span class="text-[10.5px] text-[#6F756C] block truncate max-w-[160px] mt-0.5">
                                    {{ $shipment->originLocation?->name }} &rarr; {{ $shipment->destinationLocation?->name }}
                                </span>
                            </td>

                            <!-- Carrier -->
                            <td class="py-3.5 px-4 text-[#1C1E1B]">
                                <span class="font-medium block truncate max-w-[130px]">{{ $shipment->carrier?->name ?? '-' }}</span>
                                <span class="text-[10.5px] text-[#6F756C] font-mono">{{ $shipment->carrier?->code }}</span>
                            </td>

                            <!-- Customer (for Admin & Operator) -->
                            @if(!auth()->user()->isCustomer())
                                <td class="py-3.5 px-4 text-[#1C1E1B]">
                                    <span class="font-medium block truncate max-w-[120px]">{{ $shipment->customer?->name ?? '-' }}</span>
                                    @if($shipment->customer?->company_name)
                                        <span class="text-[10.5px] text-[#6F756C] block truncate max-w-[120px]">{{ $shipment->customer->company_name }}</span>
                                    @endif
                                </td>
                            @endif

                            <!-- Est. Arrival -->
                            <td class="py-3.5 px-4 text-[#1C1E1B] whitespace-nowrap">
                                <div>{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y') : '-' }}</div>
                                <span class="text-[10.5px] text-[#6F756C]">{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('H:i') . ' WIB' : '' }}</span>
                            </td>

                            <!-- Kondisi Delay -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if ($shipment->delay_hours > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10.5px] font-bold
                                        {{ $shipment->delay_severity === 'HIGH' ? 'bg-[#F9E6E6] text-[#B84242]' : 
                                          ($shipment->delay_severity === 'MEDIUM' ? 'bg-[#F7EFE1] text-[#9A6721]' : 
                                          'bg-[#F7EFE1] text-[#9A6721]') }}">
                                        +{{ $shipment->delay_hours }}h ({{ $shipment->delay_severity }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 text-[#2F6846] font-semibold text-xs">
                                        <svg class="h-3.5 w-3.5 text-[#2F6846]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>On-Time</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <!-- View Detail -->
                                    <a href="{{ route('shipments.show', $shipment) }}" class="px-3.5 py-1 rounded-full bg-[#F5F6F1] hover:bg-[#556E5C] hover:text-white text-[#1C1E1B] text-xs font-semibold transition" title="Lihat Rincian">
                                        Detail
                                    </a>

                                    <!-- Edit (Admin & Operator, only if non-terminal) -->
                                    @if((auth()->user()->isAdmin() || auth()->user()->isOperator()) && !in_array($shipment->status, ['DELIVERED', 'CANCELLED']))
                                        <a href="{{ route('shipments.edit', $shipment) }}" class="p-1.5 rounded-full text-[#6F756C] hover:text-[#1C1E1B] hover:bg-[#F1F2EC] transition" title="Edit Shipment">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endif

                                    <!-- Delete (Admin only, only if CREATED or CANCELLED) -->
                                    @if(auth()->user()->isAdmin() && in_array($shipment->status, ['CREATED', 'CANCELLED']))
                                        <form method="POST" action="{{ route('shipments.destroy', $shipment) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus shipment {{ $shipment->tracking_number }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-full text-[#B84242] hover:bg-[#F9E6E6] transition" title="Hapus Shipment">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isCustomer() ? 8 : 9 }}" class="py-12 text-center text-[#6F756C]">
                                <div class="max-w-sm mx-auto text-center">
                                    <svg class="h-10 w-10 text-[#C9CCC0] mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm font-semibold text-[#1C1E1B]">Tidak ada pengiriman yang sesuai kriteria.</p>
                                    <p class="text-xs text-[#6F756C] mt-1">Coba sesuaikan kata kunci pencarian, rentang tanggal, atau filter kondisi delay.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shipments->hasPages())
            <div class="p-5 border-t border-[#DFE1D8]/60">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
