<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentYear = now()->year;

        // 1. Kartu Ringkasan Utama
        $totalEvents = Event::forUser($user)->count();
        
        $totalTransactions = Transaction::forUser($user)->count();

        $totalRevenue = Transaction::forUser($user)
            ->whereIn('status', ['settlement', 'success'])
            ->sum('total_price');

        // Event Terlaris berdasarkan tiket terjual
        $topSellingEvent = Event::forUser($user)
            ->withCount(['transactions as sold_count' => function ($query) {
                $query->whereIn('status', ['settlement', 'success']);
            }])
            ->orderByDesc('sold_count')
            ->first();

        // Total Review & Rata-rata Rating (Ketentuan UAS)
        $reviewQuery = Review::whereHas('event', function ($query) use ($user) {
            if (!$user->isSuperAdmin() && $user->organizer) {
                $query->where('organizer_id', $user->organizer->id);
            }
        });

        $totalReviews = (clone $reviewQuery)->count();
        $avgRating = $totalReviews > 0 ? (float) round((clone $reviewQuery)->avg('rating'), 1) : 0;

        // 2. Data Realtime Grafik Pendapatan Bulanan (Tahun Berjalan)
        $revenuePerMonthRaw = Transaction::forUser($user)
            ->whereIn('status', ['settlement', 'success'])
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 3. Data Realtime Grafik Jumlah Transaksi Bulanan (Tahun Berjalan)
        $transactionsPerMonthRaw = Transaction::forUser($user)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Format 12 Bulan (Januari - Desember)
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyRevenue = [];
        $monthlyTransactions = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[] = (float) ($revenuePerMonthRaw[$m] ?? 0);
            $monthlyTransactions[] = (int) ($transactionsPerMonthRaw[$m] ?? 0);
        }

        // 4. Riwayat 5 Transaksi Terakhir
        $recentTransactions = Transaction::forUser($user)
            ->with('event')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalTransactions',
            'totalRevenue',
            'topSellingEvent',
            'totalReviews',
            'avgRating',
            'months',
            'monthlyRevenue',
            'monthlyTransactions',
            'recentTransactions'
        ));
    }
}