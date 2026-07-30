<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\FonnteService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * CartRecovery Command
 *
 * Mencari transaksi dengan status pending yang sudah lebih dari 5 menit
 * dan belum pernah dikirim reminder WA, kemudian mengirim pesan pengingat
 * ke nomor HP pembeli agar segera menyelesaikan pembayaran.
 *
 * Cara menjalankan manual: php artisan cart:recovery
 * Dijadwalkan otomatis setiap 5 menit via Scheduler (routes/console.php).
 */
class CartRecovery extends Command
{
    /**
     * Nama dan signature Artisan Command.
     */
    protected $signature = 'cart:recovery';

    /**
     * Deskripsi singkat Command.
     */
    protected $description = 'Kirim reminder WhatsApp ke pembeli yang transaksinya masih pending lebih dari 5 menit (Abandoned Cart Recovery).';

    public function __construct(protected FonnteService $fonnte)
    {
        parent::__construct();
    }

    /**
     * Eksekusi utama Command.
     */
    public function handle(): int
    {
        $this->info('[CartRecovery] Memulai pengecekan transaksi abandoned cart...');

        // Cari transaksi pending yang:
        // 1. Status = 'pending' (belum dibayar)
        // 2. Dibuat lebih dari 5 menit yang lalu (sudah cukup waktu untuk user bayar)
        // 3. Belum pernah dikirim reminder (reminder_sent_at IS NULL)
        // 4. Memiliki payment_url (hanya untuk transaksi Midtrans, bukan event gratis)
        $transactions = Transaction::with('event')
            ->where('status', 'pending')
            ->where('created_at', '<=', now()->subMinutes(5))
            ->whereNull('reminder_sent_at')
            ->whereNotNull('payment_url')
            ->get();

        if ($transactions->isEmpty()) {
            $this->info('[CartRecovery] Tidak ada transaksi abandoned cart yang perlu diproses.');
            Log::info('[CartRecovery] Tidak ada transaksi abandoned cart.');
            return Command::SUCCESS;
        }

        $sentCount = 0;
        $failCount = 0;

        foreach ($transactions as $transaction) {
            $this->line("  → Memproses Order: {$transaction->order_id} | HP: {$transaction->customer_phone}");

            $eventName = $transaction->event->title ?? 'Event';
            $paymentUrl = $transaction->payment_url;

            $message = "Halo {$transaction->customer_name},\n\n"
                . "Pesanan tiket Anda belum selesai.\n\n"
                . "Event:\n{$eventName}\n\n"
                . "Order ID:\n{$transaction->order_id}\n\n"
                . "Klik link berikut untuk melanjutkan pembayaran:\n{$paymentUrl}\n\n"
                . "Link pembayaran masih aktif selama transaksi belum expired.\n\n"
                . "Terima kasih.";

            $sent = $this->fonnte->sendMessage($transaction->customer_phone, $message);

            if ($sent) {
                // Tandai bahwa reminder sudah dikirim agar tidak dikirim lagi
                $transaction->update(['reminder_sent_at' => now()]);
                $sentCount++;
                Log::info("[CartRecovery] Reminder WA berhasil dikirim. Order: {$transaction->order_id}, HP: {$transaction->customer_phone}");
            } else {
                $failCount++;
                Log::warning("[CartRecovery] Gagal mengirim reminder WA. Order: {$transaction->order_id}, HP: {$transaction->customer_phone}");
            }
        }

        $this->info("[CartRecovery] Selesai. Terkirim: {$sentCount}, Gagal: {$failCount}.");
        Log::info("[CartRecovery] Selesai. Total: {$transactions->count()}, Terkirim: {$sentCount}, Gagal: {$failCount}.");

        return Command::SUCCESS;
    }
}
