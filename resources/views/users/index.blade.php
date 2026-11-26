@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1C1E1B] tracking-tight">Manajemen Pengguna (Users)</h1>
            <p class="text-xs sm:text-sm text-[#6F756C] mt-1">Kelola akun dan hak akses Administrator, Operator Operasional, dan Pelanggan (Customer).</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('users.create') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-full bg-[#556E5C] hover:bg-[#425848] text-white text-xs sm:text-sm font-semibold shadow-xs transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Pengguna</span>
            </a>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-[24px] border border-[#DFE1D8]/80 shadow-[0_4px_20px_rgba(28,30,27,0.04)] overflow-hidden">
        <div class="p-6 border-b border-[#DFE1D8]/60 flex items-center justify-between">
            <h2 class="text-base font-bold text-[#1C1E1B]">Daftar Akun Pengguna</h2>
            <span class="text-xs text-[#6F756C]">Total: <span class="font-bold text-[#1C1E1B]">{{ $users->total() }}</span> pengguna</span>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-sm min-w-[680px]">
                <thead>
                    <tr class="bg-[#FAF9F5] text-[#6F756C] uppercase text-[10.5px] font-bold tracking-wider border-b border-[#DFE1D8]/60">
                        <th class="py-3.5 px-5">Nama & Avatar</th>
                        <th class="py-3.5 px-5">Email</th>
                        <th class="py-3.5 px-5 text-center">Peran (Role)</th>
                        <th class="py-3.5 px-5">Perusahaan</th>
                        <th class="py-3.5 px-5">No. Telepon</th>
                        <th class="py-3.5 px-5 text-center">Shipments</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DFE1D8]/40">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#F5F6F1] transition text-xs sm:text-[13px] {{ $user->id === auth()->id() ? 'bg-[#FAF9F5]/80' : '' }}">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center space-x-3">
                                    <div class="h-9 w-9 rounded-full bg-[#556E5C] text-white font-bold flex items-center justify-center text-xs shrink-0 ring-2 ring-[#DFE1D8]">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-[#1C1E1B] block leading-tight">{{ $user->name }}</span>
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] text-[#556E5C] font-bold uppercase tracking-wider">(Akun Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 text-[#6F756C] font-mono text-xs">
                                {{ $user->email }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center justify-center min-w-[92px] px-3 py-0.5 rounded-full text-xs font-bold bg-[#D6E0D2] text-[#1E2D23] border border-[#B8C7B2]">
                                        ADMIN
                                    </span>
                                @elseif($user->role === 'operator')
                                    <span class="inline-flex items-center justify-center min-w-[92px] px-3 py-0.5 rounded-full text-xs font-bold bg-[#F7EFE1] text-[#9A6721] border border-[#E6D8BF]">
                                        OPERATOR
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center min-w-[92px] px-3 py-0.5 rounded-full text-xs font-bold bg-[#E6F0E9] text-[#2F6846] border border-[#C8DACD]">
                                        CUSTOMER
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-[#1C1E1B] font-medium">
                                {{ $user->company_name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-[#6F756C] text-xs">
                                {{ $user->phone ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $user->shipments_count > 0 ? 'bg-[#F5F6F1] text-[#1C1E1B] border border-[#DFE1D8]' : 'text-[#9EABA2]' }}">
                                    {{ $user->shipments_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('users.edit', $user) }}" class="p-1.5 rounded-full text-[#6F756C] hover:text-[#1C1E1B] hover:bg-[#F1F2EC] transition" title="Edit Pengguna">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-full text-[#B84242] hover:bg-[#F9E6E6] transition" title="Hapus Pengguna">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-1.5 text-[#C9CCC0] cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#6F756C]">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-5 border-t border-[#DFE1D8]/60">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
