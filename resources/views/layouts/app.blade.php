<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AmikomEventHub - Temukan Event Seru!')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between">

    <!-- Navigation -->
    <nav class="glass sticky top-6 z-50 mx-4 md:mx-10 px-6 py-4 rounded-2xl border border-slate-200/60 shadow-lg flex justify-between items-center">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-indigo-200">
                AH
            </div>
            <span class="text-xl font-extrabold tracking-tight text-slate-800">AmikomEventHub</span>
        </a>

        <!-- Center Menu -->
        <div class="hidden md:flex items-center gap-8 font-bold text-sm text-slate-600">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Home</a>
            <a href="{{ route('home') }}#events" class="hover:text-indigo-600 transition">Events</a>
            @auth
                <a href="{{ route('ticket') }}" class="hover:text-indigo-600 transition">My Ticket</a>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isOrganizer())
                    <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:underline">Panel Dashboard</a>
                @endif
            @endauth
        </div>

        <!-- Right User / Auth Section -->
        <div class="flex items-center gap-4">
            @auth
                <div class="flex items-center gap-3 bg-slate-100/80 px-3.5 py-1.5 rounded-2xl border border-slate-200/50">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-slate-800 truncate max-w-[120px]">Halo, {{ auth()->user()->name }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition border border-rose-200">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-indigo-600 rounded-xl font-bold text-xs shadow-sm transition">
                    Masuk
                </a>
                <a href="{{ route('auth.google') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md shadow-indigo-200 transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Google Login</span>
                </a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-indigo-900 text-indigo-100 py-16 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4 col-span-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white text-indigo-900 rounded-xl flex items-center justify-center font-black text-xl">
                        AH
                    </div>
                    <span class="text-2xl font-black text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300 text-sm">Platform penjualan tiket event online terpercaya untuk mahasiswa dan penyelenggara profesional.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Navigasi Utama</h4>
                <ul class="space-y-3 text-sm font-medium text-indigo-200">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('home') }}#events" class="hover:text-white transition">Semua Event</a></li>
                    <li><a href="{{ route('ticket') }}" class="hover:text-white transition">E-Ticket Saya</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Bantuan & Kontak</h4>
                <ul class="space-y-3 text-sm font-medium text-indigo-200">
                    <li>support@amikomeventhub.ac.id</li>
                    <li>+62 812 3456 7890</li>
                    <li>Yogyakarta, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-10 mt-10 border-t border-indigo-800/60 text-center text-indigo-400 text-xs font-medium">
            &copy; {{ date('Y') }} AmikomEventHub. Built with Laravel 11, Socialite & Tailwind CSS.
        </div>
    </footer>

</body>
</html>