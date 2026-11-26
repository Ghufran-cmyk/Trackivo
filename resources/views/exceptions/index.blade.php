@extends('layouts.app')

@section('title', 'Manajemen Kendala (Exceptions)')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1C1E1B] tracking-tight">Manajemen Kendala Kargo</h1>
            <p class="text-xs sm:text-sm text-[#6F756C] mt-1">
                Pantau, investigasi, dan selesaikan insiden gangguan operasional kargo di seluruh jaringan logistik global Trackivo.
            </p>
        </div>
        <div class="shrink-0 flex items-center space-x-3">
            <a href="{{ route('shipments.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-full bg-white border border-[#DFE1D8] hover:bg-[#F5F6F1] text-[#1C1E1B] text-xs sm:text-sm font-semibold shadow-xs transition">
                Lihat Seluruh Pengiriman
            </a>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
        <form method="GET" action="{{ route('exceptions.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                
                <!-- Search resi/deskripsi -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Cari Resi / Catatan Kendala</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: TRK-2026-..., taifun, dokumen"
                               class="w-full pl-9 pr-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] placeholder-[#9EABA2] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <svg class="h-4 w-4 text-[#9EABA2] absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Status -->
                <div>
                    <label for="status" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Status Kendala</label>
                    <select name="status" id="status" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="">Semua Status</option>
                        @foreach($validStatuses as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tipe -->
                <div>
                    <label for="type" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Tipe Gangguan</label>
                    <select name="type" id="type" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="">Semua Tipe</option>
                        @foreach($validTypes as $tp)
                            <option value="{{ $tp }}" {{ request('type') === $tp ? 'selected' : '' }}>{{ $tp }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Severity -->
                <div>
                    <label for="severity" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Tingkat Keparahan</label>
                    <select name="severity" id="severity" class="w-full px-3.5 py-2.5 rounded-2xl border border-[#DFE1D8] bg-[#FAF9F5]/50 text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                        <option value="">Semua Severity</option>
                        @foreach($validSeverities as $sv)
                            <option value="{{ $sv }}" {{ request('severity') === $sv ? 'selected' : '' }}>{{ $sv }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-[#DFE1D8]/60">
                <a href="{{ route('exceptions.index') }}" class="px-4 py-2 rounded-full border border-[#DFE1D8] text-xs font-semibold text-[#1C1E1B] bg-[#F5F6F1] hover:bg-[#E2E4DC] transition">
                    Reset Filter
                </a>
                <button type="submit" class="px-5 py-2 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs font-bold shadow-xs transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Exceptions Table Card -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        <div class="p-6 border-b border-[#DFE1D8]/60 flex items-center justify-between">
            <h2 class="text-base font-bold text-[#1C1E1B]">Daftar Insiden Kendala Pengiriman</h2>
            <span class="text-xs text-[#6F756C]">Total: <span class="font-bold text-[#1C1E1B]">{{ $exceptions->total() }}</span> kendala tercatat</span>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-sm min-w-[680px]">
                <thead>
                    <tr class="bg-[#FAF9F5] text-[#6F756C] uppercase text-[10.5px] font-bold tracking-wider border-b border-[#DFE1D8]/60">
                        <th class="py-3.5 px-4">ID & Resi</th>
                        <th class="py-3.5 px-4">Tipe & Keparahan</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Dampak Delay</th>
                        <th class="py-3.5 px-4">Rincian Kendala</th>
                        <th class="py-3.5 px-4">Waktu Terdeteksi</th>
                        <th class="py-3.5 px-4 text-right">Penanganan / Resolusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DFE1D8]/40">
                    @forelse($exceptions as $exception)
                        <tr class="hover:bg-[#F5F6F1] transition items-start text-xs sm:text-[13px]">
                            
                            <!-- ID & Resi -->
                            <td class="py-4 px-4">
                                <span class="font-mono text-xs font-bold text-[#6F756C] block mb-0.5">#{{ $exception->id }}</span>
                                @if($exception->shipment)
                                    <a href="{{ route('shipments.show', $exception->shipment) }}" class="font-mono text-xs font-bold text-[#556E5C] hover:text-[#425848] hover:underline inline-flex items-center space-x-1">
                                        <span>{{ $exception->shipment->tracking_number }}</span>
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <span class="block text-[11px] text-[#6F756C] mt-0.5">{{ $exception->shipment->customer?->name }}</span>
                                @else
                                    <span class="text-xs text-[#6F756C]">-</span>
                                @endif
                            </td>

                            <!-- Tipe & Keparahan -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-xs text-[#1C1E1B] mb-1">
                                    {{ $exception->type }}
                                </div>
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $exception->severity === 'HIGH' ? 'bg-[#F9E6E6] text-[#B84242]' : 
                                          ($exception->severity === 'MEDIUM' ? 'bg-[#F7EFE1] text-[#9A6721]' : 
                                          'bg-[#F1F2EC] text-[#6F756C]') }}">
                                        {{ $exception->severity }}
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase inline-flex items-center space-x-1.5
                                    {{ $exception->status === 'RESOLVED' ? 'bg-[#E6F0E9] text-[#2F6846]' : 
                                      ($exception->status === 'INVESTIGATING' ? 'bg-[#F7EFE1] text-[#9A6721]' : 
                                      'bg-[#F9E6E6] text-[#B84242]') }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $exception->status === 'RESOLVED' ? 'bg-[#2F6846]' : ($exception->status === 'INVESTIGATING' ? 'bg-[#D4AF77]' : 'bg-[#B84242]') }}"></span>
                                    <span>{{ $exception->status }}</span>
                                </span>
                            </td>

                            <!-- Dampak Delay -->
                            <td class="py-4 px-4">
                                @if($exception->delay_impact_hours > 0)
                                    <span class="font-mono text-xs font-bold text-[#B84242] bg-[#F9E6E6] px-2.5 py-0.5 rounded-full border border-[#F0C9C9]">
                                        +{{ $exception->delay_impact_hours }} Jam
                                    </span>
                                @else
                                    <span class="text-xs text-[#6F756C] font-mono">0 Jam</span>
                                @endif
                            </td>

                            <!-- Rincian Kendala & Pelapor -->
                            <td class="py-4 px-4 max-w-xs">
                                <p class="text-xs text-[#1C1E1B] leading-snug line-clamp-2" title="{{ $exception->description }}">
                                    {{ $exception->description }}
                                </p>
                                @if($exception->resolution_note)
                                    <div class="mt-2 p-2.5 rounded-xl bg-[#E6F0E9]/70 border border-[#C8DACD] text-[11px] text-[#2F6846] leading-snug">
                                        <strong class="font-semibold text-[#2F6846] block">Resolusi:</strong>
                                        {{ $exception->resolution_note }}
                                    </div>
                                @endif
                                <span class="block text-[10px] text-[#6F756C] mt-1">
                                    Dilaporkan oleh: <strong class="text-[#1C1E1B]">{{ $exception->reporter?->name ?? 'Sistem' }}</strong>
                                </span>
                            </td>

                            <!-- Waktu Terdeteksi & Penyelesaian -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-xs text-[#1C1E1B] font-medium">
                                    {{ $exception->detected_at ? $exception->detected_at->format('d/m/Y H:i') : '-' }}
                                </div>
                                @if($exception->resolved_at)
                                    <div class="text-[10px] text-[#2F6846] font-medium mt-1">
                                        Selesai: {{ $exception->resolved_at->format('d/m/Y H:i') }}
                                        <br>
                                        <span class="text-[#6F756C] font-normal">Oleh: {{ $exception->resolver?->name ?? '-' }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Aksi Update Status & Resolusi -->
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                @if($exception->status === 'RESOLVED')
                                    <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full bg-[#E6F0E9] text-[#2F6846] text-xs font-semibold">
                                        <svg class="h-3.5 w-3.5 text-[#2F6846]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Terselesaikan</span>
                                    </span>
                                @else
                                    <!-- Tombol trigger form inline / dialog -->
                                    <details class="relative inline-block text-left">
                                        <summary class="list-none inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs font-bold cursor-pointer transition shadow-xs">
                                            <span>Ubah Status</span>
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </summary>
                                        <div class="absolute right-0 mt-2 w-80 bg-white rounded-[20px] shadow-xl border border-[#DFE1D8] p-5 z-50 text-left">
                                            <h4 class="text-xs font-bold text-[#1C1E1B] border-b border-[#DFE1D8]/60 pb-2 mb-3">
                                                Update Status Kendala #{{ $exception->id }}
                                            </h4>
                                            <form method="POST" action="{{ route('exceptions.update', $exception) }}" class="space-y-3">
                                                @csrf
                                                @method('PATCH')

                                                <div>
                                                    <label class="block text-[11px] font-bold text-[#6F756C] uppercase mb-1">Status Baru</label>
                                                    <select name="status" 
                                                            required
                                                            onchange="toggleResolutionNote(this, 'res-note-{{ $exception->id }}')"
                                                            class="w-full px-3 py-2 text-xs rounded-xl border border-[#DFE1D8] bg-[#FAF9F5] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">
                                                        @if($exception->status === 'OPEN')
                                                            <option value="INVESTIGATING">INVESTIGATING (Dalam Penanganan)</option>
                                                            <option value="RESOLVED">RESOLVED (Terselesaikan)</option>
                                                        @elseif($exception->status === 'INVESTIGATING')
                                                            <option value="RESOLVED" selected>RESOLVED (Terselesaikan)</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <div id="res-note-{{ $exception->id }}" class="{{ $exception->status === 'OPEN' ? 'hidden' : 'block' }}">
                                                    <label class="block text-[11px] font-bold text-[#6F756C] uppercase mb-1">
                                                        Catatan Resolusi / Solusi <span class="text-rose-500">*</span>
                                                    </label>
                                                    <textarea name="resolution_note" 
                                                              rows="2" 
                                                              placeholder="Jelaskan langkah penyelesaian kendala..."
                                                              class="w-full px-3 py-2 text-xs rounded-xl border border-[#DFE1D8] bg-[#FAF9F5] focus:outline-none focus:border-[#556E5C] focus:ring-1 focus:ring-[#556E5C]">{{ old('resolution_note') }}</textarea>
                                                </div>

                                                <div class="pt-2 flex justify-end space-x-2">
                                                    <button type="submit" class="w-full px-4 py-2 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs font-bold transition shadow-xs">
                                                        Simpan Status
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </details>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-5 text-center text-[#6F756C] text-sm">
                                Tidak ada kendala operasional yang ditemukan dengan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($exceptions->hasPages())
            <div class="p-5 border-t border-[#DFE1D8]/60">
                {{ $exceptions->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    function toggleResolutionNote(selectEl, targetId) {
        const targetDiv = document.getElementById(targetId);
        if (targetDiv) {
            if (selectEl.value === 'RESOLVED') {
                targetDiv.classList.remove('hidden');
                targetDiv.classList.add('block');
                const textarea = targetDiv.querySelector('textarea');
                if (textarea) textarea.setAttribute('required', 'required');
            } else {
                targetDiv.classList.add('hidden');
                targetDiv.classList.remove('block');
                const textarea = targetDiv.querySelector('textarea');
                if (textarea) textarea.removeAttribute('required');
            }
        }
    }
</script>
@endsection
