@extends('layouts.app')

@section('title', 'Manajemen Kendala (Exceptions)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-slate-900 text-[#d4f93a] text-xs font-semibold mb-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d4f93a]"></span>
                <span>Control Tower Operasional</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Kendala Kargo (Shipment Exceptions)</h1>
            <p class="text-sm text-slate-500 mt-1">
                Pantau, investigasi, dan selesaikan insiden gangguan operasional kargo di seluruh jaringan logistik global Trackivo.
            </p>
        </div>
        <div class="shrink-0 flex items-center space-x-3">
            <a href="{{ route('shipments.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-sm transition">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Lihat Seluruh Pengiriman</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('exceptions.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                
                <!-- Search resi/deskripsi -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cari Resi / Catatan Kendala</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: TRK-2026-..., taifun, dokumen"
                               class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <svg class="h-4 w-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Status -->
                <div>
                    <label for="status" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Kendala</label>
                    <select name="status" id="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Status</option>
                        @foreach($validStatuses as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tipe -->
                <div>
                    <label for="type" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tipe Gangguan</label>
                    <select name="type" id="type" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Tipe</option>
                        @foreach($validTypes as $tp)
                            <option value="{{ $tp }}" {{ request('type') === $tp ? 'selected' : '' }}>{{ $tp }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Severity -->
                <div>
                    <label for="severity" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tingkat Keparahan</label>
                    <select name="severity" id="severity" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Severity</option>
                        @foreach($validSeverities as $sv)
                            <option value="{{ $sv }}" {{ request('severity') === $sv ? 'selected' : '' }}>{{ $sv }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <a href="{{ route('exceptions.index') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Reset Filter
                </a>
                <button type="submit" class="px-4 py-1.5 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-xs font-bold shadow-sm transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Exceptions Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Daftar Insiden Kendala Pengiriman</h2>
            <span class="text-xs text-slate-500">Total: <span class="font-semibold text-slate-700">{{ $exceptions->total() }}</span> kendala tercatat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-5">ID & Resi</th>
                        <th class="py-3.5 px-5">Tipe & Keparahan</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Dampak Delay</th>
                        <th class="py-3.5 px-5">Rincian Kendala</th>
                        <th class="py-3.5 px-5">Waktu Terdeteksi</th>
                        <th class="py-3.5 px-5 text-right">Penanganan / Resolusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($exceptions as $exception)
                        <tr class="hover:bg-slate-50/60 transition items-start">
                            
                            <!-- ID & Resi -->
                            <td class="py-4 px-5">
                                <span class="font-mono text-xs font-bold text-slate-400 block mb-0.5">#{{ $exception->id }}</span>
                                @if($exception->shipment)
                                    <a href="{{ route('shipments.show', $exception->shipment) }}" class="font-mono text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline inline-flex items-center space-x-1">
                                        <span>{{ $exception->shipment->tracking_number }}</span>
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <span class="block text-[11px] text-slate-400 mt-0.5">{{ $exception->shipment->customer?->name }}</span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Tipe & Keparahan -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-xs text-slate-900 mb-1">
                                    {{ $exception->type }}
                                </div>
                                <div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        {{ $exception->severity === 'HIGH' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 
                                          ($exception->severity === 'MEDIUM' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 
                                          'bg-amber-100 text-amber-800 border border-amber-200') }}">
                                        {{ $exception->severity }}
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold tracking-wide uppercase inline-flex items-center space-x-1
                                    {{ $exception->status === 'RESOLVED' ? 'bg-emerald-100 text-emerald-800' : 
                                      ($exception->status === 'INVESTIGATING' ? 'bg-sky-100 text-sky-800' : 
                                      'bg-rose-100 text-rose-800') }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $exception->status === 'RESOLVED' ? 'bg-emerald-500' : ($exception->status === 'INVESTIGATING' ? 'bg-sky-500' : 'bg-rose-500') }}"></span>
                                    <span>{{ $exception->status }}</span>
                                </span>
                            </td>

                            <!-- Dampak Delay -->
                            <td class="py-4 px-5">
                                @if($exception->delay_impact_hours > 0)
                                    <span class="font-mono text-xs font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200/60">
                                        +{{ $exception->delay_impact_hours }} Jam
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 font-mono">0 Jam</span>
                                @endif
                            </td>

                            <!-- Rincian Kendala & Pelapor -->
                            <td class="py-4 px-5 max-w-xs">
                                <p class="text-xs text-slate-700 leading-snug line-clamp-2" title="{{ $exception->description }}">
                                    {{ $exception->description }}
                                </p>
                                @if($exception->resolution_note)
                                    <div class="mt-2 p-2 rounded-lg bg-emerald-50/80 border border-emerald-200/60 text-[11px] text-emerald-900 leading-snug">
                                        <strong class="font-semibold text-emerald-800 block">Resolusi:</strong>
                                        {{ $exception->resolution_note }}
                                    </div>
                                @endif
                                <span class="block text-[10px] text-slate-400 mt-1">
                                    Dilaporkan oleh: <strong class="text-slate-600">{{ $exception->reporter?->name ?? 'Sistem' }}</strong>
                                </span>
                            </td>

                            <!-- Waktu Terdeteksi & Penyelesaian -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="text-xs text-slate-700 font-medium">
                                    {{ $exception->detected_at ? $exception->detected_at->format('d/m/Y H:i') : '-' }}
                                </div>
                                @if($exception->resolved_at)
                                    <div class="text-[10px] text-emerald-600 font-medium mt-1">
                                        Selesai: {{ $exception->resolved_at->format('d/m/Y H:i') }}
                                        <br>
                                        <span class="text-slate-400 font-normal">Oleh: {{ $exception->resolver?->name ?? '-' }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Aksi Update Status & Resolusi -->
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                @if($exception->status === 'RESOLVED')
                                    <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-xl bg-slate-100 text-slate-500 text-xs font-semibold">
                                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Terselesaikan</span>
                                    </span>
                                @else
                                    <!-- Tombol trigger form inline / dialog -->
                                    <details class="relative inline-block text-left">
                                        <summary class="list-none inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-xs font-bold cursor-pointer transition shadow-sm">
                                            <span>Ubah Status</span>
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </summary>
                                        <div class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 p-4 z-50 text-left">
                                            <h4 class="text-xs font-bold text-slate-900 border-b border-slate-100 pb-2 mb-3">
                                                Update Status Kendala #{{ $exception->id }}
                                            </h4>
                                            <form method="POST" action="{{ route('exceptions.update', $exception) }}" class="space-y-3">
                                                @csrf
                                                @method('PATCH')

                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Status Baru</label>
                                                    <select name="status" 
                                                            required
                                                            onchange="toggleResolutionNote(this, 'res-note-{{ $exception->id }}')"
                                                            class="w-full px-2.5 py-1.5 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                                                        @if($exception->status === 'OPEN')
                                                            <option value="INVESTIGATING">INVESTIGATING (Dalam Penanganan)</option>
                                                            <option value="RESOLVED">RESOLVED (Terselesaikan)</option>
                                                        @elseif($exception->status === 'INVESTIGATING')
                                                            <option value="RESOLVED" selected>RESOLVED (Terselesaikan)</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <div id="res-note-{{ $exception->id }}" class="{{ $exception->status === 'OPEN' ? 'hidden' : 'block' }}">
                                                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">
                                                        Catatan Resolusi / Solusi <span class="text-rose-500">*</span>
                                                    </label>
                                                    <textarea name="resolution_note" 
                                                              rows="2" 
                                                              placeholder="Jelaskan langkah penyelesaian kendala..."
                                                              class="w-full px-2.5 py-1.5 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">{{ old('resolution_note') }}</textarea>
                                                </div>

                                                <div class="pt-2 flex justify-end space-x-2">
                                                    <button type="submit" class="w-full px-3 py-2 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-xs font-bold transition shadow">
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
                            <td colspan="7" class="py-12 px-5 text-center text-slate-400 text-sm">
                                Tidak ada kendala operasional yang ditemukan dengan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($exceptions->hasPages())
            <div class="p-4 border-t border-slate-100">
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
