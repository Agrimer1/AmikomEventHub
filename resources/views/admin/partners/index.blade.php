@extends('layouts.admin')

@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Daftar semua partner / sponsor yang bekerjasama.')

@section('content')
    <div class="bg-white rounded-[2rem] shadow-sm border overflow-hidden">
        <div class="p-8 border-b flex justify-between items-center bg-white/50 backdrop-blur">
            <h3 class="text-xl font-bold">Daftar Partner</h3>
            <a href="{{ route('admin.partners.create') }}" 
                class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Partner
            </a>
        </div>
        <div class="px-8 py-4 border-b bg-slate-50/50 flex flex-wrap gap-4 items-center justify-between">
            <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-2 w-full md:max-w-md">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama partner..." 
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
                    <a href="{{ route('admin.partners.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-sm transition text-center">
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
                        <th class="px-8 py-4">Logo</th>
                        <th class="px-8 py-4">Nama Partner</th>
                        <th class="px-8 py-4">Created At</th>
                        <th class="px-8 py-4">Updated At</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($partners as $partner)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-6">
                                <span class="text-slate-600 font-medium">{{ $partner->id }}</span>
                            </td>
                            <td class="px-8 py-6">
                                @if($partner->logo_url)
                                    <img src="{{ asset('storage/' . $partner->logo_url) }}" alt="{{ $partner->name }}" class="h-10 w-auto object-contain rounded-lg border bg-slate-50 p-1">
                                @else
                                    <span class="text-slate-400 italic text-sm">Tidak ada logo</span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <span class="font-bold text-lg text-slate-900">{{ $partner->name }}</span>
                            </td>
                            <td class="px-8 py-6 text-slate-500 text-sm">
                                {{ $partner->created_at ? $partner->created_at->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td class="px-8 py-6 text-slate-500 text-sm">
                                {{ $partner->updated_at ? $partner->updated_at->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td class="px-8 py-6 text-slate-900 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.partners.edit', $partner->id) }}" 
                                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus partner ini?')">
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
                            <td colspan="6" class="px-8 py-20 text-center text-slate-500">Belum ada partner.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($partners->hasPages())
            <div class="p-8 border-t bg-slate-50">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
@endsection
