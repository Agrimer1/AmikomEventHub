@extends('layouts.admin')

@section('page_title', 'Kelola Pengurus')
@section('page_subtitle', 'Daftar semua staff pengurus event.')

@section('content')
    <div class="bg-white rounded-[2rem] shadow-sm border overflow-hidden">
        <div class="p-8 border-b flex justify-between items-center bg-white/50 backdrop-blur">
            <h3 class="text-xl font-bold">Daftar Pengurus</h3>
            <a href="{{ route('admin.pengurus.create') }}" 
                class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Pengurus
            </a>
        </div>
        <div class="px-8 py-4 border-b bg-slate-50/50 flex flex-wrap gap-4 items-center justify-between">
            <form action="{{ route('admin.pengurus.index') }}" method="GET" class="flex gap-2 w-full md:max-w-md">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama atau deskripsi..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition outline-none text-sm">
                    <div class="absolute left-3.5 top-3 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.pengurus.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-sm transition text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-widest">
                        <th class="px-8 py-4">ID</th>
                        <th class="px-8 py-4">Nama Pengurus</th>
                        <th class="px-8 py-4">Jabatan</th>
                        <th class="px-8 py-4">Deskripsi</th>
                        <th class="px-8 py-4">Gaji</th>
                        <th class="px-8 py-4">Dibuat Oleh</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($penguruses as $pengurus)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-6">
                                <span class="text-slate-600 font-medium">{{ $pengurus->id }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="font-bold text-slate-900">{{ $pengurus->name }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold uppercase">
                                    {{ $pengurus->jabatan->name ?? 'Tidak Ada Jabatan' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-slate-600 max-w-xs truncate text-sm">
                                {{ $pengurus->description }}
                            </td>
                            <td class="px-8 py-6 text-slate-900 font-semibold font-mono text-sm">
                                Rp {{ number_format($pengurus->salary, 2, ',', '.') }}
                            </td>
                            <td class="px-8 py-6 text-slate-600 text-sm">
                                {{ $pengurus->created_by ?? '-' }}
                            </td>
                            <td class="px-8 py-6 text-slate-900 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.pengurus.edit', $pengurus->id) }}" 
                                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.pengurus.destroy', $pengurus->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengurus ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-20 text-center text-slate-500">Belum ada data pengurus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penguruses->hasPages())
            <div class="px-8 py-4 border-t bg-slate-50/50">
                {{ $penguruses->links() }}
            </div>
        @endif
    </div>
@endsection
