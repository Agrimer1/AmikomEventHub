@extends('layouts.app')

@section('title', 'Profil Organizer - ' . $organizer->name)

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12">
    <!-- Header Profil Organizer -->
    <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-100 shadow-sm mb-12 flex flex-col md:flex-row items-center gap-8">
        <div class="relative">
            @if($organizer->logo_url)
                <img src="{{ $organizer->logo_url }}" alt="{{ $organizer->name }}" class="w-32 h-32 rounded-3xl object-cover border-4 border-indigo-50 shadow-md">
            @else
                <div class="w-32 h-32 bg-indigo-600 text-white rounded-3xl flex items-center justify-center font-black text-4xl shadow-md shadow-indigo-100">
                    {{ strtoupper(substr($organizer->name, 0, 2)) }}
                </div>
            @endif
            <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white p-1.5 rounded-full border-2 border-white" title="Verified Organizer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>

        <div class="flex-1 text-center md:text-left space-y-2">
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                <h1 class="text-3xl md:text-4xl font-black text-slate-800">{{ $organizer->name }}</h1>
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider">Verified Organizer</span>
            </div>
            <p class="text-slate-500 font-medium text-sm">Penyelenggara Event Resmi di AmikomEventHub</p>

            <div class="pt-2 flex flex-wrap justify-center md:justify-start gap-4 text-xs font-bold text-slate-600">
                @if($organizer->email)
                    <span class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                        📧 {{ $organizer->email }}
                    </span>
                @endif
                @if($organizer->phone)
                    <span class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                        📱 {{ $organizer->phone }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-3 gap-4 w-full md:w-auto">
            <div class="bg-indigo-50/70 p-4 rounded-2xl text-center border border-indigo-100/50">
                <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider mb-1">Total Event</p>
                <p class="text-2xl font-black text-indigo-900">{{ $totalEvents }}</p>
            </div>
            <div class="bg-amber-50/70 p-4 rounded-2xl text-center border border-amber-100/50">
                <p class="text-xs text-amber-600 font-bold uppercase tracking-wider mb-1">Rating</p>
                <p class="text-2xl font-black text-amber-900 flex items-center justify-center gap-1">
                    <span class="text-amber-500 text-xl">★</span> {{ number_format($avgRating, 1) }}
                </p>
            </div>
            <div class="bg-emerald-50/70 p-4 rounded-2xl text-center border border-emerald-100/50">
                <p class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">Ulasan</p>
                <p class="text-2xl font-black text-emerald-900">{{ $totalReviews }}</p>
            </div>
        </div>
    </div>

    <!-- Event diselenggarakan -->
    <div class="mb-16 space-y-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Event Diselenggarakan</h2>
            <p class="text-slate-500 text-sm">Daftar seluruh acara resmi dari {{ $organizer->name }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($events as $event)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl transition-shadow flex flex-col justify-between">
                    <div>
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $event->poster_url }}"
                                alt="{{ $event->title }}" class="w-full h-full object-cover">
                            <span class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-md text-indigo-700 text-xs font-extrabold rounded-xl">
                                {{ $event->category->name ?? 'Event' }}
                            </span>
                        </div>
                        <div class="p-6 space-y-3">
                            <h3 class="font-extrabold text-lg text-slate-800 line-clamp-1">{{ $event->title }}</h3>
                            <p class="text-xs text-slate-400 font-medium">📅 {{ $event->date->format('d M Y - H:i') }}</p>
                            <p class="text-xs text-slate-500 line-clamp-2">{{ $event->description }}</p>
                        </div>
                    </div>

                    <div class="p-6 pt-0 flex justify-between items-center border-t border-slate-50 mt-4">
                        <span class="font-black text-indigo-600 text-lg">
                            {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-12 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-400 font-medium">Belum ada event yang dipublikasikan oleh organizer ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Seluruh Ulasan Organizer -->
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Semua Ulasan Peserta</h2>
            <p class="text-slate-500 text-sm">Reputasi dan testimoni asli dari seluruh event yang telah dilaksanakan</p>
        </div>

        <div class="space-y-4">
            @forelse($reviews as $rev)
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
                                <p class="text-xs text-slate-400">
                                    Ulasan untuk <strong class="text-indigo-600">{{ $rev->event->title ?? 'Event' }}</strong> • {{ $rev->created_at->diffForHumans() }}
                                </p>
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
                <div class="p-10 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-400 font-medium">Belum ada ulasan untuk event organizer ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
