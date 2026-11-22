@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-slate-900 text-[#d4f93a] text-xs font-semibold mb-2">
                <span class="h-1.5 w-1.5 rounded-full bg-[#d4f93a]"></span>
                <span>Master Data Khusus Administrator</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Pengguna (Users)</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola akun dan hak akses Administrator, Operator Operasional, dan Pelanggan (Customer).</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('users.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-[#0e1117] hover:bg-slate-800 text-[#d4f93a] text-sm font-semibold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Pengguna</span>
            </a>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Daftar Akun Pengguna</h2>
            <span class="text-xs text-slate-500">Total: <span class="font-semibold text-slate-700">{{ $users->total() }}</span> pengguna</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-5">Nama & Avatar</th>
                        <th class="py-3.5 px-5">Email</th>
                        <th class="py-3.5 px-5">Peran (Role)</th>
                        <th class="py-3.5 px-5">Perusahaan</th>
                        <th class="py-3.5 px-5">No. Telepon</th>
                        <th class="py-3.5 px-5 text-center">Shipments</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition {{ $user->id === auth()->id() ? 'bg-amber-50/20' : '' }}">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center space-x-3">
                                    <div class="h-9 w-9 rounded-full bg-slate-900 text-[#d4f93a] font-bold flex items-center justify-center text-xs shrink-0 border border-slate-800">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-slate-900 block leading-tight">{{ $user->name }}</span>
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] text-amber-600 font-bold uppercase tracking-wider">(Akun Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 text-slate-600 font-mono text-xs">
                                {{ $user->email }}
                            </td>
                            <td class="py-3.5 px-5">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        ADMIN
                                    </span>
                                @elseif($user->role === 'operator')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        OPERATOR
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        CUSTOMER
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-slate-700 font-medium">
                                {{ $user->company_name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-slate-600 text-xs">
                                {{ $user->phone ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $user->shipments_count > 0 ? 'bg-slate-100 text-slate-800' : 'text-slate-400' }}">
                                    {{ $user->shipments_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('users.edit', $user) }}" class="p-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition" title="Edit Pengguna">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" title="Hapus Pengguna">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-1.5 text-slate-300 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
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
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
