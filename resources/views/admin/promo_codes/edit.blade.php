@extends('layouts.admin')

@section('page_title', 'Edit Kode Promo')
@section('page_subtitle', 'Pembaruan data kupon diskon')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold">Form Edit Kode Promo</h2>
        <a href="{{ route('admin.promo-codes.index') }}" class="text-slate-500 hover:text-slate-700 font-bold text-sm">
            &larr; Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.promo-codes.update', $promoCode->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Promo</label>
            <input type="text" name="code" value="{{ old('code', $promoCode->code) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 uppercase font-bold" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Tipe Diskon</label>
                <select name="type" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium">
                    <option value="fixed" {{ old('type', $promoCode->type) === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                    <option value="percentage" {{ old('type', $promoCode->type) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Besaran Diskon</label>
                <input type="number" name="discount_amount" value="{{ old('discount_amount', $promoCode->discount_amount) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium" required min="0" step="any">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Min. Transaksi (Rp)</label>
                <input type="number" name="min_transaction" value="{{ old('min_transaction', $promoCode->min_transaction) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium" min="0">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Maksimal Diskon (Rp - Opsional)</label>
                <input type="number" name="max_discount" value="{{ old('max_discount', $promoCode->max_discount) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium" min="0">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Batas Kuota</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $promoCode->usage_limit) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium" required min="1">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Status</label>
                <select name="is_active" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium">
                    <option value="1" {{ old('is_active', $promoCode->is_active) ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !old('is_active', $promoCode->is_active) ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Tanggal Kedaluwarsa</label>
                <input type="datetime-local" name="expired_at" value="{{ old('expired_at', $promoCode->expired_at?->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition">
                Perbarui Kode Promo
            </button>
        </div>
    </form>
</div>
@endsection
