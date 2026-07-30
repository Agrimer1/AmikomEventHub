<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;

class EventController extends Controller
{
    // ======================
    // USER AREA
    // ======================

    public function show(Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        // Load relasi ulasan beserta data user pembuat ulasan
        $event->load(['reviews.user', 'category']);

        // Cari transaksi user login yang lunas dan belum pernah di-review untuk event ini
        $transaction = null;
        if (auth()->check()) {
            $transaction = Transaction::where('event_id', $event->id)
                ->where(function ($query) {
                    $query->where('customer_email', auth()->user()->email)
                        ->orWhere('customer_name', auth()->user()->name);
                })
                ->whereIn('status', ['success', 'settlement'])
                ->whereDoesntHave('review')
                ->latest()
                ->first();
        }
        
        // Me-render view dengan membawa data kategori, event, dan transaksi user (jika ada)
        return view('event-detail', compact('categories', 'event', 'transaction'));
    }
    
    // Halaman checkout
    public function checkout()
    {
        return view('checkout');
    }

    // Halaman tiket
    public function ticket(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if ($orderId) {
            $transaction = Transaction::with(['event', 'review'])->where('order_id', $orderId)->first();
        } else {
            $transaction = Transaction::with(['event', 'review'])->latest()->first();
        }

        return view('ticket', compact('transaction'));
    }
}
