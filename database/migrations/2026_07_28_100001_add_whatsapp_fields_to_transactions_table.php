<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom payment_url dan reminder_sent_at ke tabel transactions.
     * - payment_url: URL halaman pembayaran Snap Midtrans untuk digunakan Abandoned Cart Recovery.
     * - reminder_sent_at: Timestamp pengiriman reminder WA agar tidak dikirim dua kali.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_url')->nullable()->after('snap_token');
            $table->timestamp('reminder_sent_at')->nullable()->after('payment_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_url', 'reminder_sent_at']);
        });
    }
};
