@extends('layouts.app')

@section('title', 'Manajemen Carriers')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1C1E1B] tracking-tight">Manajemen Carriers</h1>
            <p class="text-xs sm:text-sm text-[#6F756C] mt-1">Kelola data penyedia layanan kargo global (laut, udara, dan darat) yang bermitra dengan Trackivo.</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('carriers.create') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs sm:text-sm font-semibold shadow-xs transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Carrier</span>
            </a>
        </div>
    </div>

    <!-- Carriers Table Card -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        <div class="p-6 border-b border-[#DFE1D8]/60 flex items-center justify-between">
            <h2 class="text-base font-bold text-[#1C1E1B]">Daftar Carrier Terdaftar</h2>
            <span class="text-xs text-[#6F756C]">Total: <span class="font-bold text-[#1C1E1B]">{{ $carriers->total() }}</span> carrier</span>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-sm min-w-[680px]">
                <thead>
                    <tr class="bg-[#FAF9F5] text-[#6F756C] uppercase text-[10.5px] font-bold tracking-wider border-b border-[#DFE1D8]/60">
                        <th class="py-3.5 px-5">Kode</th>
                        <th class="py-3.5 px-5">Nama Carrier</th>
                        <th class="py-3.5 px-5">Email Kontak</th>
                        <th class="py-3.5 px-5">No. Telepon</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-center">Shipments Terkait</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DFE1D8]/40">
                    @forelse($carriers as $carrier)
                        <tr class="hover:bg-[#F5F6F1] transition text-xs sm:text-[13px]">
                            <td class="py-3.5 px-5 font-mono font-bold text-[#1C1E1B]">
                                <span class="px-2.5 py-1 rounded-md bg-[#F5F6F1] border border-[#DFE1D8] text-xs">{{ $carrier->code }}</span>
                            </td>
                            <td class="py-3.5 px-5 font-semibold text-[#1C1E1B]">
                                {{ $carrier->name }}
                            </td>
                            <td class="py-3.5 px-5 text-[#6F756C]">
                                {{ $carrier->contact_email ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-[#6F756C]">
                                {{ $carrier->contact_phone ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5">
                                @if($carrier->is_active)
                                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#E6F0E9] text-[#2F6846] border border-[#C8DACD]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#2F6846]"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#F1F2EC] text-[#6F756C] border border-[#DFE1D8]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#9EABA2]"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $carrier->shipments_count > 0 ? 'bg-[#EDF1EC] text-[#556E5C] border border-[#DFE1D8]' : 'bg-[#F5F6F1] text-[#9EABA2] border border-[#DFE1D8]' }}">
                                    {{ $carrier->shipments_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('carriers.edit', $carrier) }}" class="p-1.5 rounded-full text-[#6F756C] hover:text-[#1C1E1B] hover:bg-[#F1F2EC] transition" title="Edit Carrier">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('carriers.destroy', $carrier) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus carrier {{ $carrier->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-full text-[#B84242] hover:bg-[#F9E6E6] transition" title="Hapus Carrier">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#6F756C]">
                                Belum ada carrier yang ditambahkan ke sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($carriers->hasPages())
            <div class="p-5 border-t border-[#DFE1D8]/60">
                {{ $carriers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
