@extends('layouts.admin')

@section('page_title', 'Kelola Tenant Organizer')
@section('page_subtitle', 'Daftar entitas penyelenggara event dan akun loginnya')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold">Daftar Organizer</h2>
        <p class="text-sm text-slate-500">Kelola akun dan profil penyelenggara event multi-tenant.</p>
    </div>
    <a href="{{ route('admin.organizers.create') }}" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition">
        + Tambah Organizer Baru
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <th class="p-4">Nama Organizer</th>
                    <th class="p-4">Email Login</th>
                    <th class="p-4">No. HP</th>
                    <th class="p-4">Jumlah Event</th>
                    <th class="p-4">Tanggal Dibuat</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium">
                @forelse($organizers as $org)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 flex items-center gap-3">
                        @if($org->logo_path)
                            <img src="{{ asset('storage/' . $org->logo_path) }}" alt="{{ $org->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200">
                        @else
                            <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center font-black">
                                {{ strtoupper(substr($org->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-slate-800">{{ $org->name }}</p>
                            <p class="text-xs text-slate-400">Slug: {{ $org->slug }}</p>
                        </div>
                    </td>
                    <td class="p-4 text-slate-600 font-medium">
                        {{ $org->user?->email ?? $org->email ?? '-' }}
                    </td>
                    <td class="p-4 text-slate-600">
                        {{ $org->phone ?? '-' }}
                    </td>
                    <td class="p-4">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-bold rounded-lg text-xs">
                            {{ $org->events->count() }} Event
                        </span>
                    </td>
                    <td class="p-4 text-slate-500 text-xs">
                        {{ $org->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="p-4 text-center">
                        <form action="{{ route('admin.organizers.destroy', $org->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Organizer ini beserta akun loginnya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition font-bold text-xs">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">
                        Belum ada data organizer. Klik <strong>+ Tambah Organizer Baru</strong> untuk mendaftarkan tenant.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-slate-100">
        {{ $organizers->links() }}
    </div>
</div>
@endsection
