<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PromoCode;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'promo_code' => 'nullable|string|max:50',
        ]);

        // 2. Cegah Check-out Jika Tiket Habis
        if ($event->stock <= 0) {
            return back()->with('error', 'Tiket sudah habis.');
        }

        // 3. Pengecekan & Kalkulasi Kode Promo
        $promoCode = null;
        $discountAmount = 0;

        if ($request->filled('promo_code')) {
            $codeStr = strtoupper(trim($request->promo_code));
            $promoCode = PromoCode::where('code', $codeStr)->first();

            if (!$promoCode || !$promoCode->isValidForAmount($event->price)) {
                return back()->with('error', 'Kode promo tidak berlaku, sudah habis, atau tidak memenuhi syarat minimum transaksi.');
            }

            $discountAmount = $promoCode->calculateDiscount($event->price);
        }

        // 4. Cek Apakah Event Gratis (Harga = 0)
        $isFreeEvent = ($event->price == 0);
        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $adminFee = $isFreeEvent ? 0 : 5000;
        $totalPrice = max(0, ($event->price + $adminFee) - $discountAmount);

        // ========================================================
        // LOGIKA PENANGANAN EVENT GRATIS (BYPASS MIDTRANS)
        // ========================================================
        if ($isFreeEvent || $totalPrice == 0) {
            $transaction = Transaction::create([
                'event_id' => $event->id,
                'promo_code_id' => $promoCode?->id,
                'order_id' => $orderId,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price' => 0,
                'discount_amount' => $discountAmount,
                'status' => 'success',
                'snap_token' => null,
            ]);

            // Stok langsung berkurang 1
            if ($event->stock > 0) {
                $event->decrement('stock');
            }

            // Increment jumlah penggunaan promo code jika ada
            if ($promoCode) {
                $promoCode->increment('used_count');
            }

            // Kirim E-Ticket ke email
            try {
                \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                    ->send(new \App\Mail\EventTicketMail($transaction));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email E-Ticket Event Gratis: ' . $e->getMessage());
            }

            // Redirect langsung ke halaman Success
            return redirect()->route('checkout.success', $transaction->order_id)
                ->with('success', 'Pendaftaran berhasil. Tiket gratis Anda berhasil dibuat.');
        }

        // ========================================================
        // LOGIKA PENANGANAN EVENT BERBAYAR (MIDTRANS SNAP)
        // ========================================================
        $transaction = Transaction::create([
            'event_id' => $event->id,
            'promo_code_id' => $promoCode?->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'discount_amount' => $discountAmount,
            'status' => 'pending',
        ]);

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
        ];

        try {
            // Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // URL Halaman Pembayaran Snap (untuk Abandoned Cart Recovery)
            $paymentUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}";
            if (env('MIDTRANS_IS_PRODUCTION', false)) {
                $paymentUrl = "https://app.midtrans.com/snap/v2/vtweb/{$snapToken}";
            }

            // Simpan Snap Token & Payment URL
            $transaction->update([
                'snap_token'  => $snapToken,
                'payment_url' => $paymentUrl,
            ]);

            // Arahkan ke halaman pembayaran
            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            // Hapus transaksi jika gagal membuat token
            $transaction->delete();

            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with(['event', 'promoCode'])
            ->where('order_id', $order_id)
            ->firstOrFail();

        // Jika transaksi bernilai 0 / gratis, langsung alihkan ke halaman success
        if ($transaction->total_price == 0 || $transaction->status === 'success') {
            return redirect()->route('checkout.success', $transaction->order_id);
        }

        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with(['event', 'promoCode'])->where('order_id', $order_id)->firstOrFail();

        // Jika transaksi berharga 0 atau sudah berhasil, tampilkan halaman sukses tanpa memanggil API Midtrans
        if ($transaction->total_price == 0 || strtolower($transaction->status) === 'success') {
            return view('checkout.success', compact('transaction', 'categories'));
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $status = \Midtrans\Transaction::status($order_id);

            if ($status) {
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

                if (in_array($trx_status, ['settlement', 'capture'])) {
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => 'success']);

                        if ($transaction->event && $transaction->event->stock > 0) {
                            $transaction->event->stock = $transaction->event->stock - 1;
                            $transaction->event->save();
                        }

                        if ($transaction->promoCode) {
                            $transaction->promoCode->increment('used_count');
                        }

                        try {
                            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                                ->send(new \App\Mail\EventTicketMail($transaction));
                        } catch (\Exception $e) {
                            \Log::error('Gagal mengirim email E-Ticket secara manual (Bypass): ' . $e->getMessage());
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }
}