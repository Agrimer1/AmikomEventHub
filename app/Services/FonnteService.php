<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
    }

    /**
     * Kirim pesan WhatsApp ke nomor tujuan.
     *
     * @param  string  $phone    Nomor HP tujuan (format: 08xxx atau 628xxx)
     * @param  string  $message  Isi pesan yang akan dikirimkan
     * @return bool              true jika berhasil, false jika gagal
     */
    public function sendMessage(string $phone, string $message): bool
    {
        // Jika token belum dikonfigurasi, skip pengiriman dan log warning
        if (empty($this->token)) {
            Log::warning('[FonnteService] FONNTE_TOKEN belum dikonfigurasi. Pengiriman WA dilewati.');
            return false;
        }

        // Normalisasi format nomor: ganti awalan 0 dengan 62
        $phone = $this->normalizePhone($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target'  => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $status = $body['status'] ?? false;

                if ($status === true || $status === 'true') {
                    Log::info("[FonnteService] Pesan WA berhasil dikirim ke {$phone}.");
                    return true;
                }

                // API mengembalikan status false (misal: nomor tidak terdaftar WA)
                $reason = $body['reason'] ?? $response->body();
                Log::warning("[FonnteService] API Fonnte menolak pengiriman ke {$phone}: {$reason}");
                return false;
            }

            Log::error("[FonnteService] HTTP Error saat mengirim WA ke {$phone}. Status: {$response->status()}. Body: {$response->body()}");
            return false;

        } catch (\Exception $e) {
            Log::error("[FonnteService] Exception saat mengirim WA ke {$phone}: " . $e->getMessage());
            return false;
        }
    }


    protected function normalizePhone(string $phone): string
    {
        // Hapus spasi, tanda hubung, dan karakter non-digit kecuali tanda +
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Ganti awalan 0 dengan 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}
