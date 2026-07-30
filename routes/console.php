<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Abandoned Cart Recovery Scheduler
|--------------------------------------------------------------------------
| Menjalankan command cart:recovery setiap 5 menit untuk mengirim
| notifikasi WhatsApp kepada pembeli yang belum menyelesaikan pembayaran.
|
| Untuk mengaktifkan scheduler di server/lokal, tambahkan Cron Job:
| * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
|
| Cara test manual: php artisan cart:recovery
*/
Schedule::command('cart:recovery')->everyFiveMinutes();
