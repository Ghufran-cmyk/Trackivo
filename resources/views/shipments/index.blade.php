@extends('layouts.app')

@section('title', auth()->user()->isCustomer() ? 'Pengiriman Saya' : 'Daftar Pengiriman Kargo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-slate-900 text-[#d4f93a] text-xs font-semibold mb-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d4f93a]"></span>
                <span>{{ auth()->user()->isCustomer() ? 'Portal Pelanggan Kargo' : 'Manajemen Operasional Pengiriman' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ auth()->user()->isCustomer() ? 'Pengiriman Saya (My Shipments)' : 'Daftar Pengiriman Global' }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ auth()->user()->isCustomer() ? 'Pantau status, estimasi waktu tiba, dan riwayat checkpoint muatan kargo Anda.' : 'Pantau pergerakan seluruh kargo lintas samudera, udara, dan darat dalam sistem Trackivo.' }}
            </p>
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isOperator())
            <div class="shrink-0">
                <a href="{{ route('shipments.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-sm font-semibold shadow-sm transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat Shipment</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Filter & Search Panel -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('shipments.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                
                <!-- Search input -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cari Nomor Resi / Kargo</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: TRK-2026-..., Elektronik, Tekstil"
                               class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <svg class="h-4 w-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Status -->
                <div>
                    <label for="status" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Status</option>
                        @foreach(['CREATED', 'BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'CANCELLED'] as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Carrier -->
                <div>
                    <label for="carrier_id" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Carrier</label>
                    <select name="carrier_id" id="carrier_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Carrier</option>
                        @foreach($carriers as $c)
                            <option value="{{ $c->id }}" {{ request('carrier_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-slate-900 text-[#d4f93a] text-sm font-semibold hover:bg-slate-800 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'carrier_id', 'origin_location_id', 'destination_location_id']))
                        <a href="{{ route('shipments.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <!-- Advanced Location Filters Row (Collapsible or compact) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-3 border-t border-slate-100">
                <div>
                    <label for="origin_location_id" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Titik Asal (Origin)</label>
                    <select name="origin_location_id" id="origin_location_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Titik Asal</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('origin_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }} - {{ $loc->country }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="destination_location_id" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Titik Tujuan (Destination)</label>
                    <select name="destination_location_id" id="destination_location_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Titik Tujuan</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('destination_location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }} - {{ $loc->country }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Shipments Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Daftar Pengiriman Terdata</h2>
            <span class="text-xs text-slate-500">Menampilkan: <span class="font-semibold text-slate-700">{{ $shipments->count() }}</span> dari <span class="font-semibold text-slate-700">{{ $shipments->total() }}</span> total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-4 sm:px-5">Nomor Resi</th>
                        <th class="py-3.5 px-4 sm:px-5">Status</th>
                        <th class="py-3.5 px-4 sm:px-5">Moda & Kargo</th>
                        <th class="py-3.5 px-4 sm:px-5">Rute (Asal &rarr; Tujuan)</th>
                        <th class="py-3.5 px-4 sm:px-5">Carrier</th>
                        @if(!auth()->user()->isCustomer())
                            <th class="py-3.5 px-4 sm:px-5">Pelanggan</th>
                        @endif
                        <th class="py-3.5 px-4 sm:px-5">Est. Tiba</th>
                        <th class="py-3.5 px-4 sm:px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($shipments as $shipment)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Tracking Number -->
                            <td class="py-3.5 px-4 sm:px-5">
                                <a href="{{ route('shipments.show', $shipment) }}" class="font-mono font-bold text-slate-900 hover:text-blue-600 flex items-center space-x-1.5 group">
                                    <span>{{ $shipment->tracking_number }}</span>
                                    <svg class="h-3.5 w-3.5 text-slate-400 group-hover:text-blue-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                <span class="text-[11px] text-slate-400 block">{{ $shipment->items->count() }} item barang</span>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                @php
                                    $statusBadges = [
                                        'CREATED'    => 'bg-slate-100 text-slate-700 border-slate-300',
                                        'BOOKED'     => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'PICKED_UP'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'IN_TRANSIT' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'DELIVERED'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'CANCELLED'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusBadges[$shipment->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $shipment->status }}
                                </span>
                            </td>

                            <!-- Transport Mode & Cargo -->
                            <td class="py-3.5 px-4 sm:px-5">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                        {{ $shipment->transport_mode === 'OCEAN' ? 'bg-cyan-100 text-cyan-800' :
                                          ($shipment->transport_mode === 'AIR' ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800') }}">
                                        {{ $shipment->transport_mode }}
                                    </span>
                                    <span class="font-medium text-slate-800 text-xs sm:text-sm truncate max-w-[150px]">{{ $shipment->cargo_type }}</span>
                                </div>
                                <span class="text-[11px] text-slate-400 block mt-0.5">{{ number_format($shipment->weight_kg, 1) }} kg</span>
                            </td>

                            <!-- Route -->
                            <td class="py-3.5 px-4 sm:px-5">
                                <div class="flex items-center space-x-1.5 text-xs font-semibold text-slate-800">
                                    <span class="px-1.5 py-0.5 bg-slate-100 rounded text-slate-700">{{ $shipment->originLocation?->code ?? '-' }}</span>
                                    <span class="text-slate-400">&rarr;</span>
                                    <span class="px-1.5 py-0.5 bg-slate-100 rounded text-slate-700">{{ $shipment->destinationLocation?->code ?? '-' }}</span>
                                </div>
                                <span class="text-[11px] text-slate-400 block truncate max-w-[180px]">
                                    {{ $shipment->originLocation?->name }} &rarr; {{ $shipment->destinationLocation?->name }}
                                </span>
                            </td>

                            <!-- Carrier -->
                            <td class="py-3.5 px-4 sm:px-5 text-slate-700 text-xs sm:text-sm">
                                <span class="font-medium block">{{ $shipment->carrier?->name ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $shipment->carrier?->code }}</span>
                            </td>

                            <!-- Customer (for Admin & Operator) -->
                            @if(!auth()->user()->isCustomer())
                                <td class="py-3.5 px-4 sm:px-5 text-slate-700 text-xs sm:text-sm">
                                    <span class="font-medium block text-slate-900">{{ $shipment->customer?->name ?? '-' }}</span>
                                    @if($shipment->customer?->company_name)
                                        <span class="text-[11px] text-slate-400 block truncate max-w-[140px]">{{ $shipment->customer->company_name }}</span>
                                    @endif
                                </td>
                            @endif

                            <!-- Est. Arrival -->
                            <td class="py-3.5 px-4 sm:px-5 text-slate-700 text-xs whitespace-nowrap">
                                <div>{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y') : '-' }}</div>
                                <span class="text-[11px] text-slate-400">{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('H:i') . ' WIB' : '' }}</span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 sm:px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <!-- View Detail -->
                                    <a href="{{ route('shipments.show', $shipment) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition" title="Lihat Rincian">
                                        Detail
                                    </a>

                                    <!-- Edit (Admin & Operator, only if non-terminal) -->
                                    @if((auth()->user()->isAdmin() || auth()->user()->isOperator()) && !in_array($shipment->status, ['DELIVERED', 'CANCELLED']))
                                        <a href="{{ route('shipments.edit', $shipment) }}" class="p-1 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition" title="Edit Shipment">
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
                                            <button type="submit" class="p-1 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" title="Hapus Shipment">
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
                            <td colspan="{{ auth()->user()->isCustomer() ? 7 : 8 }}" class="py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto text-center">
                                    <svg class="h-10 w-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm font-semibold text-slate-600">Tidak ada pengiriman yang ditemukan.</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shipments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
