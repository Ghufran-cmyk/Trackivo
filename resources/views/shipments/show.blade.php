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
                </div>

                <div class="flex items-baseline space-x-3">
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight font-mono text-white">
                        {{ $shipment->tracking_number }}
                    </h1>
                </div>

                <p class="text-sm text-slate-300 mt-2 flex items-center space-x-2">
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
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Rute Pengiriman</div>
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

    <!-- Grid: Detail Overview & Action Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Left 2 Cols: Details -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Schedule & Route Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Jadwal & Waktu Estimasi</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jadwal Keberangkatan</div>
                        <div class="text-sm font-bold text-slate-900 mt-1">
                            {{ $shipment->expected_departure ? $shipment->expected_departure->format('d M Y, H:i') . ' WIB' : '-' }}
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rencana Kedatangan</div>
                        <div class="text-sm font-bold text-slate-900 mt-1">
                            {{ $shipment->expected_arrival ? $shipment->expected_arrival->format('d M Y, H:i') . ' WIB' : '-' }}
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#d4f93a]/10 border border-[#d4f93a]/30">
                        <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Estimasi Tiba (Aktual)</div>
                        <div class="text-sm font-extrabold text-slate-900 mt-1">
                            {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y, H:i') . ' WIB' : '-' }}
                        </div>
                        @if($shipment->delay_hours > 0)
                            <div class="mt-1 text-[11px] font-bold text-rose-600">
                                Delay: +{{ $shipment->delay_hours }} Jam ({{ $shipment->delay_severity }})
                            </div>
                        @endif
                    </div>
                </div>

                @if($shipment->notes)
                    <div class="mt-4 p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                        <span class="font-bold text-slate-900 block mb-0.5">Catatan Khusus:</span>
                        {{ $shipment->notes }}
                    </div>
                @endif
            </div>

            <!-- Items Table Card -->
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

            <!-- Vertical Timeline of Tracking Events -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Riwayat Kronologis Perjalanan (Tracking Events)</span>
                </h2>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @forelse($shipment->trackingEvents as $event)
                        <div class="relative group">
                            <!-- Bullet -->
                            <div class="absolute -left-[27px] top-1.5 h-3.5 w-3.5 rounded-full border-2 border-white 
                                {{ $loop->last ? 'bg-[#0e1117] ring-4 ring-[#d4f93a]/30' : 'bg-slate-400' }}"></div>

                            <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-100 hover:border-slate-200 transition">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1.5">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                            {{ $event->status === 'DELIVERED' ? 'bg-emerald-100 text-emerald-800' : 
                                              ($event->status === 'CANCELLED' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-800') }}">
                                            {{ $event->status }}
                                        </span>

                                        @if($event->location)
                                            <span class="text-xs font-semibold text-slate-700">
                                                {{ $event->location->name }} ({{ $event->location->code }})
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-[11px] text-slate-400 font-mono">
                                        {{ $event->event_time ? $event->event_time->format('d M Y, H:i') . ' WIB' : '-' }}
                                    </div>
                                </div>

                                <p class="text-xs text-slate-700 leading-relaxed">
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

        <!-- Right 1 Col: State Machine & Meta Cards -->
        <div class="space-y-6">
            
            <!-- State Machine Action Panel (Admin & Operator Only) -->
            @if(auth()->user()->isAdmin() || auth()->user()->isOperator())
                <div class="bg-white rounded-2xl border-2 {{ in_array($shipment->status, ['DELIVERED', 'CANCELLED']) ? 'border-slate-200 bg-slate-50/50' : 'border-slate-900' }} shadow-md p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded bg-[#0e1117] text-[#d4f93a]">
                            State Machine
                        </span>
                        <span class="text-xs text-slate-400 font-medium">Status: <strong>{{ $shipment->status }}</strong></span>
                    </div>

                    <h3 class="text-base font-bold text-slate-900 mb-1">Pembaruan Status Kargo</h3>
                    <p class="text-xs text-slate-500 mb-4">
                        Status berpindah mengikuti alur baku sistem (State Machine) secara berurutan dan tercatat ke audit log.
                    </p>

                    @if(in_array($shipment->status, ['DELIVERED', 'CANCELLED']))
                        <!-- Terminal State Notice -->
                        <div class="p-4 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 text-xs">
                            <div class="flex items-center space-x-2 font-bold mb-1 {{ $shipment->status === 'DELIVERED' ? 'text-emerald-700' : 'text-rose-700' }}">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Status Pengiriman Final (Terminal)</span>
                            </div>
                            Pengiriman ini telah berstatus <strong>{{ $shipment->status }}</strong>. Sesuai aturan State Machine, status final tidak dapat diubah kembali ke status lain.
                        </div>
                    @else
                        <!-- Form Transition Status -->
                        <form method="POST" action="{{ route('shipments.status.update', $shipment) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="new_status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Pilih Status Berikutnya yang Sah <span class="text-rose-500">*</span>
                                </label>
                                <select name="status" id="new_status" required
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                                    <option value="">-- Pilih Status Berikutnya --</option>
                                    @foreach($allowedNextStatuses as $nextStatus)
                                        <option value="{{ $nextStatus }}">{{ $nextStatus }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="note" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Catatan / Alasan Perubahan (Opsional)
                                </label>
                                <textarea name="note" id="note" rows="2" placeholder="Contoh: Kargo telah diserahterimakan ke kapal..."
                                          class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]"></textarea>
                                @error('note')
                                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-xs font-bold shadow-sm transition">
                                Simpan Transisi Status &rarr;
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Customer & Stakeholder Card -->
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
