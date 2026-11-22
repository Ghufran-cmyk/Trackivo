@extends('layouts.app')

@section('title', 'Audit Logs Sistem')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-purple-500/10 text-purple-700 border border-purple-200 text-xs font-semibold mb-2">
                <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                <span>Khusus Administrator</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Jejak Rekam & Audit Logs
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Transparansi penuh terhadap seluruh aksi mutasi data kargo, event pelacakan, penanganan exception, dan histori perubahan sistem.
            </p>
        </div>

        <div class="shrink-0 flex items-center space-x-3 text-xs text-slate-500">
            <span class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 font-medium">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>Total Rekam: <strong class="text-slate-800">{{ $logs->total() }}</strong> Entri</span>
            </span>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
                
                <!-- Search input (4 cols) -->
                <div class="lg:col-span-4">
                    <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cari Aksi / ID / IP</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: SHIPMENT, 192.168, dsb"
                               class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <svg class="h-4 w-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Aktor (User) (3 cols) -->
                <div class="lg:col-span-3">
                    <label for="user_id" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Aktor (Pengguna)</label>
                    <select name="user_id" id="user_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Aktor</option>
                        <option value="system" {{ request('user_id') === 'system' ? 'selected' : '' }}>Sistem Otomatis (None)</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Jenis Aksi (3 cols) -->
                <div class="lg:col-span-3">
                    <label for="action" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Aksi</label>
                    <select name="action" id="action" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Jenis Aksi</option>
                        @foreach($distinctActions as $act)
                            <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter Buttons (2 cols) -->
                <div class="lg:col-span-2 flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-slate-900 text-[#d4f93a] text-sm font-semibold hover:bg-slate-800 transition shadow-sm">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['search', 'user_id', 'action', 'target_type', 'date_from', 'date_to']))
                        <a href="{{ route('audit-logs.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>

            </div>

            <!-- Row 2: Entitas Terkait & Rentang Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-3 border-t border-slate-100">
                
                <div>
                    <label for="target_type" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Entitas Terkait (Target Type)</label>
                    <select name="target_type" id="target_type" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                        <option value="">Semua Entitas</option>
                        @foreach($distinctTargetTypes as $target)
                            <option value="{{ $target }}" {{ request('target_type') == $target ? 'selected' : '' }}>{{ class_basename($target) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                </div>

                <div>
                    <label for="date_to" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#d4f93a]">
                </div>

            </div>

        </form>
    </div>

    <!-- Audit Logs Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Daftar Rekam Jejak Audit</h2>
            <span class="text-xs text-slate-500">Menampilkan: <span class="font-semibold text-slate-700">{{ $logs->count() }}</span> dari <span class="font-semibold text-slate-700">{{ $logs->total() }}</span> total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-4 sm:px-5">Waktu (WIB)</th>
                        <th class="py-3.5 px-4 sm:px-5">Aktor</th>
                        <th class="py-3.5 px-4 sm:px-5">Aksi</th>
                        <th class="py-3.5 px-4 sm:px-5">Entitas Terkait</th>
                        <th class="py-3.5 px-4 sm:px-5">IP Address</th>
                        <th class="py-3.5 px-4 sm:px-5">Rincian Perubahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/60 transition align-top">
                            
                            <!-- Waktu (WIB) -->
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                <div class="font-bold text-slate-800">
                                    {{ $log->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') }} WIB
                                </div>
                                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $log->created_at->diffForHumans() }}</span>
                            </td>

                            <!-- Aktor -->
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                @if($log->user)
                                    <div class="font-semibold text-slate-900">{{ $log->user->name }}</div>
                                    <div class="mt-0.5">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $log->user->isAdmin() ? 'bg-purple-100 text-purple-700' : ($log->user->isOperator() ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                            {{ $log->user->role }}
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                        Sistem Otomatis
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                @php
                                    $actionBadges = [
                                        'CREATE'              => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'SHIPMENT_CREATED'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'UPDATE'              => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'SHIPMENT_UPDATED'    => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'STATUS_UPDATE'       => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'STATUS_UPDATED'      => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'TRACKING_ADDED'      => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        'EXCEPTION_CREATED'   => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'EXCEPTION_RESOLVED'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'DELETE'              => 'bg-red-100 text-red-800 border-red-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono text-[11px] font-bold border {{ $actionBadges[$log->action] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Entitas Terkait -->
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">
                                    {{ class_basename($log->target_type) }}
                                </div>
                                <span class="font-mono text-[11px] text-slate-500">ID: #{{ $log->target_id ?? '-' }}</span>
                            </td>

                            <!-- IP Address -->
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                <span class="font-mono text-[11px] text-slate-600 px-2 py-0.5 rounded bg-slate-100 border border-slate-200">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </span>
                            </td>

                            <!-- Rincian Perubahan (Collapsible) -->
                            <td class="py-3.5 px-4 sm:px-5">
                                @if(empty($log->old_values) && empty($log->new_values))
                                    <span class="text-slate-400 italic text-[11px]">- Tidak ada data perubahan -</span>
                                @else
                                    <details class="group">
                                        <summary class="cursor-pointer font-semibold text-blue-600 hover:text-blue-800 text-xs select-none inline-flex items-center space-x-1">
                                            <span>Lihat Nilai Data</span>
                                            <span class="group-open:rotate-180 transition transform inline-block">&darr;</span>
                                        </summary>
                                        <div class="mt-2 space-y-2 max-w-md">
                                            @if(!empty($log->old_values))
                                                <div class="bg-rose-50/50 p-2.5 rounded-xl border border-rose-200/80">
                                                    <div class="text-[10px] font-bold text-rose-700 uppercase tracking-wider mb-1">Nilai Lama (Old):</div>
                                                    <pre class="text-[10px] font-mono text-slate-800 overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </div>
                                            @endif
                                            @if(!empty($log->new_values))
                                                <div class="bg-emerald-50/50 p-2.5 rounded-xl border border-emerald-200/80">
                                                    <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-1">Nilai Baru (New):</div>
                                                    <pre class="text-[10px] font-mono text-slate-800 overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto text-center">
                                    <svg class="h-10 w-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-semibold text-slate-600">Tidak ada audit log yang sesuai.</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau rentang tanggal yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
