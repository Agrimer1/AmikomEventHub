@extends('layouts.admin')

@section('page_title', 'Kelola Kode Promo')
@section('page_subtitle', 'Daftar kode voucher diskon untuk tiket event')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold">Daftar Kode Promo</h2>
        <p class="text-sm text-slate-500">Kelola kupon diskon dan batas penggunaannya.</p>
    </div>
    <a href="{{ route('admin.promo-codes.create') }}" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition">
        + Tambah Kode Promo
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <th class="p-4">Kode Promo</th>
                    <th class="p-4">Tipe & Besaran Diskon</th>
                    <th class="p-4">Min Transaksi</th>
                    <th class="p-4">Kuota Penggunaan</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Kedaluwarsa</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium">
                @forelse($promoCodes as $promo)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 font-black text-indigo-900 tracking-wider">
                        <span class="px-3 py-1 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-lg">
                            {{ $promo->code }}
                        </span>
                    </td>
                    <td class="p-4">
                        @if($promo->type === 'percentage')
                            <span class="font-bold text-slate-800">{{ number_format($promo->discount_amount, 0) }}%</span>
                            @if($promo->max_discount)
                                <span class="block text-xs text-slate-400">Max: Rp {{ number_format($promo->max_discount, 0, ',', '.') }}</span>
                            @endif
                        @else
                            <span class="font-bold text-slate-800">Rp {{ number_format($promo->discount_amount, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td class="p-4 text-slate-600">
                        Rp {{ number_format($promo->min_transaction, 0, ',', '.') }}
                    </td>
                    <td class="p-4">
                        <span class="font-bold text-slate-700">{{ $promo->used_count }}</span> / <span class="text-slate-400">{{ $promo->usage_limit }}</span>
                    </td>
                    <td class="p-4">
                        @if($promo->is_active && (!$promo->expired_at || $promo->expired_at->isFuture()) && $promo->used_count < $promo->usage_limit)
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Aktif</span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 text-xs font-bold rounded-full">Non-Aktif / Habis</span>
                        @endif
                    </td>
                    <td class="p-4 text-slate-500">
                        {{ $promo->expired_at ? $promo->expired_at->format('d M Y H:i') : 'Tanpa Batas' }}
                    </td>
                    <td class="p-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.promo-codes.edit', $promo->id) }}" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition font-bold text-xs">
                                Edit
                            </a>
                            <form action="{{ route('admin.promo-codes.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kode promo ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition font-bold text-xs">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-slate-400">
                        Belum ada kode promo. Klik tombol <strong>+ Tambah Kode Promo</strong> di atas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-slate-100">
        {{ $promoCodes->links() }}
    </div>
</div>
@endsection
