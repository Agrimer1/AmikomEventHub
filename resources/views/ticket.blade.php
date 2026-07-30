<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-indigo-600 text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full my-10">
        <!-- Success Banner -->
        <div class="text-center mb-8">
            <div
                class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black">Pembayaran Berhasil!</h1>
            <p class="text-indigo-100 mt-2">Tiket Anda telah terbit dan siap digunakan.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-2xl mb-6 font-bold text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-2xl mb-6 font-bold text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <!-- Ticket Card -->
        <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
            <!-- Ticket Header -->
            <div class="p-8 bg-indigo-50 border-b-4 border-dashed border-indigo-100 text-center relative">
                <p class="text-indigo-600 font-bold uppercase tracking-widest text-xs mb-2">E-Ticket Resmi</p>
                <h2 class="text-2xl font-black leading-tight">{{ $transaction ? $transaction->event->title : 'Jazz Night 2024: A Celebration' }}</h2>

                <!-- Ticket Side Cuts -->
                <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
                <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
            </div>

            <!-- Ticket Body -->
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Nama Pembeli</p>
                        <p class="font-bold text-lg">{{ $transaction ? $transaction->customer_name : 'Donni Prabowo' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Tanggal & Waktu</p>
                        <p class="font-bold text-lg">{{ $transaction && $transaction->event->date ? $transaction->event->date->format('d M, H:i') : '16 Nov, 19:30' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Order ID</p>
                        <p class="font-bold">{{ $transaction ? $transaction->order_id : 'TRX-99210' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Lokasi</p>
                        <p class="font-bold">{{ $transaction ? $transaction->event->location : 'Blue Note Lounge' }}</p>
                    </div>
                </div>

                <div class="bg-slate-100 p-6 rounded-3xl flex flex-col items-center">
                    <p class="text-slate-400 text-xs font-bold uppercase mb-4">Scan QR untuk Check-in</p>
                    <!-- Mock QR Code -->
                    <div class="w-48 h-48 bg-white p-4 rounded-xl shadow-inner flex items-center justify-center">
                        <div class="w-full h-full border-4 border-slate-900 flex flex-wrap p-1">
                            <div class="w-1/4 h-1/4 bg-slate-900"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-slate-900"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-slate-900"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-slate-900"></div>
                            <div class="w-1/4 h-1/4 bg-slate-900"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-slate-900"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-slate-900"></div>
                            <div class="w-1/4 h-1/4 bg-white"></div>
                            <div class="w-1/4 h-1/4 bg-slate-900"></div>
                        </div>
                    </div>
                    <p class="mt-4 font-mono font-bold text-slate-800">{{ $transaction ? 'TKT-' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT) : 'TKT-001293848' }}</p>
                </div>

                <!-- SEKSI ULASAN & RATING JIKA EVENT SUDAH SELESAI -->
                @if($transaction && in_array(strtolower($transaction->status), ['success', 'settlement']) && $transaction->event && $transaction->event->date && $transaction->event->date->isPast())
                    <div class="pt-6 border-t border-slate-200">
                        @if($transaction->review)
                            <!-- Tampilan Ulasan Yang Sudah Dikirim -->
                            <div class="p-6 bg-amber-50 border border-amber-200 rounded-3xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-amber-900 text-sm">Ulasan Anda</h4>
                                    <div class="flex text-amber-500 font-bold text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span>{{ $i <= $transaction->review->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-amber-800 italic">"{{ $transaction->review->review }}"</p>
                            </div>
                        @else
                            <!-- Form Pengisian Ulasan -->
                            @auth
                                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200">
                                    <h4 class="font-bold text-slate-800 text-center mb-1">Berikan Ulasan & Rating</h4>
                                    <p class="text-xs text-slate-400 text-center mb-4">Bagikan pengalaman Anda mengikuti {{ $transaction->event->title }}</p>
                                    
                                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 mb-1 uppercase tracking-wider text-center">Pilih Rating Bintang</label>
                                            <div class="flex justify-center gap-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="cursor-pointer text-2xl text-slate-300 hover:text-amber-400 transition-colors">
                                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required {{ $i === 5 ? 'checked' : '' }}>
                                                        <span class="peer-checked:text-amber-400">★</span>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div>
                                            <textarea name="review" rows="3" placeholder="Tuliskan ulasan Anda..." class="w-full p-4 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none" required></textarea>
                                        </div>

                                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm shadow-md transition">
                                            Kirim Ulasan
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="p-4 bg-slate-100 rounded-2xl text-center">
                                    <p class="text-xs text-slate-500 mb-2">Event telah selesai! Silakan login untuk memberikan rating & ulasan.</p>
                                    <a href="{{ route('auth.google') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow">Login dengan Google</a>
                                </div>
                            @endauth
                        @endif
                    </div>
                @endif
            </div>

            <div class="px-8 pb-8">
                <button onclick="window.print()"
                    class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
                    Cetak / Simpan PDF
                </button>
                <a href="/"
                class="block text-center mt-4 text-slate-500 font-bold hover:text-indigo-600"> Kembali ke Beranda </a>            
            </div>
        </div>
    </div>

</body>

</html>