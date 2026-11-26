<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran & Resi Resmi - {{ $shipment->tracking_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        };
    </script>
    <style>
        @media print {
            body {
                background: #ffffff !important;
                color: #243329 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print\:hidden {
                display: none !important;
            }
            .print\:shadow-none {
                box-shadow: none !important;
            }
            .print\:border-0 {
                border: 0 !important;
            }
            .print\:rounded-none {
                border-radius: 0 !important;
            }
            .print\:p-0 {
                padding: 0 !important;
            }
            .print\:max-w-full {
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-[#F6F7F3] text-[#243329] font-['Plus_Jakarta_Sans',sans-serif] antialiased min-h-screen py-8 px-4 sm:px-6 print:p-0 print:bg-white">

    <!-- Top Action Toolbar (Hidden during print) -->
    <div class="max-w-3xl mx-auto mb-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 print:hidden">
        <a href="{{ route('shipments.show', $shipment) }}"
           class="inline-flex items-center space-x-2 text-xs font-bold text-[#3E5245] hover:text-[#243329] transition bg-[#E9EAE4] hover:bg-[#DFE1D8] px-4 py-2 rounded-full border border-[#DFE1D8]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Detail Pengiriman</span>
        </a>

        <button type="button" onclick="window.print()"
                class="inline-flex items-center space-x-2 text-xs font-bold text-white bg-[#243329] hover:bg-[#3E5245] px-5 py-2.5 rounded-full shadow-xs transition cursor-pointer">
            <svg class="w-4 h-4 text-[#C8D3BE]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Official Receipt Document Card -->
    <div class="max-w-3xl mx-auto bg-white rounded-3xl border border-[#DFE1D8] shadow-sm print:shadow-none print:border-0 print:rounded-none overflow-hidden print:max-w-full">
        
        <!-- Header Struk (Band Sage Tua #243329) -->
        <div class="bg-[#243329] text-white p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2.5">
                    <span class="w-3 h-3 rounded-full bg-[#C8D3BE]"></span>
                    <span class="text-xl font-extrabold tracking-tight">TRACKIVO</span>
                </div>
                <p class="text-xs text-[#C8D3BE] mt-1 font-medium">Global Shipment & Cargo Logistics</p>
            </div>
            <div class="sm:text-right">
                <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-[#C8D3BE] text-[10px] font-bold uppercase tracking-wider mb-1.5 border border-white/15">
                    STRUK PEMBAYARAN & RESI RESMI
                </span>
                <div class="text-sm font-mono font-bold text-white tracking-wide">
                    {{ $shipment->invoice_number ?? 'INV-PENDING' }}
                </div>
                <div class="text-[11px] text-white/70 mt-0.5">
                    Tanggal: {{ $shipment->created_at ? $shipment->created_at->format('d F Y, H:i') : '-' }} WIB
                </div>
            </div>
        </div>

        <!-- Strip Nomor Resi & Status -->
        <div class="px-6 sm:px-8 py-4 bg-[#F6F7F3] border-b border-[#DFE1D8] flex flex-wrap items-center justify-between gap-3">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Nomor Resi Pengiriman (AWB / Tracking Number)</span>
                <span class="text-lg sm:text-xl font-extrabold font-mono text-[#243329] tracking-tight">
                    {{ $shipment->tracking_number }}
                </span>
            </div>
            <div>
                @if($shipment->payment_status === 'PAID')
                    <div class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-full bg-[#E9EAE4] text-[#243329] border border-[#C8D3BE] text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-[#556E5C]"></span>
                        <span>STATUS: LUNAS</span>
                    </div>
                @else
                    <div class="inline-flex items-center space-x-1.5 px-4 py-1.5 rounded-full bg-[#D8CFC0]/40 text-[#B96A55] border border-[#D8CFC0] text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-[#B96A55]"></span>
                        <span>STATUS: BELUM LUNAS</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Body Content -->
        <div class="p-6 sm:p-8 space-y-6">

            <!-- Grid Informasi 2 Kolom -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kolom Kiri: Data Pengirim / Pelanggan -->
                <div class="bg-[#F6F7F3] rounded-2xl p-4 border border-[#DFE1D8] space-y-2">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-[#3E5245] pb-1.5 border-b border-[#DFE1D8]">
                        Data Pelanggan (Customer)
                    </div>
                    <div class="text-sm font-bold text-[#243329]">{{ $shipment->customer?->name }}</div>
                    @if($shipment->customer?->company_name)
                        <div class="text-xs font-medium text-slate-700">{{ $shipment->customer->company_name }}</div>
                    @endif
                    <div class="text-xs text-slate-500 font-mono">{{ $shipment->customer?->email ?? '-' }}</div>
                    <div class="text-xs text-slate-500">{{ $shipment->customer?->phone ?? '-' }}</div>
                </div>

                <!-- Kolom Kanan: Ringkasan Layanan -->
                <div class="bg-[#F6F7F3] rounded-2xl p-4 border border-[#DFE1D8] space-y-2">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-[#3E5245] pb-1.5 border-b border-[#DFE1D8]">
                        Rincian Layanan & Rute
                    </div>
                    <div class="text-xs text-slate-600">
                        <span class="text-slate-400">Rute Pengiriman:</span>
                        <div class="font-bold text-[#243329] text-sm mt-0.5">
                            {{ $shipment->originLocation?->name }} ({{ $shipment->originLocation?->code }})
                            &rarr;
                            {{ $shipment->destinationLocation?->name }} ({{ $shipment->destinationLocation?->code }})
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xs text-slate-600 pt-1">
                        <span>Carrier: <strong class="text-slate-800">{{ $shipment->carrier?->name }} ({{ $shipment->carrier?->code }})</strong></span>
                        <span class="px-2 py-0.5 rounded-full bg-[#E9EAE4] text-[#243329] text-[10px] font-bold border border-[#DFE1D8]">{{ $shipment->transport_mode }}</span>
                    </div>
                    <div class="text-[11px] text-slate-500 pt-1">
                        Estimasi Tiba: <strong class="text-slate-700 font-mono">{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d/m/Y H:i') : ($shipment->expected_arrival ? $shipment->expected_arrival->format('d/m/Y H:i') : '-') }} WIB</strong>
                    </div>
                </div>
            </div>

            <!-- Tabel Rincian Muatan Kargo -->
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-[#243329] mb-2.5 flex items-center justify-between">
                    <span>Rincian Muatan Kargo ({{ $shipment->cargo_type }})</span>
                    <span class="text-[11px] font-normal text-slate-500 font-mono">
                        Total Berat: <strong>{{ number_format($shipment->weight_kg, 2) }} kg</strong> &bull; Volume: <strong>{{ number_format($shipment->volume_cbm ?? 0, 2) }} CBM</strong>
                    </span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-[#DFE1D8]">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#F6F7F3] text-[#3E5245] uppercase text-[10px] font-bold border-b border-[#DFE1D8]">
                            <tr>
                                <th class="py-2.5 px-3.5">No.</th>
                                <th class="py-2.5 px-3.5">Nama Item / Deskripsi</th>
                                <th class="py-2.5 px-3.5 text-center">Kuantitas</th>
                                <th class="py-2.5 px-3.5 text-right">Berat (Kg)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#DFE1D8]">
                            @forelse($shipment->items as $idx => $item)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-2.5 px-3.5 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                    <td class="py-2.5 px-3.5 font-medium text-slate-800">
                                        {{ $item->item_name }}
                                        @if($item->description)
                                            <span class="text-slate-400 block text-[11px]">{{ $item->description }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center font-bold text-slate-700">{{ $item->quantity }}</td>
                                    <td class="py-2.5 px-3.5 text-right font-mono text-slate-800">{{ number_format($item->weight_kg, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-3 px-3.5 text-slate-500 text-center">Kargo utama: {{ $shipment->cargo_type }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Rincian Pembayaran (IDR) -->
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-[#243329] mb-2.5">
                    Rincian Pembayaran & Tarif (IDR)
                </div>

                <div class="rounded-2xl border border-[#DFE1D8] overflow-hidden">
                    <table class="w-full text-xs">
                        <tbody class="divide-y divide-[#DFE1D8]">
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-2.5 px-4 text-slate-600">Biaya Kirim Dasar (Moda {{ $shipment->transport_mode }})</td>
                                <td class="py-2.5 px-4 text-right font-mono font-medium text-slate-800">
                                    {{ \App\Services\ShippingRateService::formatRupiah($shipment->base_cost) }}
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-2.5 px-4 text-slate-600">Biaya Berat & Volume (Tarif per kg / CBM)</td>
                                <td class="py-2.5 px-4 text-right font-mono font-medium text-slate-800">
                                    {{ \App\Services\ShippingRateService::formatRupiah($shipment->weight_volume_cost) }}
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-2.5 px-4 text-slate-600">Biaya Layanan & Asuransi Kargo</td>
                                <td class="py-2.5 px-4 text-right font-mono font-medium text-slate-800">
                                    {{ \App\Services\ShippingRateService::formatRupiah($shipment->insurance_cost) }}
                                </td>
                            </tr>
                            <tr class="bg-[#F6F7F3] font-bold text-sm">
                                <td class="py-3 px-4 text-[#243329] uppercase tracking-wider">TOTAL PEMBAYARAN</td>
                                <td class="py-3 px-4 text-right font-mono text-base text-[#243329]">
                                    {{ \App\Services\ShippingRateService::formatRupiah($shipment->total_amount) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Struk + QR Code Pelacakan -->
            <div class="pt-4 border-t border-[#DFE1D8] flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <!-- Info Status Pelunasan & Petugas -->
                <div class="space-y-1.5 text-xs text-slate-600 flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="text-slate-400">Metode Pembayaran:</span>
                        <strong class="text-slate-800">{{ \App\Services\ShippingRateService::getPaymentMethodLabel($shipment->payment_method) }}</strong>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-slate-400">Status Transaksi:</span>
                        <strong class="{{ $shipment->payment_status === 'PAID' ? 'text-[#3E5245]' : 'text-[#B96A55]' }}">
                            {{ $shipment->payment_status === 'PAID' ? 'LUNAS' : 'MENUNGGU PELUNASAN' }}
                        </strong>
                    </div>
                    @if($shipment->payment_status === 'PAID' && $shipment->paid_at)
                        <div class="flex items-center space-x-2">
                            <span class="text-slate-400">Waktu Lunas:</span>
                            <span class="font-mono text-slate-700">{{ $shipment->paid_at->format('d/m/Y H:i') }} WIB</span>
                        </div>
                    @endif
                    @if($shipment->paymentConfirmedBy)
                        <div class="flex items-center space-x-2">
                            <span class="text-slate-400">Petugas Pemroses:</span>
                            <span class="text-slate-700">{{ $shipment->paymentConfirmedBy->name }}</span>
                        </div>
                    @endif
                    <p class="text-[10px] text-slate-400 italic pt-1 leading-relaxed">
                        Dokumen ini diterbitkan secara elektronik oleh sistem Trackivo dan sah sebagai bukti transaksi pengiriman.
                    </p>
                </div>

                <!-- Kotak QR Code Pelacakan Resi -->
                @php
                    $trackingUrl = url('/track?tracking_number=' . urlencode($shipment->tracking_number));
                @endphp
                <div class="flex flex-col items-center text-center bg-[#F6F7F3] p-3.5 rounded-2xl border border-[#DFE1D8] shrink-0">
                    <div id="qrcode" class="p-1.5 bg-white rounded-xl shadow-xs border border-[#DFE1D8]"></div>
                    <div id="qrcode-fallback" class="hidden text-[10px] font-mono text-slate-600 bg-white p-2 rounded-lg border border-[#DFE1D8] max-w-[140px] break-all">
                        {{ $trackingUrl }}
                    </div>
                    <span class="text-[10px] font-semibold text-[#3E5245] mt-2">Scan untuk melacak posisi kargo</span>
                    <span class="text-[9px] text-slate-400 font-mono">{{ $shipment->tracking_number }}</span>
                </div>
            </div>

        </div>

        <!-- Footer Baris Terbawah -->
        <div class="bg-[#E9EAE4] px-6 py-3 border-t border-[#DFE1D8] text-center text-[11px] text-[#3E5245]">
            &copy; {{ date('Y') }} Trackivo Visibility Platform &bull; Layanan Pengiriman Kargo Aman, Terpadu & Terpercaya
        </div>
    </div>

    <!-- QR Code Script (qrcodejs) with Offline Fallback -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const trackUrl = @json($trackingUrl);
            const qrContainer = document.getElementById('qrcode');
            const qrFallback = document.getElementById('qrcode-fallback');

            if (typeof QRCode !== 'undefined' && qrContainer) {
                try {
                    new QRCode(qrContainer, {
                        text: trackUrl,
                        width: 96,
                        height: 96,
                        colorDark: "#243329",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                } catch (e) {
                    if (qrContainer) qrContainer.style.display = 'none';
                    if (qrFallback) qrFallback.classList.remove('hidden');
                }
            } else {
                if (qrContainer) qrContainer.style.display = 'none';
                if (qrFallback) qrFallback.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
