@extends('layouts.app')

@section('title', 'Audit Logs Sistem')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1C1E1B] tracking-tight">
                Jejak Rekam & Audit Logs
            </h1>
            <p class="text-sm text-[#6F756C] mt-1">
                Transparansi penuh terhadap seluruh aksi mutasi data kargo, event pelacakan, penanganan exception, dan histori perubahan sistem.
            </p>
        </div>

        <div class="shrink-0 flex items-center space-x-3 text-xs text-[#6F756C]">
            <span class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-white border border-[#DFE1D8]/80 font-medium shadow-sm">
                <span class="h-2 w-2 rounded-full bg-[#556E5C]"></span>
                <span>Total Rekam: <strong class="text-[#1C1E1B]">{{ $logs->total() }}</strong> Entri</span>
            </span>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-6">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">
                
                <!-- Search input (4 cols) -->
                <div class="lg:col-span-4">
                    <label for="search" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Cari Aksi / ID / IP</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: SHIPMENT, 192.168, dsb"
                               class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-[#DCE0D6] text-sm text-[#1C1E1B] placeholder-[#9EA39B] focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                        <svg class="h-4 w-4 text-[#7C8279] absolute left-3.5 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Aktor (User) (3 cols) -->
                <div class="lg:col-span-3">
                    <label for="user_id" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Aktor (Pengguna)</label>
                    <select name="user_id" id="user_id" class="w-full px-4 py-2.5 rounded-2xl border border-[#DCE0D6] text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                        <option value="">Semua Aktor</option>
                        <option value="system" {{ request('user_id') === 'system' ? 'selected' : '' }}>Sistem Otomatis (None)</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Jenis Aksi (3 cols) -->
                <div class="lg:col-span-3">
                    <label for="action" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Jenis Aksi</label>
                    <select name="action" id="action" class="w-full px-4 py-2.5 rounded-2xl border border-[#DCE0D6] text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                        <option value="">Semua Jenis Aksi</option>
                        @foreach($distinctActions as $act)
                            <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter Buttons (2 cols) -->
                <div class="lg:col-span-2 flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-full bg-[#556E5C] text-white text-sm font-semibold hover:bg-[#425848] transition shadow-sm">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['search', 'user_id', 'action', 'target_type', 'date_from', 'date_to']))
                        <a href="{{ route('audit-logs.index') }}" class="px-4 py-2.5 rounded-full border border-[#DCE0D6] text-[#6F756C] text-sm font-semibold hover:bg-[#F5F6F1] transition" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>

            </div>

            <!-- Row 2: Entitas Terkait & Rentang Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-[#DFE1D8]/60">
                
                <div>
                    <label for="target_type" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Entitas Terkait (Target Type)</label>
                    <select name="target_type" id="target_type" class="w-full px-4 py-2 rounded-2xl border border-[#DCE0D6] text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                        <option value="">Semua Entitas</option>
                        @foreach($distinctTargetTypes as $target)
                            <option value="{{ $target }}" {{ request('target_type') == $target ? 'selected' : '' }}>{{ class_basename($target) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="w-full px-4 py-2 rounded-2xl border border-[#DCE0D6] text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                </div>

                <div>
                    <label for="date_to" class="block text-[11px] font-bold text-[#6F756C] uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="w-full px-4 py-2 rounded-2xl border border-[#DCE0D6] text-xs sm:text-sm text-[#1C1E1B] focus:outline-none focus:border-[#556E5C] focus:ring-2 focus:ring-[#556E5C]/20 transition">
                </div>

            </div>

        </form>
    </div>

    <!-- Audit Logs Table Card -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        <div class="p-6 border-b border-[#DFE1D8]/60 flex items-center justify-between">
            <h2 class="text-base font-bold text-[#1C1E1B]">Daftar Rekam Jejak Audit</h2>
            <span class="text-xs text-[#6F756C]">Menampilkan: <span class="font-semibold text-[#1C1E1B]">{{ $logs->count() }}</span> dari <span class="font-semibold text-[#1C1E1B]">{{ $logs->total() }}</span> total</span>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-sm min-w-[680px]">
                <thead>
                    <tr class="bg-[#F5F6F1] text-[#6F756C] uppercase text-[11px] font-bold tracking-wider border-b border-[#DFE1D8]/70">
                        <th class="py-3.5 px-5">Waktu (WIB)</th>
                        <th class="py-3.5 px-5">Aktor</th>
                        <th class="py-3.5 px-5">Aksi</th>
                        <th class="py-3.5 px-5">Entitas Terkait</th>
                        <th class="py-3.5 px-5">IP Address</th>
                        <th class="py-3.5 px-5">Rincian Perubahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DFE1D8]/50 text-xs">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#F5F6F1]/70 transition align-top">
                            
                            <!-- Waktu (WIB) -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="font-bold text-[#1C1E1B]">
                                    {{ $log->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') }} WIB
                                </div>
                                <span class="text-[11px] text-[#6F756C] block mt-0.5">{{ $log->created_at->diffForHumans() }}</span>
                            </td>

                            <!-- Aktor -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                @if($log->user)
                                    <div class="font-semibold text-[#1C1E1B]">{{ $log->user->name }}</div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                            {{ $log->user->isAdmin() ? 'bg-[#E6F0E9] text-[#2F6846]' : ($log->user->isOperator() ? 'bg-[#F7EFE1] text-[#9A6721]' : 'bg-[#EDF1EC] text-[#556E5C]') }}">
                                            {{ $log->user->role }}
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F2EC] text-[#6F756C]">
                                        Sistem Otomatis
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                @php
                                    $actionBadges = [
                                        'CREATE'              => 'bg-[#E6F0E9] text-[#2F6846]',
                                        'SHIPMENT_CREATED'    => 'bg-[#E6F0E9] text-[#2F6846]',
                                        'UPDATE'              => 'bg-[#EDF1EC] text-[#425848]',
                                        'SHIPMENT_UPDATED'    => 'bg-[#EDF1EC] text-[#425848]',
                                        'STATUS_UPDATE'       => 'bg-[#F7EFE1] text-[#9A6721]',
                                        'STATUS_UPDATED'      => 'bg-[#F7EFE1] text-[#9A6721]',
                                        'TRACKING_ADDED'      => 'bg-[#EAF3FA] text-[#276494]',
                                        'EXCEPTION_CREATED'   => 'bg-[#F9E6E6] text-[#B84242]',
                                        'EXCEPTION_RESOLVED'  => 'bg-[#E6F0E9] text-[#2F6846]',
                                        'DELETE'              => 'bg-[#F9E6E6] text-[#B84242]',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-mono text-[11px] font-bold {{ $actionBadges[$log->action] ?? 'bg-[#F1F2EC] text-[#6F756C]' }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Entitas Terkait -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="font-semibold text-[#1C1E1B]">
                                    {{ class_basename($log->target_type) }}
                                </div>
                                <span class="font-mono text-[11px] text-[#6F756C]">ID: #{{ $log->target_id ?? '-' }}</span>
                            </td>

                            <!-- IP Address -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <span class="font-mono text-[11px] text-[#6F756C] px-2.5 py-0.5 rounded-full bg-[#F1F2EC] border border-[#DFE1D8]">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </span>
                            </td>

                            <!-- Rincian Perubahan (Collapsible) -->
                            <td class="py-4 px-5">
                                @if(empty($log->old_values) && empty($log->new_values))
                                    <span class="text-[#9EA39B] italic text-[11px]">- Tidak ada data perubahan -</span>
                                @else
                                    <details class="group">
                                        <summary class="cursor-pointer font-semibold text-[#556E5C] hover:text-[#425848] text-xs select-none inline-flex items-center space-x-1">
                                            <span>Lihat Nilai Data</span>
                                            <span class="group-open:rotate-180 transition transform inline-block">&darr;</span>
                                        </summary>
                                        <div class="mt-2 space-y-2 max-w-md">
                                            @if(!empty($log->old_values))
                                                <div class="bg-[#F9E6E6]/60 p-3 rounded-2xl border border-[#F9E6E6]">
                                                    <div class="text-[10px] font-bold text-[#B84242] uppercase tracking-wider mb-1">Nilai Lama (Old):</div>
                                                    <pre class="text-[10px] font-mono text-[#1C1E1B] overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </div>
                                            @endif
                                            @if(!empty($log->new_values))
                                                <div class="bg-[#E6F0E9]/60 p-3 rounded-2xl border border-[#E6F0E9]">
                                                    <div class="text-[10px] font-bold text-[#2F6846] uppercase tracking-wider mb-1">Nilai Baru (New):</div>
                                                    <pre class="text-[10px] font-mono text-[#1C1E1B] overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-[#6F756C]">
                                <div class="max-w-sm mx-auto text-center">
                                    <div class="h-12 w-12 rounded-full bg-[#F1F2EC] flex items-center justify-center mx-auto mb-3 text-[#7C8279]">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-[#1C1E1B]">Tidak ada audit log yang sesuai.</p>
                                    <p class="text-xs text-[#6F756C] mt-1">Coba sesuaikan kata kunci pencarian atau rentang tanggal yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-[#DFE1D8]/60">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
