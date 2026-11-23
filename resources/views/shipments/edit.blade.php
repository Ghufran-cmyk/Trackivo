@extends('layouts.app')

@section('title', 'Edit Shipment - ' . $shipment->tracking_number)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">

    <!-- Breadcrumb & Back -->
    <div class="mb-6">
        <a href="{{ route('shipments.show', $shipment) }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Detail Shipment</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        
        <!-- Header Banner -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-[#7B9581] to-[#556E5C] text-white">
            <div class="flex items-center space-x-2 mb-2">
                <span class="px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold uppercase tracking-wider">Edit Data Kargo</span>
                <span class="text-xs text-white/80 font-mono">&bull; Resi: <strong class="text-white">{{ $shipment->tracking_number }}</strong></span>
                <span class="text-xs text-white/80">&bull; Status: <strong class="text-white">{{ $shipment->status }}</strong></span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Edit Informasi Pengiriman</h1>
            <p class="text-xs sm:text-sm text-white/90 mt-1">Perubahan status dilakukan secara terpisah melalui State Machine pada panel detail shipment.</p>
        </div>

        <form method="POST" action="{{ route('shipments.update', $shipment) }}" id="shipmentForm" class="p-6 sm:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Pelanggan & Kargo -->
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                    <span>1. Data Pelanggan & Identitas Kargo</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Tracking Number (Read-only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nomor Resi (Tetap)</label>
                        <input type="text" value="{{ $shipment->tracking_number }}" disabled
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-600 text-sm font-mono cursor-not-allowed">
                    </div>

                    <!-- Pelanggan (Customer) -->
                    <div>
                        <label for="customer_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pemilik Kargo (Customer) <span class="text-rose-500">*</span>
                        </label>
                        <select name="customer_id" id="customer_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('customer_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" {{ old('customer_id', $shipment->customer_id) == $cust->id ? 'selected' : '' }}>
                                    {{ $cust->name }} {{ $cust->company_name ? "({$cust->company_name})" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Carrier -->
                    <div>
                        <label for="carrier_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Carrier Pengangkut <span class="text-rose-500">*</span>
                        </label>
                        <select name="carrier_id" id="carrier_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('carrier_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            @foreach($carriers as $carrier)
                                <option value="{{ $carrier->id }}" {{ old('carrier_id', $shipment->carrier_id) == $carrier->id ? 'selected' : '' }}>
                                    {{ $carrier->name }} ({{ $carrier->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('carrier_id')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transport Mode -->
                    <div>
                        <label for="transport_mode" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Moda Transportasi <span class="text-rose-500">*</span>
                        </label>
                        <select name="transport_mode" id="transport_mode" required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('transport_mode') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            <option value="OCEAN" {{ old('transport_mode', $shipment->transport_mode) == 'OCEAN' ? 'selected' : '' }}>OCEAN (Samudera)</option>
                            <option value="AIR" {{ old('transport_mode', $shipment->transport_mode) == 'AIR' ? 'selected' : '' }}>AIR (Udara)</option>
                            <option value="LAND" {{ old('transport_mode', $shipment->transport_mode) == 'LAND' ? 'selected' : '' }}>LAND (Darat)</option>
                        </select>
                        @error('transport_mode')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cargo Type -->
                    <div>
                        <label for="cargo_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jenis Kargo <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="cargo_type" id="cargo_type" value="{{ old('cargo_type', $shipment->cargo_type) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('cargo_type') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                        @error('cargo_type')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Weight -->
                    <div>
                        <label for="weight_kg" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Total Berat Kargo (Kg) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="weight_kg" id="weight_kg" value="{{ old('weight_kg', $shipment->weight_kg) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('weight_kg') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] font-mono">
                        @error('weight_kg')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Volume CBM -->
                    <div>
                        <label for="volume_cbm" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Volume Kargo (CBM)
                        </label>
                        <input type="number" step="0.01" name="volume_cbm" id="volume_cbm" value="{{ old('volume_cbm', $shipment->volume_cbm) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('volume_cbm') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] font-mono">
                        @error('volume_cbm')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Rute & Jadwal -->
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                    <span>2. Rute Asal - Tujuan & Estimasi Jadwal</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Origin -->
                    <div>
                        <label for="origin_location_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Titik Asal (Origin) <span class="text-rose-500">*</span>
                        </label>
                        <select name="origin_location_id" id="origin_location_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('origin_location_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('origin_location_id', $shipment->origin_location_id) == $loc->id ? 'selected' : '' }}>
                                    [{{ $loc->code }}] {{ $loc->name }} - {{ $loc->country }} ({{ $loc->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('origin_location_id')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Destination -->
                    <div>
                        <label for="destination_location_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Titik Tujuan (Destination) <span class="text-rose-500">*</span>
                        </label>
                        <select name="destination_location_id" id="destination_location_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('destination_location_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('destination_location_id', $shipment->destination_location_id) == $loc->id ? 'selected' : '' }}>
                                    [{{ $loc->code }}] {{ $loc->name }} - {{ $loc->country }} ({{ $loc->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('destination_location_id')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Departure -->
                    <div>
                        <label for="expected_departure" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jadwal Keberangkatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" name="expected_departure" id="expected_departure" 
                               value="{{ old('expected_departure', $shipment->expected_departure ? $shipment->expected_departure->format('Y-m-d\TH:i') : '') }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('expected_departure') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                        @error('expected_departure')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Arrival -->
                    <div>
                        <label for="expected_arrival" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Estimasi Kedatangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" name="expected_arrival" id="expected_arrival" 
                               value="{{ old('expected_arrival', $shipment->expected_arrival ? $shipment->expected_arrival->format('Y-m-d\TH:i') : '') }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('expected_arrival') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                        @error('expected_arrival')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Daftar Item Muatan (Dinamis Vanilla JS) -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                        <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                        <span>3. Rincian Item Muatan Kargo <span class="text-rose-500">*</span></span>
                    </h2>
                    <button type="button" id="addItemBtn" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-full bg-[#556E5C] text-white text-xs font-bold hover:bg-[#425848] transition shadow-xs">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Tambah Baris Item</span>
                    </button>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                    <table class="w-full text-left text-xs sm:text-sm" id="itemsTable">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-bold border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 w-1/3">Nama Item Kargo <span class="text-rose-500">*</span></th>
                                <th class="py-2.5 px-3 w-24">Jumlah (Qty) <span class="text-rose-500">*</span></th>
                                <th class="py-2.5 px-3 w-32">Berat (Kg) <span class="text-rose-500">*</span></th>
                                <th class="py-2.5 px-3">Deskripsi / Catatan Tambahan</th>
                                <th class="py-2.5 px-3 w-16 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTbody" class="divide-y divide-slate-100">
                            @php
                                $currentItems = old('items', $shipment->items->map(fn($it) => [
                                    'item_name' => $it->item_name,
                                    'quantity' => $it->quantity,
                                    'weight_kg' => $it->weight_kg,
                                    'description' => $it->description,
                                ])->toArray());

                                if (empty($currentItems)) {
                                    $currentItems = [['item_name' => '', 'quantity' => 1, 'weight_kg' => '', 'description' => '']];
                                }
                            @endphp

                            @foreach($currentItems as $idx => $item)
                                <tr class="item-row hover:bg-slate-50/50">
                                    <td class="p-2.5">
                                        <input type="text" name="items[{{ $idx }}][item_name]" value="{{ $item['item_name'] ?? '' }}" required placeholder="Nama item / barang kargo"
                                               class="w-full px-3 py-1.5 rounded-lg border {{ $errors->has("items.{$idx}.item_name") ? 'border-rose-400 bg-rose-50/30' : 'border-slate-200' }} text-slate-800 text-xs focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
                                        @error("items.{$idx}.item_name")
                                            <p class="text-[10px] text-rose-500 mt-0.5">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="p-2.5">
                                        <input type="number" name="items[{{ $idx }}][quantity]" value="{{ $item['quantity'] ?? 1 }}" min="1" required
                                               class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-slate-800 text-xs text-center focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
                                    </td>
                                    <td class="p-2.5">
                                        <input type="number" step="0.01" name="items[{{ $idx }}][weight_kg]" value="{{ $item['weight_kg'] ?? '' }}" min="0.01" required placeholder="0.00"
                                               class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-slate-800 text-xs text-right font-mono focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
                                    </td>
                                    <td class="p-2.5">
                                        <input type="text" name="items[{{ $idx }}][description]" value="{{ $item['description'] ?? '' }}" placeholder="Catatan spesifikasi / nomor pallet"
                                               class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
                                    </td>
                                    <td class="p-2.5 text-center">
                                        <button type="button" class="remove-item-btn p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Baris">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section 4: Catatan -->
            <div>
                <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Catatan Khusus / Instruksi Pengiriman
                </label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">{{ old('notes', $shipment->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t border-[#DFE1D8]/60 flex items-center justify-end space-x-3">
                <a href="{{ route('shipments.show', $shipment) }}" class="px-5 py-2.5 rounded-full border border-[#DFE1D8] bg-[#F5F6F1] text-[#1C1E1B] text-sm font-semibold hover:bg-[#E2E4DC] transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-sm font-bold shadow-xs transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Vanilla JS untuk Dynamic Rows & Validasi -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('itemsTbody');
    const addBtn = document.getElementById('addItemBtn');
    const form = document.getElementById('shipmentForm');

    function reindexRows() {
        const rows = tbody.querySelectorAll('.item-row');
        rows.forEach((row, index) => {
            row.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/items\[\d+\]/, `items[${index}]`));
                }
            });
        });
    }

    addBtn.addEventListener('click', function () {
        const rowCount = tbody.querySelectorAll('.item-row').length;
        const tr = document.createElement('tr');
        tr.className = 'item-row hover:bg-slate-50/50';
        tr.innerHTML = `
            <td class="p-2.5">
                <input type="text" name="items[${rowCount}][item_name]" required placeholder="Nama item / barang kargo"
                       class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
            </td>
            <td class="p-2.5">
                <input type="number" name="items[${rowCount}][quantity]" value="1" min="1" required
                       class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-slate-800 text-xs text-center focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
            </td>
            <td class="p-2.5">
                <input type="number" step="0.01" name="items[${rowCount}][weight_kg]" min="0.01" required placeholder="0.00"
                       class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-slate-800 text-xs text-right font-mono focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
            </td>
            <td class="p-2.5">
                <input type="text" name="items[${rowCount}][description]" placeholder="Catatan spesifikasi / nomor pallet"
                       class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-slate-800 text-xs focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
            </td>
            <td class="p-2.5 text-center">
                <button type="button" class="remove-item-btn p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Baris">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    tbody.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-item-btn');
        if (btn) {
            const rows = tbody.querySelectorAll('.item-row');
            if (rows.length <= 1) {
                alert('Pengiriman harus memiliki minimal 1 item muatan kargo.');
                return;
            }
            btn.closest('.item-row').remove();
            reindexRows();
        }
    });

    form.addEventListener('submit', function (e) {
        const origin = document.getElementById('origin_location_id').value;
        const destination = document.getElementById('destination_location_id').value;

        if (origin && destination && origin === destination) {
            e.preventDefault();
            alert('Titik Asal (Origin) dan Titik Tujuan (Destination) tidak boleh sama.');
            return false;
        }

        const departure = document.getElementById('expected_departure').value;
        const arrival = document.getElementById('expected_arrival').value;

        if (departure && arrival && new Date(arrival) <= new Date(departure)) {
            e.preventDefault();
            alert('Estimasi kedatangan (Expected Arrival) harus setelah jadwal keberangkatan (Expected Departure).');
            return false;
        }
    });
});
</script>
@endsection
