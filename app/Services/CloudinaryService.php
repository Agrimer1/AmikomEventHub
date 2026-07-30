<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * CloudinaryService
 *
 * Mengunggah file gambar ke Cloudinary menggunakan REST API.
 * Jika kredensial Cloudinary belum diatur atau upload gagal,
 * service ini secara otomatis fallback ke penyimpanan lokal.
 */
class CloudinaryService
{
    protected string $cloudName;
    protected string $apiKey;
    protected string $apiSecret;

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name', env('CLOUDINARY_CLOUD_NAME', 's2jmy0a6'));
        $this->apiKey    = config('services.cloudinary.api_key', env('CLOUDINARY_API_KEY', '618873684742824'));
        $this->apiSecret = config('services.cloudinary.api_secret', env('CLOUDINARY_API_SECRET', 'KHYwshqvO5jgRjPHTr4ZAqQ3Rzg'));
    }

    /**
     * Unggah file gambar ke Cloudinary.
     *
     * @param  UploadedFile  $file    File gambar dari request
     * @param  string        $folder  Nama sub-folder di Cloudinary (contoh: 'events', 'organizers')
     * @return string                 URL publik Cloudinary (secure_url) atau path penyimpanan lokal sebagai fallback
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        if (empty($this->cloudName) || empty($this->apiKey) || empty($this->apiSecret)) {
            Log::warning('[CloudinaryService] Kredensial Cloudinary tidak lengkap. Menggunakan penyimpanan lokal.');
            return $file->store($folder, 'public');
        }

        try {
            $timestamp = time();
            
            // Parameter yang di-sign untuk autentikasi API Cloudinary
            $paramsToSign = "folder={$folder}&timestamp={$timestamp}";
            $signature = sha1($paramsToSign . $this->apiSecret);

            $apiUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";

            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post($apiUrl, [
                'api_key'   => $this->apiKey,
                'timestamp' => $timestamp,
                'folder'    => $folder,
                'signature' => $signature,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $secureUrl = $data['secure_url'] ?? null;

                if ($secureUrl) {
                    Log::info("[CloudinaryService] Berhasil mengunggah file ke Cloudinary: {$secureUrl}");
                    return $secureUrl;
                }
            }

            Log::error("[CloudinaryService] Gagal mengunggah ke Cloudinary. Status: {$response->status()}, Response: {$response->body()}");

        } catch (\Exception $e) {
            Log::error('[CloudinaryService] Exception saat mengunggah ke Cloudinary: ' . $e->getMessage());
        }

        // Fallback jika Cloudinary gagal/error
        return $file->store($folder, 'public');
    }
}
