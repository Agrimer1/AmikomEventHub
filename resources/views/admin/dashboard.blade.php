@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Analytics')
@section('page_subtitle', 'Ringkasan performa penjualan dan statistik event secara realtime')

@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-10">
    <!-- Card 1: Total Event -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Event</p>
        <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalEvents, 0, ',', '.') }}</h3>
    </div>

    <!-- Card 2: Total Transaksi -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Transaksi</p>
        <h3 class="text-2xl font-black text-slate-800">{{ number_format($totalTransactions, 0, ',', '.') }}</h3>
    </div>

    <!-- Card 3: Total Pendapatan -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Pendapatan</p>
        <h3 class="text-xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>

    <!-- Card 4: Event Terlaris -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Event Terlaris</p>
        @if($topSellingEvent)
            <h3 class="text-sm font-extrabold text-slate-800 truncate" title="{{ $topSellingEvent->title }}">{{ $topSellingEvent->title }}</h3>
            <p class="text-xs text-indigo-600 font-bold mt-1">{{ number_format($topSellingEvent->sold_count ?? 0, 0, ',', '.') }} Tiket Terjual</p>
        @else
            <h3 class="text-sm font-bold text-slate-400">Belum ada data</h3>
        @endif
    </div>

    <!-- Card 5: Total Review -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Review</p>
        <h3 class="text-2xl font-black text-purple-900">{{ number_format($totalReviews, 0, ',', '.') }}</h3>
    </div>

    <!-- Card 6: Rata-rata Rating -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-yellow-50 text-amber-500 rounded-2xl flex items-center justify-center mb-4 font-black text-xl">
            ★
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Rata-rata Rating</p>
        <h3 class="text-2xl font-black text-amber-600">{{ number_format($avgRating, 1) }} / 5.0</h3>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Chart 1: Pendapatan Bulanan -->
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h4 class="text-lg font-black text-slate-800">Grafik Pendapatan Bulanan</h4>
                <p class="text-xs text-slate-400 font-medium">Tren akumulasi pendapatan lunas di tahun {{ date('Y') }}</p>
            </div>
            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg">Rp</span>
        </div>
        <div class="h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Jumlah Transaksi Bulanan -->
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h4 class="text-lg font-black text-slate-800">Grafik Jumlah Transaksi Bulanan</h4>
                <p class="text-xs text-slate-400 font-medium">Jumlah total volume transaksi masuk per bulan</p>
            </div>
            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg">Transaksi</span>
        </div>
        <div class="h-72">
            <canvas id="transactionChart"></canvas>
        </div>
    </div>
</div>

<!-- Latest Sales Table -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex justify-between items-center">
        <div>
            <h3 class="font-black text-xl text-slate-800">Transaksi Terakhir</h3>
            <p class="text-xs text-slate-400">5 transaksi teranyar yang masuk ke dalam sistem</p>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="text-indigo-600 font-bold hover:underline text-sm">Lihat Semua &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100">
                <tr>
                    <th class="px-8 py-4 w-1/4">Tgl Transaksi</th>
                    <th class="px-8 py-4 w-1/4">Pembeli</th>
                    <th class="px-8 py-4 w-1/4">Event</th>
                    <th class="px-8 py-4 w-[10%]">Status</th>
                    <th class="px-8 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium text-sm">
                @forelse($recentTransactions as $trx)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-5 text-slate-600 max-w-xs break-all">
                        {{ $trx->created_at->format('d M Y - H:i') }}<br>
                        <span class="text-xs text-slate-400 font-mono">{{ $trx->order_id }}</span>
                    </td>
                    <td class="px-8 py-5">
                        <p class="font-bold tracking-wide text-slate-800 truncate max-w-[180px]">{{ $trx->customer_name }}</p>
                        <p class="text-xs text-slate-400 truncate max-w-[180px]">{{ $trx->customer_email }}</p>
                    </td>
                    <td class="px-8 py-5 text-slate-700 max-w-xs truncate font-bold">
                        {{ $trx->event->title ?? '-' }}
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap">
                        @if($trx->status === 'settlement' || $trx->status === 'success')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase">Success</span>
                        @elseif($trx->status === 'pending')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase">Pending</span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold uppercase">{{ $trx->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 font-black text-indigo-600 whitespace-nowrap text-right">
                        Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-slate-400">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Script Initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = @json($months);
        const revenueData = @json($monthlyRevenue);
        const transactionData = @json($monthlyTransactions);

        // 1. Chart Pendapatan Bulanan (Bar Chart)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueData,
                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    borderRadius: 10,
                    hoverBackgroundColor: '#4f46e5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return ' Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'k';
                            }
                        },
                        grid: { borderDash: [4, 4] }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Chart Jumlah Transaksi Bulanan (Line Chart)
        const ctxTransaction = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctxTransaction, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: transactionData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Transaksi: ' + context.raw + ' pesanan';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { borderDash: [4, 4] }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection