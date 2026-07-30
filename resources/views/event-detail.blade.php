@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    $avgRating = $event->averageRating();
    $reviewsCount = $event->reviewsCount();
@endphp

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Left: Poster & Organizer Info -->
        <div class="lg:col-span-1">
            <div class="sticky top-32">
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                    ? asset('storage/' . $event->poster_path)
                    : 'https://placehold.co/200x600' }}"
            alt="{{ $event->title }}"
            class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-[3/4]">
                
                <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <h4 class="font-bold mb-4 text-slate-700 uppercase tracking-wider text-xs">Penyelenggara Event</h4>
                    @if($event->organizer)
                        <a href="{{ route('organizers.show', $event->organizer->slug) }}" class="flex items-center gap-4 group">
                            @if($event->organizer->logo_path)
                                <img src="{{ asset('storage/' . $event->organizer->logo_path) }}" alt="{{ $event->organizer->name }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-200">
                            @else
                                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                                    {{ strtoupper(substr($event->organizer->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-slate-800 group-hover:text-indigo-600 transition">{{ $event->organizer->name }}</p>
                                <p class="text-xs text-indigo-600 font-semibold">Lihat Profil Organizer &rarr;</p>
                            </div>
                        </a>
                    @else
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                                AH
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">AmikomEventHub Admin</p>
                                <p class="text-xs text-slate-500">Verified Organizer</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Details -->
        <div class="lg:col-span-2 space-y-12">
            <div class="space-y-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">{{ $event->category->name ?? 'Event' }}</span>
                    
                    <!-- Rating Badge -->
                    <div class="flex items-center gap-2 px-4 py-1.5 bg-amber-50 border border-amber-200 text-amber-700 rounded-full text-sm font-bold">
                        <span class="text-amber-500 text-base">★</span>
                        <span>{{ number_format($avgRating, 1) }} / 5.0</span>
                        <span class="text-slate-400 font-normal">({{ $reviewsCount }} ulasan)</span>
                    </div>
                </div>

                <h1 class="text-4xl md:text-5xl font-black leading-tight">{{ $event->title }}</h1>
                <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ $event->date->format('l, d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <div class="prose prose-slate max-w-none">
                <h3 class="text-2xl font-bold mb-4">Deskripsi Event</h3>
                <div class="text-lg text-slate-600 leading-relaxed">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            <div
                class="bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <p class="text-indigo-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                        <h2 class="text-5xl font-black">
                            {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                            <span class="text-lg font-medium text-indigo-200">/ orang</span>
                        </h2>
                        <p class="mt-4 text-indigo-100 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa stok: <span class="font-bold underline">{{ $event->stock }} Tiket lagi!</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('checkout.create', $event->id) }}"
                            class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                <!-- Decoration -->
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400 opacity-20 rounded-full"></div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl font-bold">Kebijakan Tiket</h3>
                <ul class="space-y-3 text-slate-500">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Tiket dapat discan di pintu masuk (Check-in).
                    </li>
                    <li class="flex items-start gap-2 text-rose-500">
                        <svg class="w-5 h-5 text-rose-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tiket yang sudah dibeli tidak dapat direfund.
                    </li>
                </ul>
            </div>

            <!-- SEKSI RATING & ULASAN PESERTA -->
            <div class="pt-8 border-t border-slate-200">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-2xl font-black">Ulasan Peserta</h3>
                        <p class="text-slate-500 text-sm">Penilaian dan komentar dari peserta yang telah mengikuti event ini.</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-black text-slate-800">{{ number_format($avgRating, 1) }} <span class="text-xl text-slate-400">/ 5.0</span></div>
                        <div class="flex items-center justify-end gap-1 text-amber-400 text-lg">
                            @for ($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($avgRating) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="p-4 mb-6 bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold rounded-2xl text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 mb-6 bg-rose-100 border border-rose-200 text-rose-700 font-bold rounded-2xl text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- FORM REVIEW HANYA TAMPIL JIKA SYARAT TERPENUHI (H+1 SETELAH EVENT) -->
                @if(auth()->check() && isset($transaction) && $transaction && in_array(strtolower($transaction->status), ['success', 'settlement']) && !$transaction->review && $event->date->copy()->addDay()->isPast())
                    <div class="mb-8 p-8 bg-slate-50 border border-slate-200 rounded-3xl shadow-sm">
                        <h4 class="text-xl font-bold text-slate-800 mb-2">Berikan Ulasan & Rating Anda</h4>
                        <p class="text-sm text-slate-500 mb-6">Anda telah mengikuti event ini. Bagikan ulasan dan pengalaman Anda untuk peserta lainnya!</p>

                        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Pilih Rating Bintang</label>
                                <div class="flex items-center gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer text-3xl text-slate-300 hover:text-amber-400 transition-colors">
                                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required {{ $i === 5 ? 'checked' : '' }}>
                                            <span class="peer-checked:text-amber-400">★</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Ulasan Anda</label>
                                <textarea name="review" rows="4" placeholder="Tuliskan pengalaman atau ulasan Anda tentang acara ini..." class="w-full p-4 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none font-medium" required></textarea>
                            </div>

                            <button type="submit" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-base shadow-lg shadow-indigo-200 transition">
                                Kirim Ulasan Sekarang
                            </button>
                        </form>
                    </div>
                @endif

                <!-- DAFTAR ULASAN PESERTA -->
                <div class="space-y-6">
                    @forelse($event->reviews as $rev)
                        <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-3">
                                    @if($rev->user?->avatar)
                                        <img src="{{ $rev->user->avatar }}" alt="{{ $rev->user->name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($rev->user->name ?? 'User', 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="font-bold text-slate-800">{{ $rev->user->name ?? 'Peserta' }}</h5>
                                        <p class="text-xs text-slate-400">{{ $rev->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 text-amber-400 text-sm font-bold">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                    @endfor
                                    <span class="ml-1 text-xs text-slate-500">({{ $rev->rating }}.0)</span>
                                </div>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed pl-13">
                                "{{ $rev->review }}"
                            </p>
                        </div>
                    @empty
                        <div class="p-10 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                            <p class="text-slate-400 font-medium">Belum ada ulasan untuk event ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
@endsection