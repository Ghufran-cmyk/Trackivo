@extends('layouts.app')

@section('title', 'Buat Shipment Baru')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">

    <!-- Breadcrumb & Back -->
    <div class="mb-6">
        <a href="{{ route('shipments.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Pengiriman</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        
        <!-- Header Banner -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-[#7B9581] to-[#556E5C] text-white">
            <div class="flex items-center space-x-2 mb-2">
                <span class="px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold uppercase tracking-wider">Operasional Pengiriman</span>
                <span class="text-xs text-white/80">&bull; Status Awal: <strong class="text-white">CREATED</strong></span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Buat Pengiriman (Shipment) Baru</h1>
            <p class="text-xs sm:text-sm text-white/90 mt-1">Lengkapi data rute, carrier, moda transportasi, dan daftar item muatan kargo.</p>
        </div>

        <form method="POST" action="{{ route('shipments.store') }}" id="shipmentForm" class="p-6 sm:p-8 space-y-8">
            @csrf

            <!-- Section 1: Informasi Utama & Pelanggan -->
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                    <span>1. Data Pelanggan & Identitas Kargo</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Nomor Resi (Opsional) -->
                    <div>
                        <label for="tracking_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Resi / Tracking No.
                        </label>
                        <input type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number') }}" 
                               placeholder="Otomatis (TRK-YYYY-XXXXXX)"
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('tracking_number') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] font-mono">
                        <p class="mt-1 text-[11px] text-slate-400">Kosongkan jika ingin digenerate otomatis oleh sistem.</p>
                        @error('tracking_number')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pelanggan (Customer) -->
                    <div>
                        <label for="customer_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pemilik Kargo (Customer) <span class="text-rose-500">*</span>
                        </label>
                        <select name="customer_id" id="customer_id" required
                                class="w-full appearance-none px-3.5 py-2.5 rounded-xl border {{ $errors->has('customer_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
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
                                class="w-full appearance-none px-3.5 py-2.5 rounded-xl border {{ $errors->has('carrier_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            <option value="">-- Pilih Carrier --</option>
                            @foreach($carriers as $carrier)
                                <option value="{{ $carrier->id }}" {{ old('carrier_id') == $carrier->id ? 'selected' : '' }}>
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
                                class="w-full appearance-none px-3.5 py-2.5 rounded-xl border {{ $errors->has('transport_mode') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            <option value="">-- Pilih Moda Transportasi --</option>
                            <option value="OCEAN" {{ old('transport_mode') == 'OCEAN' ? 'selected' : '' }}>OCEAN (Kapal Laut / Kontainer Samudera)</option>
                            <option value="AIR" {{ old('transport_mode') == 'AIR' ? 'selected' : '' }}>AIR (Pesawat Udara / Air Freight)</option>
                            <option value="LAND" {{ old('transport_mode') == 'LAND' ? 'selected' : '' }}>LAND (Truk / Kereta Api Logistik Darat)</option>
                        </select>
                        @error('transport_mode')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cargo Type -->
                    <div>
                        <label for="cargo_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kategori / Jenis Kargo <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="cargo_type" id="cargo_type" value="{{ old('cargo_type') }}" required 
                               placeholder="Contoh: Komponen Elektronik, Tekstil, FMCG"
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('cargo_type') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                        @error('cargo_type')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Weight (Kg) -->
                    <div>
                        <label for="weight_kg" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Total Berat Kargo (Kg) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="weight_kg" id="weight_kg" value="{{ old('weight_kg') }}" required placeholder="Contoh: 1250.50"
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('weight_kg') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] font-mono">
                        @error('weight_kg')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Volume CBM (Opsional) -->
                    <div>
                        <label for="volume_cbm" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Volume Kargo (CBM)
                        </label>
                        <input type="number" step="0.01" name="volume_cbm" id="volume_cbm" value="{{ old('volume_cbm') }}" placeholder="Contoh: 15.80"
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('volume_cbm') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C] font-mono">
                        @error('volume_cbm')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Rute & Jadwal Pengiriman -->
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                    <span>2. Rute Asal - Tujuan & Estimasi Jadwal</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Titik Asal (Origin) -->
                    <div>
                        <label for="origin_location_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Titik Asal (Origin) <span class="text-rose-500">*</span>
                        </label>
                        <select name="origin_location_id" id="origin_location_id" required
                                class="w-full appearance-none px-3.5 py-2.5 rounded-xl border {{ $errors->has('origin_location_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            <option value="">-- Pilih Titik Asal --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('origin_location_id') == $loc->id ? 'selected' : '' }}>
                                    [{{ $loc->code }}] {{ $loc->name }} - {{ $loc->country }} ({{ $loc->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('origin_location_id')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Titik Tujuan (Destination) -->
                    <div>
                        <label for="destination_location_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Titik Tujuan (Destination) <span class="text-rose-500">*</span>
                        </label>
                        <select name="destination_location_id" id="destination_location_id" required
                                class="w-full appearance-none px-3.5 py-2.5 rounded-xl border {{ $errors->has('destination_location_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                            <option value="">-- Pilih Titik Tujuan --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('destination_location_id') == $loc->id ? 'selected' : '' }}>
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
                            Jadwal Keberangkatan (Expected Departure) <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" name="expected_departure" id="expected_departure" value="{{ old('expected_departure') }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('expected_departure') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                        @error('expected_departure')
                            <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Arrival -->
                    <div>
                        <label for="expected_arrival" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Estimasi Kedatangan (Expected Arrival) <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" name="expected_arrival" id="expected_arrival" value="{{ old('expected_arrival') }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('expected_arrival') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                        <p class="mt-1 text-[11px] text-slate-400">Harus setelah jadwal keberangkatan.</p>
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
                        <span>3. Rincian Item Muatan Kargo (Minimal 1 Item) <span class="text-rose-500">*</span></span>
                    </h2>
                    <button type="button" id="addItemBtn" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-full bg-[#556E5C] text-white text-xs font-bold hover:bg-[#425848] transition shadow-xs">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Baris Item</span>
                    </button>
                </div>

                @if($errors->has('items'))
                    <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                        {{ $errors->first('items') }}
                    </div>
                @endif

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
                                $oldItems = old('items', [
                                    ['item_name' => '', 'quantity' => 1, 'weight_kg' => '', 'description' => '']
                                ]);
                            @endphp

                            @foreach($oldItems as $idx => $item)
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
                                               class="w-full px-3 py-1.5 rounded-lg border {{ $errors->has("items.{$idx}.quantity") ? 'border-rose-400 bg-rose-50/30' : 'border-slate-200' }} text-slate-800 text-xs text-center focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
                                    </td>
                                    <td class="p-2.5">
                                        <input type="number" step="0.01" name="items[{{ $idx }}][weight_kg]" value="{{ $item['weight_kg'] ?? '' }}" min="0.01" required placeholder="0.00"
                                               class="w-full px-3 py-1.5 rounded-lg border {{ $errors->has("items.{$idx}.weight_kg") ? 'border-rose-400 bg-rose-50/30' : 'border-slate-200' }} text-slate-800 text-xs text-right font-mono focus:ring-2 focus:ring-[#556E5C]/20 focus:border-[#556E5C] focus:outline-none">
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

            <!-- Section 4: Rincian Biaya & Pembayaran (IDR) -->
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                    <span>4. Rincian Biaya & Pembayaran (IDR)</span>
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                    <!-- Form Input Pembayaran -->
                    <div class="lg:col-span-2 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Biaya Layanan & Asuransi (Rp) -->
                            <div>
                                <label for="insurance_cost" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Biaya Layanan & Asuransi (Rp)
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-semibold text-slate-500">Rp</span>
                                    <input type="number" name="insurance_cost" id="insurance_cost" min="0" step="1000"
                                           value="{{ old('insurance_cost', 0) }}"
                                           placeholder="0"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border {{ $errors->has('insurance_cost') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                                </div>
                                <p class="mt-1 text-[11px] text-slate-400">Proteksi tambahan kargo opsional (Rp).</p>
                                @error('insurance_cost')
                                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Metode Pembayaran -->
                            <div>
                                <label for="payment_method" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Metode Pembayaran <span class="text-rose-500">*</span>
                                </label>
                                <select name="payment_method" id="payment_method" required
                                        class="w-full appearance-none px-3.5 py-2.5 rounded-xl border {{ $errors->has('payment_method') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                                    <option value="BANK_TRANSFER" {{ old('payment_method', 'BANK_TRANSFER') == 'BANK_TRANSFER' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="CASH" {{ old('payment_method') == 'CASH' ? 'selected' : '' }}>Tunai (Loket)</option>
                                    <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="CORPORATE_INVOICE" {{ old('payment_method') == 'CORPORATE_INVOICE' ? 'selected' : '' }}>Tagihan Invoice B2B</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Pembayaran Awal -->
                        <div>
                            <label for="payment_status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Status Pembayaran Awal <span class="text-rose-500">*</span>
                            </label>
                            <select name="payment_status" id="payment_status" required
                                    class="w-full appearance-none px-3.5 py-2.5 rounded-xl border {{ $errors->has('payment_status') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 bg-white' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">
                                <option value="UNPAID" {{ old('payment_status', 'UNPAID') == 'UNPAID' ? 'selected' : '' }}>Belum Lunas (UNPAID)</option>
                                <option value="PAID" {{ old('payment_status') == 'PAID' ? 'selected' : '' }}>Lunas di Loket (PAID)</option>
                            </select>
                            <p class="mt-1 text-[11px] text-slate-400">Pilih "Lunas di Loket" jika pembayaran tunai/transfer langsung diselesaikan saat pembuatan resi.</p>
                            @error('payment_status')
                                <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kartu Kalkulator Estimasi Live -->
                    <div class="bg-[#F6F7F3] border border-[#DFE1D8] rounded-2xl p-5 shadow-xs">
                        <div class="flex items-center justify-between pb-3 border-b border-[#DFE1D8]">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#556E5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#243329]">Ringkasan Tarif (Live)</span>
                            </div>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#E9EAE4] text-[#3E5245] border border-[#DFE1D8]">IDR</span>
                        </div>

                        <div class="mt-4 space-y-3 text-xs text-slate-600">
                            <div class="flex justify-between items-center">
                                <span>Biaya Kirim Dasar:</span>
                                <span id="displayBaseCost" class="font-mono font-semibold text-[#243329]">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Biaya Berat & Volume:</span>
                                <span id="displayWeightVolumeCost" class="font-mono font-semibold text-[#243329]">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Biaya Asuransi:</span>
                                <span id="displayInsuranceCost" class="font-mono font-semibold text-[#243329]">Rp 0</span>
                            </div>

                            <div class="pt-3 border-t border-[#DFE1D8]">
                                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Total Pembayaran:</div>
                                <div id="displayTotalAmount" class="text-xl font-bold text-[#243329] font-mono tracking-tight">Rp 0</div>
                            </div>
                        </div>

                        <div class="mt-4 p-2.5 rounded-xl bg-white border border-[#DFE1D8]/80 text-[11px] text-slate-500">
                            Kalkulasi otomatis real-time berdasarkan tarif deterministik moda, berat, volume, dan proteksi asuransi.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Catatan Tambahan (Opsional) -->
            <div>
                <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Catatan Khusus / Instruksi Pengiriman
                </label>
                <textarea name="notes" id="notes" rows="3" placeholder="Tambahkan instruksi penanganan khusus atau catatan dokumen kargo bila ada..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#556E5C] focus:border-[#556E5C]">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t border-[#DFE1D8]/60 flex items-center justify-end space-x-3">
                <a href="{{ route('shipments.index') }}" class="px-5 py-2.5 rounded-full border border-[#DFE1D8] bg-[#F5F6F1] text-[#1C1E1B] text-sm font-semibold hover:bg-[#E2E4DC] transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-sm font-bold shadow-xs transition">
                    Buat Shipment Sekarang
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Vanilla JS untuk Dynamic Table Row & Validasi Frontend -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('itemsTbody');
    const addBtn = document.getElementById('addItemBtn');
    const form = document.getElementById('shipmentForm');

    // Function to re-index rows
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

    // Add new row
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

    // Remove row event delegation
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

    // Form submission validation (Origin vs Destination & Dates)
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

    // Real-time Shipping Rate Live Calculator
    const transportModeSelect = document.getElementById('transport_mode');
    const weightInput = document.getElementById('weight_kg');
    const volumeInput = document.getElementById('volume_cbm');
    const insuranceInput = document.getElementById('insurance_cost');

    const displayBase = document.getElementById('displayBaseCost');
    const displayWeightVolume = document.getElementById('displayWeightVolumeCost');
    const displayInsurance = document.getElementById('displayInsuranceCost');
    const displayTotal = document.getElementById('displayTotalAmount');

    const rateTable = {
        'OCEAN': { base: 750000, rateKg: 1500, rateCbm: 250000 },
        'AIR':   { base: 1500000, rateKg: 8500, rateCbm: 600000 },
        'LAND':  { base: 350000, rateKg: 2000, rateCbm: 150000 }
    };

    function formatRupiah(number) {
        return 'Rp ' + Math.round(number).toLocaleString('id-ID');
    }

    function calculateShippingCost() {
        const mode = transportModeSelect ? (transportModeSelect.value || 'LAND') : 'LAND';
        const rate = rateTable[mode] || rateTable['LAND'];

        const weight = weightInput ? parseFloat(weightInput.value) || 0 : 0;
        const volume = volumeInput ? parseFloat(volumeInput.value) || 0 : 0;
        const insurance = insuranceInput ? parseInt(insuranceInput.value) || 0 : 0;

        const baseCost = rate.base;
        const weightVolumeCost = Math.round((weight * rate.rateKg) + (volume * rate.rateCbm));
        const insuranceCost = Math.max(0, insurance);
        const totalAmount = baseCost + weightVolumeCost + insuranceCost;

        if (displayBase) displayBase.textContent = formatRupiah(baseCost);
        if (displayWeightVolume) displayWeightVolumeCost.textContent = formatRupiah(weightVolumeCost);
        if (displayInsurance) displayInsurance.textContent = formatRupiah(insuranceCost);
        if (displayTotal) displayTotal.textContent = formatRupiah(totalAmount);
    }

    if (transportModeSelect) transportModeSelect.addEventListener('change', calculateShippingCost);
    if (weightInput) weightInput.addEventListener('input', calculateShippingCost);
    if (volumeInput) volumeInput.addEventListener('input', calculateShippingCost);
    if (insuranceInput) insuranceInput.addEventListener('input', calculateShippingCost);

    // Initial calculation
    calculateShippingCost();
});
</script>
@endsection
