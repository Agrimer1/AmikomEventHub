<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        // Mengambil transaksi milik organizer (atau semua transaksi jika Super Admin)
        $transactions = Transaction::forUser()->with(['event', 'promoCode'])->latest()->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }
}