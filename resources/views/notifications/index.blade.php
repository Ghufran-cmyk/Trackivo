@extends('layouts.app')

@section('title', 'Notifikasi Saya')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#E6F0E9] text-[#2F6846] text-xs font-semibold mb-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#2F6846]"></span>
                <span>Pusat Notifikasi Logistik</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1C1E1B] tracking-tight">
                Notifikasi Pengiriman
            </h1>
            <p class="text-sm text-[#6F756C] mt-1">
                Pembaruan status operasional, laporan anomali, checkpoint rute, dan mitigasi kendala kargo Anda.
            </p>
        </div>

        @if($unreadCount > 0)
            <div class="shrink-0">
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs sm:text-sm font-semibold shadow-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Tandai Semua Sudah Dibaca ({{ $unreadCount }})</span>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Filter Tabs (Semua, Belum Dibaca, Sudah Dibaca) -->
    <div class="flex items-center space-x-2 border-b border-[#DFE1D8]/60 pb-3">
        <a href="{{ route('notifications.index') }}" 
           class="px-4 py-2 rounded-full text-xs sm:text-sm font-semibold transition {{ !request('filter') ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#6F756C] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
            Semua ({{ $totalCount }})
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
           class="px-4 py-2 rounded-full text-xs sm:text-sm font-semibold transition flex items-center space-x-1.5 {{ request('filter') === 'unread' ? 'bg-[#556E5C] text-white shadow-sm' : 'text-[#6F756C] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
            <span>Belum Dibaca</span>
            @if($unreadCount > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ request('filter') === 'unread' ? 'bg-white text-[#556E5C]' : 'bg-[#B84242] text-white' }}">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
           class="px-4 py-2 rounded-full text-xs sm:text-sm font-semibold transition {{ request('filter') === 'read' ? 'bg-[#1C1E1B] text-white shadow-sm' : 'text-[#6F756C] hover:text-[#1C1E1B] hover:bg-[#F1F2EC]' }}">
            Sudah Dibaca
        </a>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="rounded-[24px] p-5 border transition {{ $notification->is_read ? 'bg-white border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)]' : 'bg-gradient-to-r from-[#F7EFE1]/70 to-white border-[#DFE1D8] shadow-md ring-1 ring-[#9A6721]/20' }}">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    
                    <!-- Left: Icon, Title & Message -->
                    <div class="flex items-start space-x-4">
                        <div class="shrink-0 mt-0.5">
                            @if(! $notification->is_read)
                                <span class="h-10 w-10 rounded-full bg-[#F7EFE1] text-[#9A6721] flex items-center justify-center font-bold">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </span>
                            @else
                                <span class="h-10 w-10 rounded-full bg-[#F1F2EC] text-[#7C8279] flex items-center justify-center">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            @endif
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <h3 class="text-sm sm:text-base font-bold text-[#1C1E1B]">
                                    {{ $notification->title }}
                                </h3>
                                @if(! $notification->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F7EFE1] text-[#9A6721] uppercase tracking-wider">
                                        Belum Dibaca
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#F1F2EC] text-[#6F756C]">
                                        Sudah Dibaca
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs sm:text-sm text-[#6F756C] leading-relaxed max-w-2xl">
                                {{ $notification->message }}
                            </p>

                            <!-- Shipment Quick Link -->
                            @if($notification->shipment)
                                <div class="mt-3">
                                    <a href="{{ route('shipments.show', $notification->shipment) }}" class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#F1F2EC] hover:bg-[#E9EAE4] text-[#1C1E1B] text-xs font-semibold transition group">
                                        <svg class="h-3.5 w-3.5 text-[#556E5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span>Lihat Rincian Resi: <strong class="font-mono text-[#556E5C]">{{ $notification->shipment->tracking_number }}</strong></span>
                                        <span class="group-hover:translate-x-0.5 transition">&rarr;</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Timestamps & Mark As Read Action -->
                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-2 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-[#DFE1D8]/50">
                        <div class="text-right">
                            <div class="text-xs font-semibold text-[#1C1E1B]">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                            <div class="text-[11px] text-[#6F756C]">
                                {{ $notification->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                            </div>
                        </div>

                        @if(! $notification->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-1.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs font-semibold shadow-sm transition">
                                    Tandai Dibaca
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] p-12 text-center text-[#6F756C]">
                <div class="h-12 w-12 rounded-full bg-[#F1F2EC] flex items-center justify-center mx-auto mb-3 text-[#7C8279]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#1C1E1B]">Tidak Ada Notifikasi</h3>
                <p class="text-xs text-[#6F756C] mt-1">
                    {{ request('filter') === 'unread' ? 'Seluruh notifikasi telah Anda baca.' : 'Belum ada rekaman notifikasi kargo untuk akun Anda.' }}
                </p>
                @if(request('filter'))
                    <a href="{{ route('notifications.index') }}" class="inline-block mt-4 text-xs font-semibold text-[#556E5C] hover:underline">
                        Lihat Seluruh Notifikasi &rarr;
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
@endsection
