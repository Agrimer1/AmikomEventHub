<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Simpan ulasan dan rating untuk transaksi event.
     */
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $transaction = Transaction::with(['event', 'review'])->findOrFail($request->transaction_id);

        // 1. Verifikasi Status Pembayaran Sukses
        if (!in_array(strtolower($transaction->status), ['success', 'settlement'])) {
            return back()->with('error', 'Review hanya boleh diberikan apabila pembayaran tiket telah sukses.');
        }

        // 2. Verifikasi Event Telah Selesai minimal H+1 sesuai Ketentuan UAS
        if (!$transaction->event || !$transaction->event->date || !$transaction->event->date->copy()->addDay()->isPast()) {
            return back()->with('error', 'Ulasan baru dapat diberikan minimal H+1 setelah pelaksanaan event selesai.');
        }

        // 3. Verifikasi Transaksi Belum Pernah Direview
        if ($transaction->review) {
            return back()->with('error', 'Anda sudah memberikan review untuk transaksi ini.');
        }

        // Simpan Review
        Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => auth()->id(),
            'event_id' => $transaction->event_id,
            'rating' => (int) $request->rating,
            'review' => trim($request->review),
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }
}
