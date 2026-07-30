<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(protected FonnteService $fonnte)
    {
        //
    }

    public function handle(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Mencari ID transaksi tersebut di database lokal kita
        $transaction = Transaction::with(['event', 'promoCode'])->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cegah proses berulang jika status sudah lunas/sukses
        if ($transaction->status === 'settlement' || $transaction->status === 'success') {
            return response()->json(['message' => 'Already processed']);
        }

        // Logika Penerjemahan Status Midtrans API
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'success';
                $this->processSuccess($transaction);
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'settlement';
            $this->processSuccess($transaction);
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();
        return response()->json(['message' => 'OK']);
    }

    private function processSuccess(Transaction $transaction): void
    {
        $event = $transaction->event;

        // Jika tiket masih ada dan terhubung dengan data event, kurangi jumlahnya sebanyak 1
        if ($event && $event->stock > 0) {
            $event->stock = $event->stock - 1;
            $event->save();
        } else {
            Log::warning('Stock habis setelah pembayaran berhasil (Perlu proses refund opsional). Order: ' . $transaction->order_id);
        }

        // Increment promo_code count
        if ($transaction->promoCode) {
            $transaction->promoCode->increment('used_count');
        }

        // Mengirimkan email E-Ticket ke pelanggan
        try {
            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                ->send(new \App\Mail\EventTicketMail($transaction));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
        }

        // ====================================================
        // NOTIFIKASI WHATSAPP (via Fonnte)
        // Dikirim setelah pembayaran SUCCESS/SETTLEMENT.
        // Kegagalan pengiriman WA TIDAK akan menggagalkan transaksi.
        // ====================================================
        try {
            $eventName  = $event->title ?? 'Event';
            $totalFormatted = 'Rp ' . number_format((int) $transaction->total_price, 0, ',', '.');

            $message = "Halo {$transaction->customer_name},\n\n"
                . "Pembayaran Anda berhasil. ✅\n\n"
                . "Event:\n{$eventName}\n\n"
                . "Order ID:\n{$transaction->order_id}\n\n"
                . "Total:\n{$totalFormatted}\n\n"
                . "Silakan cek email Anda untuk mendapatkan E-Ticket.\n\n"
                . "Terima kasih.";

            $this->fonnte->sendMessage($transaction->customer_phone, $message);

        } catch (\Exception $e) {
            Log::error('[MidtransWebhook] Gagal mengirim notifikasi WA: ' . $e->getMessage());
        }
    }
}