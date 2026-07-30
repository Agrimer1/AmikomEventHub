@extends('layouts.app')
@section('title', $transaction->total_price == 0 ? 'Pendaftaran Berhasil' : 'Pembayaran Berhasil')
@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full max-w-md">
        <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        @if($transaction->total_price == 0)
            <h2 class="text-3xl font-black mb-2 text-slate-800">Pendaftaran Berhasil!</h2>
            <p class="text-indigo-600 font-bold text-sm mb-4">Tiket gratis Anda berhasil dibuat.</p>
            <p class="text-slate-500 mb-8 text-sm leading-relaxed">
                Pendaftaran untuk event <strong>{{ $transaction->event->title ?? 'Acara' }}</strong> (Order ID: <span class="font-mono text-slate-700 font-bold">{{ $transaction->order_id }}</span>) telah terkonfirmasi. E-Ticket telah diterbitkan dan dikirim ke email Anda (<strong>{{ $transaction->customer_email }}</strong>).
            </p>
        @else
            <h2 class="text-3xl font-black mb-2 text-slate-800">Pembayaran Berhasil!</h2>
            <p class="text-indigo-600 font-bold text-sm mb-4">E-Ticket Anda telah diterbitkan.</p>
            <p class="text-slate-500 mb-8 text-sm leading-relaxed">
                Pembayaran untuk pesanan <span class="font-mono text-slate-700 font-bold">{{ $transaction->order_id }}</span> telah berhasil dikonfirmasi. E-Ticket telah dikirim ke email Anda (<strong>{{ $transaction->customer_email }}</strong>).
            </p>
        @endif

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('ticket', ['order_id' => $transaction->order_id]) }}" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition text-sm">
                Lihat E-Ticket Saya &rarr;
            </a>
            <a href="{{ route('home') }}" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-sm">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</main>
@endsection