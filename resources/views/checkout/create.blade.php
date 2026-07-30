@extends('layouts.app')
@section('title', 'Checkout - ' . $event->title)
@section('content')
<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="mb-12">
        <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 font-bold flex items-center gap-2 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Event
        </a>
        <h1 class="text-4xl font-extrabold">Checkout Tiket</h1>
        <p class="text-slate-500 mt-2">Lengkapi nomor kontak Anda untuk menyelesaikan pemesanan.</p>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        <!-- Summary Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 border-b pb-4">Pesanan Anda</h3>
            <div class="flex gap-6 items-start">
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                 ? asset('storage/' . $event->poster_path)
                 : 'https://placehold.co/200x200' }}"
                    alt="Event" class="w-24 h-24 rounded-2xl object-cover">
                <div>
                    <h4 class="font-extrabold text-lg">{{ $event->title }}</h4>
                    <p class="text-slate-500">{{ $event->date->format('d M Y') }} • {{ $event->location }}</p>
                    <p class="text-indigo-600 font-bold mt-2">
                        1 x {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t space-y-3">
                <div class="flex justify-between text-slate-500">
                    <span>Harga Tiket</span>
                    <span>{{ $event->price == 0 ? 'Rp 0' : 'Rp ' . number_format($event->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Biaya Admin</span>
                    <span>{{ $event->price == 0 ? 'Rp 0' : 'Rp 5.000' }}</span>
                </div>
                <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t">
                    <span>Total Bayar</span>
                    <span class="{{ $event->price == 0 ? 'text-emerald-600' : 'text-indigo-600' }}">
                        {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price + 5000, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold">
                    ✓
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Data Pemesan (Akun Google)</h3>
                    <p class="text-xs text-slate-400">Terautentikasi sebagai <strong>{{ auth()->user()->email }}</strong></p>
                </div>
            </div>

            <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Pemesan</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}"
                        class="w-full px-5 py-4 bg-slate-100 border-2 border-slate-200 rounded-2xl outline-none font-bold text-slate-700 cursor-not-allowed"
                        required readonly>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-bold uppercase tracking-wider">*Terisi otomatis dari Akun Google Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email Pemesan</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}"
                            class="w-full px-5 py-4 bg-slate-100 border-2 border-slate-200 rounded-2xl outline-none font-bold text-slate-700 cursor-not-allowed"
                            required readonly>
                        <p class="text-[10px] text-slate-400 mt-1.5 font-bold uppercase tracking-wider">*E-Ticket akan dikirim ke email ini</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">No. WhatsApp / HP</label>
                        <input type="tel" name="customer_phone" placeholder="08xxxxxxxxxx"
                            class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                            required value="{{ old('customer_phone') }}">
                    </div>
                </div>

                @if($event->price > 0)
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kode Promo / Voucher (Opsional)</label>
                        <input type="text" name="promo_code" placeholder="Masukkan kode promo jika ada (cth: DISKON50)"
                            class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-bold tracking-wider uppercase"
                            value="{{ old('promo_code') }}">
                    </div>
                @endif

                <button type="submit"
                    class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                    {{ $event->price == 0 ? 'Dapatkan Tiket Gratis Sekarang' : 'Lanjut Pembayaran' }}
                </button>
                <p class="text-center text-xs text-slate-400">Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.</p>
            </form>
        </div>

    </div>
</main>
@endsection